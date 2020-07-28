<?php
require_once 'Api.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database_class.php';

class Categories extends Api
{
    public $apiName = 'categories';

    public function indexAction()
    {
        $db = new DataBase_class();
        $categories = $db->getAll("categories", "", "id", true, "");
        if($categories){
            $resultArray["data"] = $categories;
            return $this->response($resultArray, 200);
        }
        return $this->response('Data not found', 404);
    }

    public function viewAction()
    {
        $id = array_shift($this->requestUri);
        if($id) {
            $db = new DataBase_class();
            $category = $db->getElementOnID("categories", $id);
            if($category){
                $resultArray["data"] = $category;
                return $this->response($resultArray, 200);
            }
            unset($db);
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

}