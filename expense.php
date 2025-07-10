<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
include("./includes/db_conn.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        nav {
            background-color: #333;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            bottom:5px;
            border-radius: 8px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 0px 0px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #4CAF50;
        }

        .add-expense-link {
            display: block;
            text-align: center;
            margin-bottom: 30px;
        }

        .add-expense-link a {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .add-expense-link a:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            font-size: 1.2rem;
        }

        table td {
            color: #555;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .action-links a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .action-links a:hover {
            color: #0056b3;
        }

        .no-data {
            text-align: center;
            font-size: 1.2rem;
            color: #ff5733;
            padding: 20px;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #777;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.9rem;
            }

            table th, table td {
                padding: 8px;
            }

            .add-expense-link a {
                font-size: 1rem;
                padding: 8px 15px;
            }

            nav {
                flex-direction: column;
                align-items: center;
            }

            nav a {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="expense.php">Expense</a>
        <a href="category.php">Category</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h2>Expense</h2>

    <div class="add-expense-link">
        <a href="./manage_expense.php">Add Expense</a>
    </div>

    <table>
        <tr>
            <th>S.No</th>
            <th>Category</th>
            <th>Item</th>
            <th>Price</th>
            <th>Details</th>
            <th>Expense Date</th>
            <th>Actions</th>
        </tr>

        <?php
        if(isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0){
            $id = $_GET['id'];
            mysqli_query($conn, "DELETE FROM expense WHERE id = $id");
        }
        $res = mysqli_query($conn, "SELECT expense.*, category.name FROM expense, category WHERE expense.category_id = category.id ORDER BY expense.expense_date ASC");
        if (mysqli_num_rows($res) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_assoc($res)) {
        ?>

            <tr>
                <td><?php echo $i; $i++; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['item']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['details']; ?></td>
                <td><?php echo $row['expense_date']; ?></td>
                <td class="action-links">
                    <a href="manage_expense.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="?type=delete&id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>

        <?php
            }
        } else {
            echo '<tr><td colspan="7" class="no-data">No data found</td></tr>';
        }
        ?>
    </table>

    <footer>
        <p>&copy; 2024 FinTrack | All rights reserved</p>
    </footer>

</body>
</html>
