<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require event model
require_once __DIR__ . '/../../models/Event.php';
use App\Models\Event;

// Get event info for display
$event = Event::find($_POST['eventID'] ?? null);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
</head>
<body>

<h2>Booking Confirmed!</h2>

<?php if ($event): ?>
    <p><strong>Event:</strong> <?= htmlspecialchars($event->getName()) ?></p>
<?php endif; ?>

<p>You booked <?= htmlspecialchars($_POST['ticketAmount'] ?? 0) ?> ticket(s).</p>

<br>
<a href="../Event/eventsRouter.php">Back to Events</a>

</body>
</html>
