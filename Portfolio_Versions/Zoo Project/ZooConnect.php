<?php
$servername = "localhost";
$username = "Farah";
$password = "Farah123";
$dbname = "zoo";

# Create connection to  database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


