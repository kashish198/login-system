<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];

    // Check password length
    if (strlen($new_password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long.";
        header("Location: reset_password.php");
        exit();
    }

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Hash new password and update
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = "UPDATE users SET password=? WHERE email=?";
        $stmt2 = $conn->prepare($update);
        $stmt2->bind_param("ss", $hashed_password, $email);
        $stmt2->execute();

        $_SESSION['success'] = "Password successfully reset! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Email not found!";
        header("Location: reset_password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>🔑Reset Password🔑</h2>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <input type="password" name="new_password" id="new_password" placeholder=" New Password" required><br>
        <p id="strengthText"></p>
        <button type="submit">Reset Password</button>
    </form>

    <p><a href="login.php">Back to Login</a></p>
</div>

<script>
const password = document.getElementById('new_password');
const strengthText = document.getElementById('strengthText');

password.addEventListener('input', () => {
    const val = password.value;
    let strength = '';

    if (val.length === 0) {
        strengthText.textContent = '';
    } else if (val.length < 8) {
        strength = 'Weak 😢 (min 8 characters)';
        strengthText.style.color = '#e63946';
    } else if (val.match(/[A-Za-z]/) && val.match(/[0-9]/) && val.match(/[^A-Za-z0-9]/)) {
        strength = 'Strong 💪';
        strengthText.style.color = '#2e8b57';
    } else if (val.match(/[A-Za-z]/) && val.match(/[0-9]/)) {
        strength = 'Medium 😊';
        strengthText.style.color = '#ff9800';
    } else {
        strength = 'Weak 😢';
        strengthText.style.color = '#e63946';
    }

    strengthText.textContent = strength;
});
</script>
</body>
</html>
