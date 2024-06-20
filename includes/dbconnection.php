<?php
$host = '192.168.0.12';
$user = 'root';
$password = 'P@ssw0rd';
$database = 'fypdb';

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>