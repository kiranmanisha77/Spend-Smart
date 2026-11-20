
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../includes/db.php";

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM expenses
          WHERE user_id = ?
          ORDER BY expense_date DESC, id DESC";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Expenses - Spend Smart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>My Expenses</h2>

        <a href="add.php" class="btn btn-primary">
            + Add Expense
        </a>

    </div>


    <div class="card shadow">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        if (mysqli_num_rows($result) > 0) {

                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>

                             <tr>
                                  <td><?php echo $row['id']; ?></td>

                                      <td><?php echo $row['category']; ?></td>

                                         <td>₹<?php echo number_format($row['amount'], 2); ?></td>

                                        <td><?php echo $row['description']; ?></td>

                                    <td><?php echo $row['expense_date']; ?></td>

                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>"
                                 class="btn btn-warning btn-sm">
                                 Edit
                                  </a>
                            </td>

                        </tr>
                                            
                                

                        <?php
                            }

                        } else {
                        ?>

                            <tr>
                                <td colspan="5" class="text-center">
                                    No expenses found.
                                </td>
                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <a href="../dashboard.php" class="btn btn-secondary mt-3">
        Back to Dashboard
    </a>

</div>

</body>
</html>
