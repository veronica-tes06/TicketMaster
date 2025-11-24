<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//get the admin flag from the session
$accAdmin = $_SESSION['user']['accAdmin'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($event->getName()) ?> - Event Details</title>
</head>

<body>

    <h2>Event Details</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($event->getName()) ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($event->getLocation()) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($event->getDate()) ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($event->getTime()) ?></p>
    <p><strong>Performer:</strong> <?= htmlspecialchars($event->getPerformer() ?: 'N/A') ?></p>
    <p><strong>Max Tickets:</strong> <?= htmlspecialchars($event->getMaxTickets()) ?></p>
    <p><strong>Min Tickets:</strong> <?= htmlspecialchars($event->getMinTickets()) ?></p>

    <?php if ($accAdmin == 0): ?>
        <form action="bookEvent.php" method="POST">
            <input type="hidden" name="eventID" value="<?= $event->getId() ?>">
            <button type="submit">Book Ticket</button>
        </form>
    <?php endif; ?>

    <?php if ($accAdmin == 1): ?>
        <form action="eventEdit.php" method="POST">
            <input type="hidden" name="eventID" value="<?= $event->getId() ?>">
            <button type="submit">Edit Event</button>
        </form>
        <form action="eventDelete.php" method="POST">
            <input type="hidden" name="eventID" value="<?= $event->getId() ?>">
            <button type="submit">Delete Event</button>
        </form>
    <?php endif; ?>

    <br><br>
    <a href="../User/logout.php">Logout</a>
</body>

</html>