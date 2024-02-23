<?php

require_once "../../Config/DatabaseManager.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$database = new DatabaseManager();

$stmt = $database->getUsers();
$stmt->store_result();
$stmt->bind_result($uid, $username, $password, $email);

$user_array = array();
$user_array['users'] = array();

$found_user = false;

while($stmt->fetch()){
    $found_user = true;
    $user_item = array(
        "uid" => $uid,
        "username" => $username,
        "password" => $password,
        "email" => $email
    );
    array_push($user_array["users"], $user_item);
}

$stmt->free_result();
$stmt->close();

if(!$found_user) {
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}
else {
    http_response_code(200);
    echo json_encode($user_array);
    //printf("<pre>%s</pre>", json_encode($posts_array, JSON_PRETTY_PRINT));
}
// TODO: Close
