<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
include("./includes/db_conn.php");
if(isset($_GET['from'])){
    $from = $_GET['from'];
    $to = $_GET['to'];
} else {
    $from = "";
    $to = "";
}

$query = "SELECT expense.price, category.name, expense.item, expense.expense_date 
          FROM expense 
          JOIN category ON expense.category_id = category.id";
if($from != "") {
    $res = mysqli_query($conn, "SELECT expense.price, category.name, expense.item, expense.expense_date 
                                FROM expense, category 
                                WHERE expense.category_id = category.id 
                                AND expense.expense_date BETWEEN '$from' AND '$to'");
} else {
    $res = mysqli_query($conn, "SELECT expense.price, category.name, expense.item, expense.expense_date 
                                FROM expense, category 
                                WHERE expense.category_id = category.id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
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

        /* Form Styling */
        .filter-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .filter-form span {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .filter-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            text-align: center;
        }

        table th,
        table td {
            padding: 15px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #777;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: center;
            }

            table {
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

    <h2>Dashboard Report</h2>

    <div class="filter-form">
        <?php if ($from != "") { ?>
            <form method="GET">
                <span>From: <?php echo $from ?></span>
                <span>To: <?php echo $to ?></span>
            </form>
        <?php } else { ?>
            <span>ALL TIME EXPENSE</span><br><br>
        <?php } ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Item</th>
                <th>Expense Date</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $final_price = 0;
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $final_price += $row['price'];
            ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['item']; ?></td>
                        <td><?php echo $row['expense_date']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>No data found</td></tr>";
            }
            ?>
            <tr>
                <th>Total</th>
                <th colspan="3"><?php echo $final_price; ?></th>
            </tr>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2024 FinTrack | All rights reserved</p>
    </footer>
</body>

</html>
