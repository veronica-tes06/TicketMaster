<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}

require_once __DIR__ . '/../../config/connect.php';

$accID = $_SESSION['user']['accID'];
$eventID = $_POST['eventID'] ?? null;
$ticketAmount = $_POST['ticketAmount'] ?? null;

if (!$eventID || !$ticketAmount) {
    die("Invalid booking request.");
}

$stmt = $db->prepare("INSERT INTO eventDetails (accID, eventID, eventTicketAMT) VALUES (?, ?, ?)");

if ($stmt->execute([$accID, $eventID, $ticketAmount])) {
    $_SESSION['user']['bookings'][] = $eventID;

    echo "<h3>Booking Confirmed!</h3>";
    echo "<p>You booked $ticketAmount ticket(s).</p>";
    echo '<br><a href="eventsRouter.php">Back to Events</a>';
} else {
    echo "<p style='color:red;'>Booking failed.</p>";
    echo '<br><a href="eventsRouter.php">Back</a>';
}
?>
