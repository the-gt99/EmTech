<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
$host = "localhost";
$username = "u148682720_test";
$password = "egtXOIZTaE1y";
$db = "u148682720_test";
$connection = new mysqli($host, $username, $password, $db);
$connection->query(
        "INSERT INTO Blanks (user_id, VK_name, VK_lastname, OS, blank_name, blank_lastname, email) VALUES (123, NULL, NULL, NULL, NULL, NULL, NULL)"
        );
?>