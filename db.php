<?php

function connectDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "url_shortener";

    try {
        $conn = new PDO("mysql:host=localhost;dbname=".$dbname, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}
?>