<?php

// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "cosmetics_db";  // Replace with your database name

// Create a connection
$dsn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($dsn->connect_error) {
    die("Connection failed: " . $dsn->connect_error);
}

// Optional: Use this to set the character set to UTF-8
$dsn->set_charset("utf8");
?>
