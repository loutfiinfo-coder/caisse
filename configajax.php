<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

function connect() {
    return new PDO('mysql:host=mysql;dbname=gest;charset=utf8mb4', 'caisse','Caisse@123', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}

?>
