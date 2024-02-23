<?php

require_once "../../Config/DatabaseManager.php";
require_once "../Objects/Activity.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$database = new DatabaseManager();

$id = $_GET['act_id'] ?? die();

$activityFound = $database->getActivityById((int) $id);

if ($activityFound != null) {
    http_response_code(200);
    echo json_encode($activityFound);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No activity found."));
}

// TODO: Change response codes because they could be wrong.