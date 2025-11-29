<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}

require_once __DIR__ . '/../../controllers/AuthController.php';

$eventID = $_POST['eventID'] ?? null;

if (!$eventID) {
    die("Invalid cancel request.");
}

$auth = new AuthController();
$auth->cancelBooking($eventID);
