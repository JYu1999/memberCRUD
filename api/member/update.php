<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Member.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate member object
$member = new Member($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to update
$member->id = $data->id;

// Set member properties if they are present in the JSON data
if (isset($data->account)) {
    $member->account = $data->account;
}
if (isset($data->password)) {
    $member->password = $data->password;
}
if (isset($data->first_name)) {
    $member->first_name = $data->first_name;
}
if (isset($data->last_name)) {
    $member->last_name = $data->last_name;
}
if (isset($data->gender)) {
    $member->gender = $data->gender;
}
if (isset($data->birthday)) {
    $member->birthday = $data->birthday;
}
if (isset($data->city)) {
    $member->city = $data->city;
}

// Update member
if ($member->update()) {
    echo json_encode(
        array('message' => 'Member Updated')
    );
} else {
    echo json_encode(
        array('message' => 'Member Not Updated')
    );
}
