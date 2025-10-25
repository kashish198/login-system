<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard">
    <h2>Welcome, <?php echo $_SESSION['username']; ?> 👋</h2>
    <p>Role: <?php echo $_SESSION['role']; ?></p>
    <a href="delete_user.php">Delete My Account</a> |
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
