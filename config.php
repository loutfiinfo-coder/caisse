<?php

session_start();

header('Content-Type: text/html; charset=UTF-8');

    try
    {
        // On se connecte à MySQL
        $bdd = new PDO('mysql:host=mysql;dbname=gest;charset=utf8mb4', 'caisse','Caisse@123',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
        // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
    }

?>
