
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../includes/db.php";

$message = "";

if (isset($_POST['add_expense'])) {

    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $expense_date = $_POST['expense_date'];

    $query = "INSERT INTO expenses
              (user_id, category, amount, description, expense_date)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "isdss",
        $user_id,
        $category,
        $amount,
        $description,
        $expense_date
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = "Expense added successfully!";
    } else {
        $message = "Error adding expense.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Expense - Spend Smart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4 mx-auto" style="max-width: 550px;">

        <h3 class="text-center mb-4">Add New Expense</h3>

        <?php if ($message != "") { ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <!-- Category -->
            <div class="mb-3">

                <label class="form-label">Category</label>

                <select name="category" class="form-select" required>

                    <option value="">Select Category</option>
                    <option value="Food">Food</option>
                    <option value="Hostel">Hostel</option>
                    <option value="Shopping">Shopping</option>
                    <option value="Travel">Travel</option>
                    <option value="Education">Education</option>
                    <option value="Others">Others</option>

                </select>

            </div>


            <!-- Amount -->
            <div class="mb-3">

                <label class="form-label">Amount (₹)</label>

                <input type="number"
                       name="amount"
                       class="form-control"
                       min="0.01"
                       step="0.01"
                       required>

            </div>


            <!-- Description -->
            <div class="mb-3">

                <label class="form-label">Description</label>

                <textarea name="description"
                          class="form-control"
                          rows="3"
                          placeholder="Enter expense details"></textarea>

            </div>


            <!-- Date -->
            <div class="mb-3">

                <label class="form-label">Expense Date</label>

                <input type="date"
                       name="expense_date"
                       class="form-control"
                       value="<?php echo date('Y-m-d'); ?>"
                       required>

            </div>


            <button type="submit"
                    name="add_expense"
                    class="btn btn-primary w-100">

                Add Expense

            </button>

        </form>

        <a href="../dashboard.php" class="btn btn-secondary mt-3">
            Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>
