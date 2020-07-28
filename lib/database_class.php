<?php
require_once "config_class.php";

class DataBase_class {

    private $config;
    private $mysqli;

    public function __construct()
    {
        $this->config = new Config_class();
        $this->mysqli = new mysqli($this->config->host, $this->config->user, $this->config->password, $this->config->db);
        $this->mysqli->query("SET NAMES 'utf8'");
    }

    public function getConnect() {
        if ($this->mysqli->connect_errno) {
            return false;
        }
        return true;
    }

    public function query($query) {
        return $this->mysqli->query($query);
    }

    public function select($table_name, $fields, $where = "", $order = "", $up = true, $limit = "", $join = "") {
        if($order) {
            if($order != "RAND()") {
                $order = "ORDER BY `$order`";
                if(!$up) $order .= " DESC";
            } else $order = "ORDER BY $order";
        }
        if($limit) $limit = "LIMIT $limit";
        if($where) $query = "SELECT $fields FROM $table_name $join WHERE $where $order $limit";
        else $query = "SELECT $fields FROM $table_name $order $limit";

        $result_set = $this->query($query);
        if(!$result_set) return false;
        $data = array();
        $i = 0;
        while($row = $result_set->fetch_assoc()) {
            $data[$i] = $row;
            $i++;
        }
        $result_set->close();
        return $data;
    }

    public function insert($table_name, $new_values) {
        $query = "INSERT INTO $table_name (";
        foreach ($new_values as $field=>$value) $query .= "`".$field."`,";
        $query = substr($query, 0, -1);
        $query .= ") VALUES (";
        foreach ($new_values as $value) $query .= "'".addslashes($value)."',";
        $query = substr($query, 0, -1);
        $query .= ")";
        return $this->query($query);
    }

    public function getField($table_name, $field_out, $field_in, $value_in) {
        $data = $this->select($table_name, $field_out, "`".$field_in."`='".addslashes($value_in)."'");
        if(count($data) != 1) return false;
        return $data[0][$field_out];
    }

    public function getFieldOnID($table_name, $id, $field_out) {
        if(!$this->existsID($table_name, $id)) return false;
        return $this->getField($table_name, $field_out, "id", $id);
    }

    public function getAll($table_name, $where = "", $order = "", $up = true, $limit = 10, $join = "") {
        return $this->select($table_name, "*", $where, $order, $up, $limit, $join);
    }

    public function getElementOnID($table_name, $id) {
        if(!$this->existsID($table_name, $id)) return false;
        $arr = $this->select($table_name, "*", "`id`='".$id."'");
        return $arr[0];
    }

    public function getElementOnField($table_name, $field, $value) {
        $arr = $this->select($table_name, "*", "`".$field."`='".addslashes($value)."'");
        return $arr[0];
    }

    public function getCount($table_name) {
        $data = $this->select($table_name, array("COUNT(`id`)"));
        return $data[0]["COUNT(`id`)"];
    }

    public function isExists($table_name, $field, $value) {
        $data = $this->select($table_name, array("id"), "`".$field."`='".addslashes($value)."'");
        if(count($data) === 0) return false;
        return true;
    }

    private function existsID($table_name, $id) {
        $data = $this->select($table_name, array("id"), "`id`='".addslashes($id)."'");
        if(count($data) === 0) return false;
        return true;
    }

    public function existsColumn($table_name, $column) {
        $data = $this->select("information_schema.COLUMNS", array("COLUMN_NAME"), "`TABLE_NAME` = '".$table_name."' AND `COLUMN_NAME`='".$column."'");
        if(empty($data)) return false;
        return true;
    }

    public function destruct() {
        if($this->mysqli) $this->mysqli->close();
    }

}