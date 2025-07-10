<?php 
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
include("./includes/db_conn.php");


function dashBoardExpense($type) {
    global $conn;
    $from = "";
    $to = "";
    $subsql = "";

    if ($type == "today") {
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $subsql = "WHERE expense_date = '$from'";
    } elseif ($type == "yesterday") {
        $from = date('Y-m-d', strtotime('-1 day'));
        $to = $from;
        $subsql = "WHERE expense_date = '$from'";
    } elseif ($type == "week") {
        $from = date('Y-m-d', strtotime('-1 week'));
        $to = date('Y-m-d');
        $subsql = "WHERE expense_date BETWEEN '$from' AND '$to'";
    } elseif ($type == "month") {
        $from = date('Y-m-d', strtotime('-1 month'));
        $to = date('Y-m-d');
        $subsql = "WHERE expense_date BETWEEN '$from' AND '$to'";
    } elseif ($type == "year") {
        $from = date('Y-m-d', strtotime('-1 year'));
        $to = date('Y-m-d');
        $subsql = "WHERE expense_date BETWEEN '$from' AND '$to'";
    } elseif ($type == "all_time") {
        $subsql = "";
    }

    $sql = "SELECT SUM(price) AS price FROM expense $subsql";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    $links = "";
    if ($type == "all_time") {
        $links = "&nbsp;<a href='dashboard_report.php' class='details-link' >View Details</a>";
    } else {
        $links = "&nbsp;<a href='dashboard_report.php?from=$from&to=$to' class='details-link'>View Details</a>";
    }
    return $links;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            color: #333;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin-bottom: 40px;
        }

        header img {
            width: 100px;
            height: auto;
        }

        header h1 {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-left: 15px;
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

        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 50px;
        }

        .expense-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        .expense-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .expense-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .expense-box .amount {
            font-size: 1.8rem;
            color: #4CAF50;
            font-weight: bold;
        }

        .details-link {
            text-decoration: none;
            bottom:5px;
            color: #fff;
            background-color: #007BFF;
            padding: 6px 10px;
            border-radius: 5px;
            font-weight: bold;
            position: absolute;
            bottom: 25px;
            right: 10px;
            transition: background-color 0.3s ease;
            font-size: 0.9rem;
        }

        .details-link:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #777;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }

            .expense-box {
                font-size: 1.2rem;
                flex-direction: column;
                align-items: flex-start;
            }

            .expense-box .amount {
                font-size: 1.5rem;
            }

            nav {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <nav>
        <a href="./index.php">Dashboard</a>
        <a href="./category.php">Category</a>
        <a href="./expense.php">Expense</a>
        <a href="./reports.php">Report</a>
        <a href="./logout.php">Logout</a>
    </nav>

    <header>
        <img src="./assets/logo.png" alt="FinTrack Logo">
        <h1>FinTrack</h1>
    </header>

    <div>
        <div class="expense-boxes">
            <div class="expense-box">
                <div>Today's Expense</div>
                <span class="amount"><?php echo dashBoardExpense('today'); ?></span>
            </div>

            <div class="expense-box">
                <div>Yesterday's Expense</div>
                <span class="amount"><?php echo dashBoardExpense('yesterday'); ?></span>
            </div>

            <div class="expense-box">
                <div>This Week's Expense</div>
                <span class="amount"><?php echo dashBoardExpense('week'); ?></span>
            </div>

            <div class="expense-box">
                <div>This Month's Expense</div>
                <span class="amount"><?php echo dashBoardExpense('month'); ?></span>
            </div>

            <div class="expense-box">
                <div>This Year's Expense</div>
                <span class="amount"><?php echo dashBoardExpense('year'); ?></span>
            </div>

            <div class="expense-box">
                <div>All-Time Expense</div>
                <span class="amount"><?php echo dashBoardExpense('all_time'); ?></span>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 FinTrack | All rights reserved</p>
    </footer>

</body>
</html>
