<?php
session_start();

// User MUST be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$accID       = $_SESSION['user']['accID'];
$accEmail    = $_SESSION['user']['accEmail'];
$accAdmin    = $_SESSION['user']['accAdmin'];
$accBookings = $_SESSION['user']['bookings'] ?? [];

require_once __DIR__ . '/../../config/connect.php';
global $db;
require_once __DIR__ . '/../../models/Event.php';

use App\Models\Event;
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
</head>
<body>

<h2>My Account</h2>

<p><strong>Email:</strong> <?= htmlspecialchars($accEmail) ?></p>
<p><strong>Account ID:</strong> <?= htmlspecialchars($accID) ?></p>
<p><strong>Account Type:</strong> <?= $accAdmin ? 'Admin' : 'Buyer' ?></p>

<h3>My Bookings:</h3>

<?php if (empty($accBookings)): ?>
    <p>You have no bookings yet.</p>
<?php else: ?>
<ul>
    <?php foreach ($accBookings as $eventID): 
        $event = Event::find($eventID);
        if (!$event) continue;

        // Convert date string from DB (dd-mm-yyyy)
        $eventDate = DateTime::createFromFormat('d-m-Y', $event->getDate());
        $now = new DateTime();

        $isFuture = $eventDate >= $now;
    ?>
        <li>
            <?= htmlspecialchars($event->getName()) ?> –
            <?= htmlspecialchars($event->getDate()) ?> –
            <?= htmlspecialchars($event->getTime()) ?>

        <?php if ($isFuture): ?>
            <form action="../Event/bookingDelete.php" method="POST" style="display:inline;" 
                onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                <input type="hidden" name="eventID" value="<?= $eventID ?>">
                <button type="submit">Cancel</button>
            </form>
        <?php endif; ?>

        </li>
    <?php endforeach; ?>
</ul>

<?php endif; ?>

<br>
<a href="../Event/eventsRouter.php">Back to Events</a><br>
<a href="logout.php">Logout</a>

</body>
</html>
