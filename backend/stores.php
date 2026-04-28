<?php
require_once "database.php";
//headers for react
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
//options check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}
//store request method
$method = $_SERVER['REQUEST_METHOD'];
//GET POST and DELETE 
if ($method === 'GET') {
    $stmt = $database->query("SELECT * FROM stores ORDER BY created_at DESC");
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);//sql to php array
    echo json_encode($stores);//encodes php array as json for react
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name']) || trim($data['name']) === '') {//checks that in the input the name exists and if the input is empty
        http_response_code(400);
        echo json_encode(["error" => "Store name required and cannot already exist"]);
        exit;
    }

    $stmt = $database->prepare("INSERT INTO stores (name) VALUES (?)");//protects against injection
    $stmt->execute([$data['name']]);

    echo json_encode(["success" => true, "id" => $database->lastInsertId()]);
    exit;
}

if ($method === 'DELETE') {
    if (!isset($_GET['id'])) {//This shouldnt happen but just in case
        http_response_code(400);
        echo json_encode(["error" => "Store not found"]);
        exit;
    }

    $id = $_GET['id'];

    $stmt = $database->prepare("DELETE FROM stores WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);