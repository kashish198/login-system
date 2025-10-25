<?php
session_start();
require 'db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    // Check if user exists by username or email
    $sql = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username_email, $username_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Delete user
            $del = "DELETE FROM users WHERE id=?";
            $stmt2 = $conn->prepare($del);
            $stmt2->bind_param("i", $user['id']);
            $stmt2->execute();

            $success = "User <strong>".$user['username']."</strong> successfully deleted! 💖";
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete User 💖</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>Delete Account ❌</h2>

    <?php
    if ($success) {
        echo "<p class='success'>$success</p>";
    }
    if ($error) {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="username_email" placeholder="👧 Username or Email" required><br>
        <input type="password" name="password" placeholder="🔒 Password" required><br>
        <button type="submit">Delete Account 💔</button>
    </form>

    <p><a href="login.php">Back to Login</a></p>
</div>
</body>
</html>
