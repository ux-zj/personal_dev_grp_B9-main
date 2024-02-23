<?php

require_once "../../../Config/DatabaseManager.php";
require_once "../../Objects/Stage.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$database = new DatabaseManager();

if(isset($_GET['t_id'])){
    $id = $_GET['t_id'];
    $stageFound = $database->getStageByTournamentId($id) ?? null;
} elseif(isset($_GET['s_id'])){
    $id = $_GET['s_id'] ?? die();
    $stageFound = $database->getStageById($id)->getStageData();
} else {
    die();
}

if($stageFound != null) {
    http_response_code(200);
    echo json_encode($stageFound);
}
else {
    http_response_code(404);
    echo json_encode(array());
}

// TODO: Change response codes because they could be wrong.