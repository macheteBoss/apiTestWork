<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/database_class.php";
$db = new DataBase_class();

$status = "";
$msg_box = "";
$errors = array();
$invalid = array();
$fatal = false;

if($db->getConnect()) {

    if($_POST["table"] == "") {
        $errors[] = "Укажите таблицу для заполнения.";
        $invalid[] = "table";
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/upload/';

    if(!mkdir($uploadDir, 0777, false)) {
        $errors[] = "Произошла ошибка. Попробуйте пожалуйста позже...";
        $invalid[] = "loadData";
    }

    if($_FILES && $_FILES["file"]['tmp_name'] != "") {
        $allowed_extensions = array("application/json");
        if(!in_array($_FILES["file"]["type"], $allowed_extensions)){
            $errors[] = "Неверный формат файла! Допустим только .json";
            $invalid[] = "file";
        } else if(move_uploaded_file($_FILES["file"]['tmp_name'], $uploadDir . basename($_FILES["file"]['name']))) {
            $file = realpath($uploadDir . basename($_FILES["file"]['name']));
            $stringJson = file_get_contents($file);
            $array = json_decode($stringJson, true);

            if($array["data"] && count($array["data"])) {
                foreach ($array["data"] as $item) {
                    $el = $item;
                    if($n_field = array_search("", $el)) {
                        unset($el[$n_field]);
                    }
                    if($_POST["table"] == "products" && array_key_exists("categories", $el)) {
                        unset($el["categories"]);
                    }
                    $data = $db->insert($_POST["table"], $el);
                    if($_POST["table"] == "products" && array_key_exists("categories", $item)) {
                        foreach ($item["categories"] as $cat) {
                            $buf = array("cat_id" => $cat["id"], "prod_id" => $item["id"]);
                            $catProd = $db->insert("cat_prod", $buf);
                        }
                        unset($item["categories"]);
                    }
                }
            }

            unlink($file);
            rmdir($uploadDir);
        }
    } else {
        $errors[] = "Укажите файл импорта.";
        $invalid[] = "file";
    }
} else {
    $errors["fatal"] = "Не удалось соединиться с базой данных..";
    $invalid["fatal"] = "loadData";
    $fatal = true;
}

if(empty($errors)) {
    $status = "success";
    $msg_box = "Данные успешно загружены";
} else {
    $msg_box = $errors;
    $status = "error";
}

echo json_encode(array(
    'result' => $msg_box,
    'status' => $status,
    'invalid' => $invalid,
    'fatal' => $fatal
));