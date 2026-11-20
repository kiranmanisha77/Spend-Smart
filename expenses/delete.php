<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../includes/db.php";

$user_id = $_SESSION['user_id'];

if (isset($_POST['delete_expense'])) {

    $id = $_POST['id'];

    $query = "DELETE FROM expenses
              WHERE id = ? AND user_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

header("Location: view.php");
exit();
?>