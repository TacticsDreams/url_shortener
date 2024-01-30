<?php
/**
 *  This file contains and logs in to your database.
 *  It is ready to use for local environnements.
 */
function connectDatabase() {
    // Your database's coordonates.
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
