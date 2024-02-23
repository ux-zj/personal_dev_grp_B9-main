<?php

require_once "../../../Config/DatabaseManager.php";
require_once "../../Objects/Matchup.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$database = new DatabaseManager();

$id = $_GET['m_id'] ?? die();

$matchFound = $database->getMatchById($id);
//print_r($matchFound);

if($matchFound != null) {
    http_response_code(200);
    echo json_encode($matchFound->getMatchData());
}
else {
    http_response_code(404);
    echo json_encode(array("message" => "No match found."));
}

// TODO: Change response codes because they could be wrong.