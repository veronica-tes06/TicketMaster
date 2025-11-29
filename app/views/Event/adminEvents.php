<?php

//only admin can access check
if (!isset($_SESSION['user']) || $_SESSION['user']['accAdmin'] != 1) {
    header("Location: ../User/login.php");
    exit;
}

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
            <th>View</th>
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

                
                 <td>
                    <form action="../Event/viewEventRouter.php" method="POST" style="display:inline;">
                        <input type="hidden" name="eventID" value="<?= $event['eventID'] ?>">
                        <button type="submit">View Event</button>
                    </form>
                </td>
                <!-- TODO: Edit and Delete Event functionality to be completed -->
                <td>
                    <a href="eventEdit.php?id=<?= $event['eventID'] ?>">Edit</a> |
                    <a href="eventDelete.php?id=<?= $event['eventID'] ?>" onclick="return confirm('Delete this event?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="../User/account.php">My Account</a><br>
    <a href="../User/logout.php">Logout</a>
</body>

</html>