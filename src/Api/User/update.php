<?php

require_once "../../Config/Database.php";
require_once "../Objects/User.php";
require_once "../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT"); // Changed POST to PUT.
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new DatabaseManager();

$data = json_decode(file_get_contents("php://input")); //Get user input
$user = new User($data->u_id, $data->username, $data->password, $data->email);

if($database->updateUser($user)) {
    http_response_code(200);
    echo "updated";
}
else  {
    http_response_code(400);
    echo "failed";
}

// TODO: Change this to allow individual info to be updated.