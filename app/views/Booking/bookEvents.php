<?php

require_once __DIR__ . '/../../models/Event.php';
use App\Models\Event;

$event = Event::find($eventID);
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Event</title>
</head>
<body>

<h2>Book Tickets for <?= htmlspecialchars($event->getName()); ?></h2>

<p><strong>Location:</strong> <?= htmlspecialchars($event->getLocation()); ?></p>
<p><strong>Date:</strong> <?= htmlspecialchars($event->getDate()); ?></p>
<p><strong>Time:</strong> <?= htmlspecialchars($event->getTime()); ?></p>

<form action="processBookingRouter.php" method="POST">
    <input type="hidden" name="eventID" value="<?= $event->getId(); ?>">

    <label>Ticket Amount:</label><br>
    <input type="number" name="ticketAmount" 
           min="1" max="<?= $event->getMaxTickets(); ?>" required>

    <br><br>
    <button type="submit">Confirm Booking</button>
</form>

<br>
<a href="/EventController/listEvents">Back to Events</a>

</body>
</html>
