<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Member.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate member object
$member = new Member($db);

// Get member ID
$member->id = isset($_GET['id']) ? $_GET['id'] : die();

// Delete member and member points records
if ($member->delete()) {
    echo json_encode(
        array('message' => 'Member and member points records deleted')
    );
} else {
    echo json_encode(
        array('message' => 'Member and member points records not deleted')
    );
}