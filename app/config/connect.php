<?php
try {
    $dsn = 'mysql:host=localhost;dbname=ticketmaster';
    $username = 'root';
    $password = 'admin';
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
