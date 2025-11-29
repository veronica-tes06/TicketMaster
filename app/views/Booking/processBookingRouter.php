<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}

require_once __DIR__ . '/../../controllers/AuthController.php';

$eventID      = $_POST['eventID'] ?? null;
$ticketAmount = $_POST['ticketAmount'] ?? null;

if (!$eventID || !$ticketAmount) {
    die("Invalid booking request.");
}

$auth = new AuthController();
$auth->createBooking($eventID, $ticketAmount);
