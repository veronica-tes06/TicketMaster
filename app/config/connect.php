<?php
try {
    $dsn = 'mysql:host=localhost;port=3307;dbname=ticketmaster';
    $username = 'root';
    $password = '';
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
