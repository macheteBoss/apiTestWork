<?php
require_once 'Api.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database_class.php';

class Products extends Api
{
    public $apiName = 'products';

    public function indexAction()
    {
        $db = new DataBase_class();
        $fields = "*";
        $where = "";
        $order = "";
        $up = true;
        $limit = "";

        $join = "";

        if($this->requestUri) {
            $params = $this->requestParams;
            foreach ($params as $key=>$param) {
                if($key == "sort") {
                    if($param[0] == "-") {
                        $up = false;
                        $param = substr($param, 1);
                    }
                    $order = $param;
                } else if($key == "maxItems") {
                    $limit = $param;
                } else if ($key == "startFrom") {
                    $where .= "p.id > $param|";
                } else if($key == "filter") {
                    foreach ($param as $pk=>$pi) {
                        if($pk == "title") {
                            $where .= "p.$pk LIKE '%".$pi."%'|";
                        } else if($pk == "producer") {
                            if(is_array($pi)) {
                                $data = "";
                                foreach ($pi as $item) {
                                    $data .= "p.producer LIKE '%".$item."%' OR ";
                                }
                                $data = substr($data, 0, -4);
                                $where .= "$data|";
                            } else {
                                $where .= "p.producer LIKE '%".$pi."%'|";
                            }
                        } else if($pk == "categoryId") {
                            $fields = "p.id as id,p.title as title,p.short_description as short_description, p.image_url as image_url, p.amount as amount, p.price as price, p.producer as producer";
                            $join = "INNER JOIN cat_prod ON cat_prod.prod_id = p.id INNER JOIN categories c ON c.id = cat_prod.cat_id";
                            $where .= "c.id = $pi|";
                        } else if($pk == "parentCategoryId") {
                            $cats = $db->getAll("categories", "", "", true, "");
                            $data = $this->recursion(intval($pi), $cats);
                            array_push($data, $pi);
                            $ids = implode(",", $data);
                            $fields = "p.id as id,p.title as title,p.short_description as short_description, p.image_url as image_url, p.amount as amount, p.price as price, p.producer as producer";
                            $join = "INNER JOIN cat_prod ON cat_prod.prod_id = p.id INNER JOIN categories c ON c.id = cat_prod.cat_id";
                            $where .= "c.id IN ($ids)|";
                        }

                    }
                }
            }
            $where = str_replace("|", " AND ", substr($where, 0, -1));
        }

        $products = $db->select("products p", $fields, $where, $order, $up, $limit, $join);
        if($products){
            $resultArray["data"] = $products;
            $fields = "c.id as id,c.title as title, c.parent_id as parent_id";
            $join = "INNER JOIN cat_prod ON cat_prod.cat_id = c.id INNER JOIN products p ON p.id = cat_prod.prod_id";
            foreach ($products as $key=>$prod) {
                $where = "p.id = ".$prod["id"];
                $resultArray["data"][$key]["categories"][] = $db->select("categories c", $fields, $where, $order, $up, $limit, $join);
            }
            unset($db);
            return $this->response($resultArray, 200);
        }
        return $this->response('Data not found', 404);
    }

    public function viewAction()
    {
        $db = new DataBase_class();
        $fields = "*";
        $where = "";
        $order = "";
        $up = true;
        $limit = "";

        $join = "";

        $id = array_shift($this->requestUri);
        if($id) {
            $product = $db->getElementOnID("products", $id);
            if($product){
                $resultArray["data"] = $product;
                $fields = "c.id as id,c.title as title, c.parent_id as parent_id";
                $join = "INNER JOIN cat_prod ON cat_prod.cat_id = c.id INNER JOIN products p ON p.id = cat_prod.prod_id";
                $where = "p.id = ".$id;
                $resultArray["data"]["categories"][] = $db->select("categories c", $fields, $where, $order, $up, $limit, $join);
                unset($db);
                return $this->response($resultArray, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    public function createAction()
    {
        return $this->response("Error", 401);
    }

    public function updateAction()
    {
        return $this->response("Update error", 400);
    }

    public function deleteAction()
    {
        return $this->response("Delete error", 401);
    }

    public function recursion($id, $data) {
        $result = array();
        $buf = array();
        foreach ($data as $item) {
            if($item["parent_id"] == $id) {
                $result[] = $item["id"];
            } else {
                $buf[] = $item;
            }
        }

        foreach ($result as $item) {
            $res = $this->recursion($item, $buf);
            $result = array_merge($result, $res);
        }

        return $result;
    }

}