<?php

require_once "../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new DatabaseManager();

$data = json_decode(file_get_contents("php://input")); //Get user input

if (!empty($data->title) && !empty($data->owner) && !empty($data->date)) {

    $result = $database->createTournament($data->owner, $data->title, $data->private, $data->date);

    if ($result != -1) {
        http_response_code(201);
        echo $result;
    } else {
        http_response_code(503);
        echo -1;
    }
}
else {
    http_response_code(400);
    echo -1;
}
