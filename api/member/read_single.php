<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Member.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog post object
$member = new Member($db);

// Get ID
$member->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get post
$member->read_single();

// Make JSON
print_r(json_encode($member->points));
