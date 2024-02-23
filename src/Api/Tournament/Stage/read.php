<?php

require_once "../../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$database = new DatabaseManager();

if(!$database->getStages()) {
    http_response_code(404);
    echo json_encode(array("message" => "No Stages found."));
}
else {
    http_response_code(200);
    echo json_encode($database->getStages());
}
