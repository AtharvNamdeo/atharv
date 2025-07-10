<?php
session_start();
$error_message = ""; 

if (isset($_POST['login'])) {
    //creating db connection
    include("./includes/db_conn.php");

    //getting input data from user
    $username = $_POST['username'];
    $password = $_POST['password'];
   
    //checking credentials
    $login_query="SELECT * FROM registered_users WHERE username='$username' AND password='$password' ";
    $result_login_query=mysqli_query($conn,$login_query);

    if(mysqli_num_rows($result_login_query)==1){
        $_SESSION['loggedin']=true;
        header("Location: ./index.php");
    } else {
        $error_message="Invalid Credentials !";
    }
    
    //closing db connection
    mysqli_close($conn);
}
?>

//frontend

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinTrack - Login</title>
    <link rel="stylesheet" href="./css/login.css">
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
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">Name</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" name="login" value="Login">
        </form>

        <!-- New User link -->
        <div class="new-user-link">
            <br>
            <p>New User? <a href="register.php">Register here</a></p>
        </div>
    </div>

</body>
</html>
