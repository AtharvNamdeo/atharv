<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
include("./includes/db_conn.php");

$selected_category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$from_date = isset($_GET['from']) ? $_GET['from'] : '';
$to_date = isset($_GET['to']) ? $_GET['to'] : '';

function getCategory($category_id = '')
{
    global $conn;
    $res = mysqli_query($conn, "SELECT * FROM category ORDER BY name ASC");

    $html = '<select name="category_id" onchange="changeCategory()" id="category_id">';
    $html .= '<option value="">Select Category</option>';
    while ($row = mysqli_fetch_assoc($res)) {
        if ($category_id > 0 && $category_id == $row['id']) {
            $html .= '<option value="' . $row['id'] . '" selected>' . $row['name'] . '</option>';
        } else {
            $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
    }
    $html .= '</select>';
    return $html;
}

$query = "SELECT SUM(expense.price) AS price, category.name 
          FROM expense 
          JOIN category ON expense.category_id = category.id 
          WHERE 1";

if ($selected_category_id > 0) {
    $query .= " AND expense.category_id = $selected_category_id";
}

if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND expense.expense_date BETWEEN '$from_date' AND '$to_date'";
} elseif (!empty($from_date)) {
    $query .= " AND expense.expense_date >= '$from_date'";
} elseif (!empty($to_date)) {
    $query .= " AND expense.expense_date <= '$to_date'";
}

$query .= " GROUP BY expense.category_id";

$res = mysqli_query($conn, $query);
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

        
        form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
        }

        form input[type="date"],
        form select {
            width: 200px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .reset-link {
            display: inline-block;
            font-size: 1.1rem;
            margin-left: 10px;
        }

        .reset-link a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .reset-link a:hover {
            color: #0056b3;
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

            form {
                flex-direction: column;
                align-items: flex-start;
            }

            form input[type="date"],
            form select {
                width: 100%;
            }

            form input[type="submit"] {
                width: 100%;
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

    <h2>Report</h2>

    <div>
        <form method="GET">
            From <input type="date" name="from" value="<?php echo htmlspecialchars($from_date); ?>">
            To <input type="date" name="to" value="<?php echo htmlspecialchars($to_date); ?>">
            <?php echo getCategory($selected_category_id); ?>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Submit">
           
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
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
                        <td><?php echo $row['price']; ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='2'>No data found</td></tr>";
            }
            ?>
            <tr>
                <th>Total</th>
                <th><?php echo $final_price; ?></th>
            </tr>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2024 FinTrack | All rights reserved</p>
    </footer>

    <script>
        function changeCategory() {
            document.getElementById('filter_form').submit();
        }
    </script>
</body>

</html>
