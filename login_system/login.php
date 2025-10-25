<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>🌸Login🌸</h2>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['register_success'])) {
        echo "<p class='success'>".$_SESSION['register_success']."</p>";
        unset($_SESSION['register_success']);
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder=" Username" required><br>
        <input type="password" name="password" placeholder=" Password" required><br>
        <button type="submit">Login</button>
    </form>

    <p>Don’t have an account? <a href="register.php">Register Here</a></p>
    <p><a href="reset_password.php">Forgot Password?</a></p>
</div>
</body>
</html>
