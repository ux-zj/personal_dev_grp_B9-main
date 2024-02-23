<?php

require_once "../../Config/DatabaseManager.php";
require_once "../Objects/User.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$database = new DatabaseManager();

$id = $_GET['u_id'] ?? die();

$userFound = $database->getUserById($id);

if($userFound != null) {
    http_response_code(200);
    echo json_encode($userFound->getUserData());
}
else {
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}

// TODO: Change response codes because they could be wrong.