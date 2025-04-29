<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'kivicare_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Set timezone
date_default_timezone_set('UTC');
?>