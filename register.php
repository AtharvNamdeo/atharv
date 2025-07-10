<?php
$success_message = ""; 

if (isset($_POST['submit'])) {
    include("./includes/db_conn.php");

    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];

    $sql = "INSERT INTO registered_users (username, password, gender, age) VALUES ('$username', '$password', '$gender', '$age')";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Registered Successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinTrack - Register</title>
    <link rel="stylesheet" href="./css/register.css">
</head>
<body>

    <div class="logo-container">
        <img src="./assets/logo.png" alt="FinTrack Logo">
        <h1>FinTrack</h1>
    </div>

    <div class="description-container">
        <h2>FinTrack - Your Smart Finance Partner</h2>
        <p>
            Take control of your finances with FinTrack, your ultimate personal finance analyser. 
            Track your expenses, manage budgets, and gain insightful data that helps you make smarter decisions. 
            FinTrack is designed to make finance management simple, intuitive, and effective for everyone.
        </p>
    </div>

    <div class="form-container">
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">Name</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" required>

            <input type="submit" name="submit" value="Register">
        </form>

        <!-- Hyperlink for existing members -->
        <div class="already-member-link">
            <br>
            <p>Already a member? <a href="login.php">Sign in</a></p>
        </div>
    </div>

</body>
</html>
