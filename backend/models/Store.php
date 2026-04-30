<?php

class Store {
    private $id, $name, $created_at;
    
    public function __construct($id, $name, $created_at = null) {//constructor
        $this->set_id($id);
        $this->set_name($name);
        $this->created_at = $created_at;
    }
    //getters and setters
    public function set_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function set_name($name) {
        $this->name = trim($name);
    }

    public function get_name() {
        return $this->name;
    }

    public function get_created_at() {
        return $this->created_at;
    }
}

function get_stores() {//fetches all stores
    global $database;//connects to database

    $query = "SELECT * FROM stores ORDER BY created_at DESC";//gets all stores newest to oldest
    $statement = $database->prepare($query);//prepares and executes query
    $statement->execute();

    $stores = $statement->fetchAll(PDO::FETCH_ASSOC);//fetches results
    $statement->closeCursor();//close query

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

function delete_store($id) {
    global $database;

    $query = "DELETE FROM stores WHERE id = :id";//finds store matching id and deletes it
    $statement = $database->prepare($query);//prepares, executes, and closes query
    $statement->bindValue(":id", $id);
    $statement->execute();
    $statement->closeCursor();
}

function insert_store($store) {//posts store
    global $database;

    $query = "INSERT INTO stores (name) VALUES (:name)";//creates prepares and executes query
    $statement = $database->prepare($query);
    $statement->bindValue(":name", $store->get_name());
    $statement->execute();
    $statement->closeCursor();
}