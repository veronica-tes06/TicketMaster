<?php


// User MUST be logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../User/login.php");
    exit;
}



// User session data
$accID = $_SESSION['user']['accID'];
$accEmail = $_SESSION['user']['accEmail'];
$accAdmin = $_SESSION['user']['accAdmin'];
$accBookings = $_SESSION['user']['bookings'] ?? []; //or since if they register their bookings will be empty
?>
<!DOCTYPE html>
<html>

<head>
    <title>Events</title>
</head>

<body>

    <h2>Upcoming Events</h2>
    <p>Welcome, <?= htmlspecialchars($accEmail) ?></p>

    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Date</th>
            <th>Time</th>
            <th>View</th>
        </tr>

        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['eventName']) ?></td>
                <td><?= htmlspecialchars($event['eventLocation']) ?></td>
                <td><?= htmlspecialchars($event['eventDate']) ?></td>
                <td><?= htmlspecialchars($event['eventTime']) ?></td>

                <!-- View Event functionality completed -->
                <td>
                    <form action="../Event/viewEventRouter.php" method="POST" style="display:inline;">
                        <input type="hidden" name="eventID" value="<?= $event['eventID'] ?>">
                        <button type="submit">View Event</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>

    <a href="../User/account.php">My Account</a><br>
    <a href="../User/logout.php">Logout</a>

</body>

</html>