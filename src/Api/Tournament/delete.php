<?php

require_once "../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$database = new DatabaseManager();

$data = json_decode(file_get_contents("php://input")); //Get user input

if ($database->deleteTournament($data->t_id)) {
    http_response_code(200);
    echo "Deleted";
} else {
    http_response_code(503);
    echo "failed";
}