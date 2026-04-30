<?php
require_once "database.php";
require_once "models/Item.php";
//headers for interacting with the frontend
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

//save the request type 
$action = $_SERVER["REQUEST_METHOD"];

//get post put and delete actions
if ($action === "GET") {
    echo json_encode(get_items());
    exit;
}


if ($action === "POST") {
    //gets raw request data and converts it to json
    $data = json_decode(file_get_contents("php://input"), true);

    
    if (isset($data["quantity"]) && isset($data[$quantity]) > 0) {
        $quantity = (int)$data["quantity"];
    } else {
        $quantity = 1;
    }

    //creates a new Item object 
    $item = new Item(
        null,
        $_GET["store_id"],
        $data["name"],
        $quantity
    );

    insert_item($item);//writes that item to the database

    echo json_encode(["success" => true]);//lets the frontend know we succeeded
    exit;
}

if ($action === "PUT") {//handles updates to existing items, checkboxes

    $data = json_decode(file_get_contents("php://input"), true);
    //creates a new item object with the ID from raw request 
    $item = new Item(
        $_GET["id"],
        $data["store_id"] ?? null,
        $data["name"] ?? "",
        $data["quantity"] ?? 1,
        $data["checked"] ?? 0
    );
    //uses the new item to update the old one
    update_item($item);

    echo json_encode(["success" => true]);
    exit;
}

if ($action === "DELETE") {

    delete_item($_GET["id"]);

    echo json_encode(["success" => true]);
    exit;
}
