<?php
session_start();
include("./includes/db_conn.php");

$msg = "";
$category = "";
$label = "Add";

if ((isset($_GET['id']) && $_GET['id'] > 0)) {
    $label = "Edit";
    $id = $_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM category WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    $category = $row['name'];
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $type = "add";
    $sub_sql = "";

    if ((isset($_GET['id']) && $_GET['id'] > 0)) {
        $type = "edit";
        $sub_sql = "AND id != $id";
    }

    $res = mysqli_query($conn, "SELECT * FROM category WHERE name = '$name' $sub_sql");
    if (mysqli_num_rows($res) > 0) {
        $msg = "Category already exists";
    } else {
        if ($type == "edit") {
            mysqli_query($conn, "UPDATE category SET name = '$name' WHERE id = $id");
            header("Location: ./category.php");
        } else {
            mysqli_query($conn, "INSERT INTO category (name) VALUES ('$name')");
            header("Location: ./category.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Category</title>
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
        }

        form table {
            width: 100%;
            margin-bottom: 20px;
        }

        form td {
            padding: 10px;
            font-size: 1rem;
        }

        form input[type="text"] {
            width: 100%;
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

        .msg {
            text-align: center;
            font-size: 1.1rem;
            color: #ff5733;
            margin-top: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #007BFF;
            font-size: 1.1rem;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #0056b3;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #777;
        }

        @media (max-width: 768px) {
            form input[type="submit"] {
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

    <h2><?php echo $label; ?> Category</h2>

    <div class="back-link">
        <a href="./category.php">Back to Category List</a>
    </div>

    <form method="POST">
        <table>
            <tr>
                <td>Category Name</td>
                <td><input type="text" name="name" required value="<?php echo $category; ?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="submit" value="Submit"></td>
            </tr>
        </table>
    </form>

    <div class="msg">
        <?php echo $msg; ?>
    </div>

    <footer>
        <p>&copy; 2024 FinTrack | All rights reserved</p>
    </footer>
</body>

</html>
