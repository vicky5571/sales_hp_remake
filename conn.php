<?php
// Start the session if not already started
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Database connection parameters
$databaseHost = 'localhost';
$databaseName = 'pos_system_konter_hp';
$databaseUsername = 'root';
$databasePassword = '';

// Create a connection
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set default timezone
date_default_timezone_set('Asia/Jakarta');
?>
