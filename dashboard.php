
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Spend Smart Dashboard</title>
</head>
<body>

    <h1>Welcome to Spend Smart!</h1>

    <h2>
        Hello, <?php echo $_SESSION['user_name']; ?>
    </h2>

    <p>You have successfully logged in.</p>

    <a href="auth/logout.php">Logout</a>

</body>
</html>
