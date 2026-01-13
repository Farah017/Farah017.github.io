<?php

$servername = "localhost";
$username = "Farah";
$password = "Farah123";
$dbname = "health";


# Create connection to database
$conn = new mysqli($servername, $username, $password, $dbname);


# Check if connection failed
if ($conn->connect_error) {
    die("Connection failed: " .$conn->connect_error);
}

?>