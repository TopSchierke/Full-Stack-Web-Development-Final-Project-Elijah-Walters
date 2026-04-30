<?php

require_once "../database.php";
require_once "../models/Store.php";


function get_stores() {//fetches all stores
    global $database;//connects to database

    $query = "SELECT * FROM stores ORDER BY created_at DESC";//gets all stores newest to oldest
    $statement = $database->prepare($query);//prepares and executes query
    $statement->execute();

    $stores = $statement->fetchAll(PDO::FETCH_ASSOC);//fetches results

    $store_array = [];//create array

    foreach ($stores as $store) {//stores stores in the array
        $store_array[] = [
            "id" => $store['id'],
            "name" => $store['name'],
            "created_at" => $store['created_at']
        ];
    }

    return $store_array;//returns data to be sent to frontend
}


function insert_store($store) {//posts store
    global $database;

    $query = "INSERT INTO stores (name) VALUES (:name)";//creates prepares and executes query
    $statement = $database->prepare($query);
    $statement->bindValue(":name", $store->get_name());
    $statement->execute();
}

function delete_store($id) {
    global $database;

    $query = "DELETE FROM stores WHERE id = :id";//finds store matching id and deletes it
    $statement = $database->prepare($query);//prepares, executes, and closes query
    $statement->bindValue(":id", $id);
    $statement->execute();
}


