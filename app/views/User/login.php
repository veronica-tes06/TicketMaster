<?php
session_start();
require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$error = '';

//handle the post from the form below
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    //making an instance of the AuthController so we can use its login method
    $auth = new AuthController($db);
    $result = $auth->login($email, $password);

    //if the result from login is an error string display it
    $error = $result;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <input type="text" name="email" placeholder="Email" required>@gmail.com<br>
        <input type="password" name="password" placeholder="Password (8–20 chars)" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
