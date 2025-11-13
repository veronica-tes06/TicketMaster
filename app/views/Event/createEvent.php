<?php
session_start();
if ($_SESSION["user"]["accAdmin"] != 1) {
    header("Location: ../User/login.php");
    exit;
}

require_once __DIR__ . '/../../controllers/EventController.php';
$controller = new EventController();
$error = $controller->createEvent();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>

<h2>Create Event</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">

    <label>Event Name (8–25 letters):</label><br>
    <input type="text" name="name" required><br><br>

    <label>Location (6–30 letters):</label><br>
    <input type="text" name="location" required><br><br>

    <label>Date (DD/MM/YY):</label><br>
    <input type="text" name="date" required><br><br>

    <label>Time (HH:MM):</label><br>
    <input type="text" name="time" required><br><br>

    <label>Performer (4–15 letters):</label><br>
    <input type="text" name="performer" required><br><br>

    <label>Tickets (30–1000):</label><br>
    <input type="number" name="tickets" required><br><br>

    <button type="submit">Create Event</button>

</form>

</body>
</html>
