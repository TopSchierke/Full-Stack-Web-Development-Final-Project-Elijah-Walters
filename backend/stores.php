<?php
require_once "database.php";
require_once "models/Store.php";
//headers for interacting with the frontend
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

//save the request type 
$action = $_SERVER['REQUEST_METHOD'];

//get post and delete actions
if ($action === 'GET') {
    echo json_encode(get_stores());//converts the php array to json then sends the result to react
    exit;
}

if ($action === 'POST') {//takess the raw request and converts it to json
    $data = json_decode(file_get_contents("php://input"), true);

    $store = new Store(null, $data['name']);//create a new store object, database assigns the ID
    insert_store($store);//writes it to the database

    echo json_encode(["success" => true]);//sends success response to frontend
    exit;
}

if ($action === 'DELETE') {

    delete_store($_GET['id']);

    echo json_encode(["success" => true]);
    exit;
}
