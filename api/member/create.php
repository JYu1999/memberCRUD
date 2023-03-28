<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
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

$member->account = $data->account;
$member->password = $data->password;
$member->first_name = $data->first_name;
$member->last_name = $data->last_name;
$member->gender = $data->gender;
$member->birthday = $data->birthday;
$member->city = $data->city;
$member->point_id = $data->point_id;

//$points = isset($_GET['points']) ? $_GET['points'] : die();
// Create member
if($member->create()) {
    echo json_encode(
        array('message' => 'Member Created')
    );
} else {
    echo json_encode(
        array('message' => 'Member Not Created')
    );
}
