<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}

require_once __DIR__ . '/../../config/connect.php';

$accID        = $_SESSION['user']['accID'];
$eventID      = $_POST['eventID']      ?? null;
$ticketAmount = $_POST['ticketAmount'] ?? null;

if (!$eventID || !$ticketAmount) {
    die("Invalid booking request.");
}

$check = $db->prepare("SELECT * FROM eventDetails WHERE accID = ? AND eventID = ?");
$check->execute([$accID, $eventID]);

if ($check->rowCount() > 0) {
    die("You already booked this event.<br><br>
         <a href=\"eventsRouter.php\">Back to Events</a>");
}

$stmt = $db->prepare("
    INSERT INTO eventDetails (accID, eventID, eventTicketAMT) 
    VALUES (?, ?, ?)
");

if ($stmt->execute([$accID, $eventID, $ticketAmount])) {

    // Save for My Account
    $_SESSION['user']['bookings'][] = $eventID;

    echo "<h2>Booking Confirmed!</h2>";
    echo "<p>You booked $ticketAmount ticket(s).</p>";

    echo '<br><a href="eventsRouter.php">Back to Events</a>';
} else {
    echo "<p style='color:red;'>Booking failed.</p>";
    echo '<br><a href="eventsRouter.php">Back</a>';
}
?>
