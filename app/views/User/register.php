<?php
session_start();
session_unset();
session_destroy();
session_start();

require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$error = '';

//handle the post from the form below
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    //making an instance of the AuthController so we can use its register method
    // AuthController is namespaced under App\Controllers; use fully-qualified name here
    $auth = new AuthController();
    $result = $auth->register($email, $password);

    //if the result from rehister is an error string display it
    $error = $result;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="register.php">
        <input type="text" name="email" placeholder="Email" required>@gmail.com<br>
        <input type="password" name="password" placeholder="Password (8–20 chars)" required><br>
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>