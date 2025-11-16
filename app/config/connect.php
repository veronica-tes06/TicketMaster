<?php
// Allow overriding DB connection via environment variables for tests/CI
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3307';
$dbName = getenv('DB_NAME') ?: 'ticketmaster';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';

try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $dbHost, $dbPort, $dbName);
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
