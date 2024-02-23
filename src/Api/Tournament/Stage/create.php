<?php

require_once "../../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new DatabaseManager();

$data = json_decode(file_get_contents("php://input")); //Get user input

if (!empty($data->activity_id) && !empty($data->tournament_id) && !empty($data->format)) {

    if ($database->createStage($data->team_size, $data->format, $data->activity_id, $data->tournament_id)) {
        http_response_code(201);
        echo "created";
    } else {
        http_response_code(503);
        echo "failed";
    }
}
else {
    http_response_code(400);
    echo "Unable to  create stage.";
}
