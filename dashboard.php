


<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include "includes/db.php";

// Logged-in user
$user_id = $_SESSION['user_id'];

// Current month and year
$month = date('n');
$year = date('Y');

// Default values
$pocket_money = 0;
$monthly_budget = 0;
$total_expenses = 0;

// Fetch budget details
$budget_query = "SELECT pocket_money, monthly_budget
                 FROM budgets
                 WHERE user_id = ? AND month = ? AND year = ?
                 ORDER BY id DESC LIMIT 1";

$stmt = mysqli_prepare($conn, $budget_query);

if (!$stmt) {
    die("Budget query error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "iii", $user_id, $month, $year);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($budget = mysqli_fetch_assoc($result)) {
    $pocket_money = (float)$budget['pocket_money'];
    $monthly_budget = (float)$budget['monthly_budget'];
}

mysqli_stmt_close($stmt);


// Fetch total expenses
$expense_query = "SELECT SUM(amount) AS total
                  FROM expenses
                  WHERE user_id = ?
                  AND MONTH(expense_date) = ?
                  AND YEAR(expense_date) = ?";

$stmt = mysqli_prepare($conn, $expense_query);

if (!$stmt) {
    die("Expense query error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "iii", $user_id, $month, $year);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$total_expenses = (float)($row['total'] ?? 0);

mysqli_stmt_close($stmt);


// Calculate remaining balance
$remaining_balance = $pocket_money - $total_expenses;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Spend Smart Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            Spend Smart
        </a>

        <a href="auth/logout.php" class="btn btn-danger btn-sm">
            Logout
        </a>

    </div>
</nav>


<!-- Dashboard -->
<div class="container mt-4">

    <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>

    <p class="text-muted">
        Manage your expenses smartly.
    </p>


    <!-- Summary Cards -->
    <div class="row mt-4">

        <!-- Pocket Money -->
        <div class="col-md-3 mb-3">

            <div class="card bg-primary text-white">
                <div class="card-body">

                    <h5>Pocket Money</h5>

                    <h3>
                        ₹<?php echo number_format($pocket_money, 2); ?>
                    </h3>

                </div>
            </div>

        </div>


        <!-- Monthly Budget -->
        <div class="col-md-3 mb-3">

            <div class="card bg-success text-white">
                <div class="card-body">

                    <h5>Monthly Budget</h5>

                    <h3>
                        ₹<?php echo number_format($monthly_budget, 2); ?>
                    </h3>

                </div>
            </div>

        </div>


        <!-- Total Expenses -->
        <div class="col-md-3 mb-3">

            <div class="card bg-warning text-dark">
                <div class="card-body">

                    <h5>Total Expenses</h5>

                    <h3>
                        ₹<?php echo number_format($total_expenses, 2); ?>
                    </h3>

                </div>
            </div>

        </div>


        <!-- Remaining Balance -->
        <div class="col-md-3 mb-3">

            <div class="card bg-danger text-white">
                <div class="card-body">

                    <h5>Remaining Balance</h5>

                    <h3>
                        ₹<?php echo number_format($remaining_balance, 2); ?>
                    </h3>

                </div>
            </div>

        </div>

    </div>


    <!-- Set Budget Button -->
    <div class="mt-4">

        <a href="expenses/budget.php" class="btn btn-primary">
            Set Monthly Budget
        </a>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
