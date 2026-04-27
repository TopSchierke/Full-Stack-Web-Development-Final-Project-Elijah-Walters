<?php
require_once "database.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $stmt = $database->query("
        SELECT items.*, stores.name AS store_name
        FROM items
        JOIN stores ON items.store_id = stores.id 
        ORDER BY stores.name, items.created_at DESC");
    

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($method === 'POST') {
    if (!isset($_GET['store_id'])) {
        http_response_code(400);
        echo json_encode(["error" => "store_id required"]);
        exit;
    }

    $store_id = $_GET['store_id'];
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name']) || trim($data['name']) === '') {
        http_response_code(400);
        echo json_encode(["error" => "Item name required"]);
        exit;
    }

    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;

    $stmt = $database->prepare("
        INSERT INTO items (store_id, name, quantity)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$store_id, $data['name'], $quantity]);

    echo json_encode(["success" => true, "id" => $database->lastInsertId()]);
    exit;
}

if ($method === 'PUT') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Item id required"]);
        exit;
    }

    $id = $_GET['id'];
    $data = json_decode(file_get_contents("php://input"), true);

    $name = $data['name'] ?? '';
    $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
    $checked = isset($data['checked']) ? (int)$data['checked'] : 0;

    $stmt = $database->prepare("
        UPDATE items 
        SET name = ?, quantity = ?, checked = ?
        WHERE id = ?
    ");

    $stmt->execute([$name, $quantity, $checked, $id]);

    echo json_encode(["success" => true]);
    exit;
}

if ($method === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Item id required"]);
        exit;
    }

    $id = $_GET['id'];

    $stmt = $database->prepare("DELETE FROM items WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Method not allowed"]);