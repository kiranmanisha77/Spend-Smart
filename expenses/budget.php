
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../includes/db.php";

$message = "";

if (isset($_POST['save_budget'])) {

    $user_id = $_SESSION['user_id'];
    $pocket_money = $_POST['pocket_money'];
    $monthly_budget = $_POST['monthly_budget'];

    $month = date('n');
    $year = date('Y');

    // Check whether budget already exists
    $check_query = "SELECT id FROM budgets
                    WHERE user_id = ? AND month = ? AND year = ?";

    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $month, $year);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        // Update existing budget
        $update_query = "UPDATE budgets
                         SET pocket_money = ?, monthly_budget = ?
                         WHERE user_id = ? AND month = ? AND year = ?";

        $stmt = mysqli_prepare($conn, $update_query);

        mysqli_stmt_bind_param(
            $stmt,
            "ddiii",
            $pocket_money,
            $monthly_budget,
            $user_id,
            $month,
            $year
        );

        mysqli_stmt_execute($stmt);

    } else {

        // Insert new budget
        $insert_query = "INSERT INTO budgets
                         (user_id, pocket_money, monthly_budget, month, year)
                         VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $insert_query);

        mysqli_stmt_bind_param(
            $stmt,
            "iddii",
            $user_id,
            $pocket_money,
            $monthly_budget,
            $month,
            $year
        );

        mysqli_stmt_execute($stmt);
    }

    $message = "Budget saved successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Budget - Spend Smart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4 mx-auto" style="max-width: 500px;">

        <h3 class="text-center mb-4">Set Monthly Budget</h3>

        <?php if ($message != "") { ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Pocket Money (₹)</label>
                <input type="number" name="pocket_money"
                       class="form-control" min="0" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Monthly Budget (₹)</label>
                <input type="number" name="monthly_budget"
                       class="form-control" min="0.01" step="0.01" required>
            </div>

            <button type="submit" name="save_budget"
                    class="btn btn-primary w-100">
                Save Budget
            </button>

        </form>

        <a href="../dashboard.php" class="btn btn-secondary mt-3">
            Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>
