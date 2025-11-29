<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}

require_once __DIR__ . '/../../config/connect.php';
$accID = $_SESSION['user']['accID'];
$eventID = $_POST['eventID'] ?? null;

if (!$eventID) {
    die("Invalid cancel request.");
}

// Delete booking from database
$stmt = $db->prepare("DELETE FROM eventDetails WHERE accID = ? AND eventID = ? LIMIT 1");
$stmt->execute([$accID, $eventID]);

// Remove from session array
if (($key = array_search($eventID, $_SESSION['user']['bookings'])) !== false) {
    unset($_SESSION['user']['bookings'][$key]);
}

// Re-index array to avoid gaps
$_SESSION['user']['bookings'] = array_values($_SESSION['user']['bookings']);

header("Location: ../User/account.php");
exit;
