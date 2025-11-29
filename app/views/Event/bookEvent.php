<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}

if (!isset($_POST['eventID'])) {
    die("No event selected.");
}

$eventID = $_POST['eventID'];

require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../models/Event.php';

use App\Models\Event;

$event = Event::find($eventID);

if (!$event) {
    die("Event not found.");
}

// Prevent double-booking
if (in_array($eventID, $_SESSION['user']['bookings'])) {
    die("You have already booked this event.<br><br><a href=\"eventsRouter.php\">Back to Events</a>");
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Event</title>
</head>
<body>

<h2>Book Tickets for <?= htmlspecialchars($event->getName()) ?></h2>

<p><strong>Location:</strong> <?= htmlspecialchars($event->getLocation()) ?></p>
<p><strong>Date:</strong> <?= htmlspecialchars($event->getDate()) ?></p>
<p><strong>Time:</strong> <?= htmlspecialchars($event->getTime()) ?></p>

<form action="processBooking.php" method="POST">
    <input type="hidden" name="eventID" value="<?= $eventID ?>">

    <label>How many tickets?</label><br>
    <input type="number" name="ticketAmount" min="1" max="<?= $event->getMaxTickets() ?>" required><br><br>

    <button type="submit">Confirm Booking</button>
</form>

<br>
<a href="eventsRouter.php">Back to Events</a><br>

</body>
</html>
