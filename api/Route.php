<?php

require_once "Categories.php";
require_once "Products.php";

class Route {

    public $apiName;

    public function __construct($apiName) {
        $this->apiName = $apiName;
    }

    public function marsh() {
        switch ($this->apiName) {
            case "categories":
                $api = new Categories();
                break;
            case "products":
                $api = new Products();
                break;
            default: $api = "error";
        }
        return $api;
    }

}

?>