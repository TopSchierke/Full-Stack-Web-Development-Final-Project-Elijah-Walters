<?php
//had trouble making a stockuser for this even though I didnt struggle for the last project
//So I used root for this one
$data_source_name = 'mysql:host=localhost;dbname=shopping';
$username = 'root';
$password = '';
$database = new PDO($data_source_name, $username, $password);