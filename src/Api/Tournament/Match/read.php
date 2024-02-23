<?php

require_once "../../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$database = new DatabaseManager();

$stmt = $database->getMatches();
$stmt->store_result();
$stmt->bind_result($m_id, $m_results, $s_id);

$matches_array = array();
$matches_array['matches'] = array();

$match_found = false;

while($stmt->fetch()){
    $match_found = true;
    $matches = array(
        "m_id" => $m_id,
        "m_results" => $m_results,
        "s_id" => $s_id
    );
    array_push($matches_array['matches'], $matches);
}
$stmt->free_result();
$stmt->close();

if(!$match_found) {
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}
else {
    http_response_code(200);
    echo json_encode($matches_array);
}
// TODO: Close
