
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../includes/db.php";

$user_id = $_SESSION['user_id'];
$message = "";

// Get expense ID from URL
if (!isset($_GET['id'])) {
    header("Location: view.php");
    exit();
}

$id = $_GET['id'];

// Update expense when form is submitted
if (isset($_POST['update_expense'])) {

    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $expense_date = $_POST['expense_date'];

    $query = "UPDATE expenses
              SET category = ?, amount = ?, description = ?, expense_date = ?
              WHERE id = ? AND user_id = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "sdssii",
        $category,
        $amount,
        $description,
        $expense_date,
        $id,
        $user_id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: view.php");
        exit();
    } else {
        $message = "Error updating expense.";
    }
}

// Fetch existing expense details
$query = "SELECT * FROM expenses
          WHERE id = ? AND user_id = ?";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$expense = mysqli_fetch_assoc($result);

if (!$expense) {
    die("Expense not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Expense - Spend Smart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4 mx-auto" style="max-width: 550px;">

        <h3 class="text-center mb-4">Edit Expense</h3>

        <?php if ($message != "") { ?>
            <div class="alert alert-danger">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Category</label>

                <select name="category" class="form-select" required>

                    <option value="Food" <?php if ($expense['category'] == 'Food') echo 'selected'; ?>>Food</option>

                    <option value="Hostel" <?php if ($expense['category'] == 'Hostel') echo 'selected'; ?>>Hostel</option>

                    <option value="Shopping" <?php if ($expense['category'] == 'Shopping') echo 'selected'; ?>>Shopping</option>

                    <option value="Travel" <?php if ($expense['category'] == 'Travel') echo 'selected'; ?>>Travel</option>

                    <option value="Education" <?php if ($expense['category'] == 'Education') echo 'selected'; ?>>Education</option>

                    <option value="Others" <?php if ($expense['category'] == 'Others') echo 'selected'; ?>>Others</option>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Amount (₹)</label>

                <input type="number"
                       name="amount"
                       class="form-control"
                       min="0.01"
                       step="0.01"
                       value="<?php echo $expense['amount']; ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>

                <textarea name="description"
                          class="form-control"
                          rows="3"><?php echo $expense['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Expense Date</label>

                <input type="date"
                       name="expense_date"
                       class="form-control"
                       value="<?php echo $expense['expense_date']; ?>"
                       required>
            </div>

            <button type="submit"
                    name="update_expense"
                    class="btn btn-primary w-100">

                Update Expense

            </button>

        </form>

        <a href="view.php" class="btn btn-secondary mt-3">
            Back to Expenses
        </a>

    </div>

</div>

</body>
</html>
