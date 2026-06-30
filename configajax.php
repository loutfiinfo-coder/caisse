<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

function connect() {
    return new PDO('mysql:host=localhost;dbname=caisse;charset=utf8mb4', 'root','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}

?>