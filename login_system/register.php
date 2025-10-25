<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Simple Password Validation
    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long.";
        header("Location: register.php");
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['register_success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Email already exists or registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register💖</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>🌸Create Account🌸</h2>

    <!-- Show messages -->
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
<?php
if (isset($_GET['deleted_user'])) {
    $deleted_user = htmlspecialchars($_GET['deleted_user']);
    echo "<p class='success'>User <strong>$deleted_user</strong> successfully deleted! 💖</p>";
}
?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder=" Email" required><br>

        <!-- Password input with strength indicator -->
        <input type="password" name="password" id="password" placeholder=" Password (min 8 chars)" required><br>
        <p id="strengthText"></p>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login Here</a></p>
</div>

<!-- 💫 Password Strength Script -->
<script>
const password = document.getElementById('password');
const strengthText = document.getElementById('strengthText');

password.addEventListener('input', () => {
    const val = password.value;
    let strength = '';

    if (val.length === 0) {
        strengthText.textContent = '';
    } 
    else if (val.length < 8) {
        strength = 'Weak 😢 (min 8 characters)';
        strengthText.style.color = '#e63946';
    } 
    else if (val.match(/[A-Za-z]/) && val.match(/[0-9]/) && val.match(/[^A-Za-z0-9]/)) {
        strength = 'Strong 💪';
        strengthText.style.color = '#2e8b57';
    } 
    else if (val.match(/[A-Za-z]/) && val.match(/[0-9]/)) {
        strength = 'Medium 😊';
        strengthText.style.color = '#ff9800';
    } 
    else {
        strength = 'Weak 😢';
        strengthText.style.color = '#e63946';
    }

    strengthText.textContent = strength;
});
</script>


</body>
</html>
