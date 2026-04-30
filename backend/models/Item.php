<?php

class Item {
    private $id, $store_id, $name, $quantity, $checked, $created_at;
    //constructor
    public function __construct($id, $store_id, $name, $quantity = 1, $checked = 0, $created_at = null) {
        $this->set_id($id);
        $this->set_store_id($store_id);
        $this->set_name($name);
        $this->set_quantity($quantity);
        $this->set_checked($checked);
        $this->created_at = $created_at;
    }
    //getters and setters
    public function set_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function set_store_id($store_id) {
        $this->store_id = $store_id;
    }

    public function get_store_id() {
        return $this->store_id;
    }

    public function set_name($name) {
        $this->name = trim($name);
    }

    public function get_name() {
        return $this->name;
    }

    public function set_quantity($quantity) {
        $quantity = (int)$quantity;

        if ($quantity <= 0) {//if an item has 0 or less quantity set it to 1
            $quantity = 1;
        }

        $this->quantity = $quantity;
    }

    public function get_quantity() {
        return $this->quantity;
    }

    public function set_checked($checked) {
        $this->checked = $checked;
    }

    public function get_checked() {
        return $this->checked;
    }

    public function get_created_at() {
        return $this->created_at;
    }
}