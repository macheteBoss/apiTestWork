<?php
require_once 'Route.php';

$pos1 = strpos($_SERVER["REQUEST_URI"], '/', 3);
$buf = substr($_SERVER["REQUEST_URI"], $pos1+1);

$data = array_filter(explode("/", $buf));

$apiName = $data[1];

try {
    $api = new Route($apiName);
    $run = $api->marsh();
    echo $run->run();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage(), 'code' => $e->getCode()));
}