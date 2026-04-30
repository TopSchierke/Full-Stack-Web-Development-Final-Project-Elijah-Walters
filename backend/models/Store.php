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