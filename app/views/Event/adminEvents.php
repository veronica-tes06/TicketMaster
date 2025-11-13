<?php
// Only admin can access
if (!isset($_SESSION['user']) || $_SESSION['user']['accAdmin'] != 1) {
    header("Location: ../User/login.php");
    exit;
}

// $events is supplied by EventController
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Events</title>
</head>
<body>

<h2>All Events (Admin)</h2>

<p>Welcome, <?= htmlspecialchars($_SESSION['user']['accEmail']); ?></p>

<a href="eventCreate.php">Create Event</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Location</th>
        <th>Date</th>
        <th>Time</th>
        <th>Max</th>
        <th>Min</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['eventID']) ?></td>
            <td><?= htmlspecialchars($event['eventName']) ?></td>
            <td><?= htmlspecialchars($event['eventLocation']) ?></td>
            <td><?= htmlspecialchars($event['eventDate']) ?></td>
            <td><?= htmlspecialchars($event['eventTime']) ?></td>
            <td><?= htmlspecialchars($event['eventTicketMaxAMT']) ?></td>
            <td><?= htmlspecialchars($event['eventTicketMinAMT']) ?></td>

             <!-- busted ? -->
            <td>
                <a href="eventEdit.php?id=<?= $event['eventID'] ?>">Edit</a> |
                <a href="eventDelete.php?id=<?= $event['eventID'] ?>"
                   onclick="return confirm('Delete this event?');">
                   Delete
                </a>
            </td>
             <!-- busted -->
        </tr>
    <?php endforeach; ?>
</table>
<br>
    <a href="../User/logout.php">Logout</a>
</body>
</html>
