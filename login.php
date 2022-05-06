<?php
  session_start();
?>

<?php
include "config.php";
?>

<?php
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    $missingUserName = '<p style = "text-align: center;"><strong>Please enter your Username!</strong></p>';
    $missingEmail = '<p style = "text-align: center;"><strong>Please enter your Email!</strong></p>';
    $invalidEmail = '<p style = "text-align: center;"><strong>Please enter a valid Email!</strong></p>';
    $errors = isset($error) ? $error : '';

    if (isset($_POST["submit"])) {
        if(!$username) {
            $error .= $missingUserName;
        }
        else {
            $username = filter_var($username, FILTER_SANITIZE_STRING);
        }
        if($errors) {
            $resultMessage = '<div style = "border: 1px solid black;">' .$errors. '</div>';
            echo $resultMessage;
        }
        else {
            $username = mysqli_real_escape_string($link, $username);
            $password = md5($password);

            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            if(is_array($row)){
                $_SESSION["id"] = $row['id'];
                $_SESSION["username"] = $row['username'];
                $resultMessage = '<div style = "border: 1px solid black;">Logged in Successfully to your profile</div>';
                echo $resultMessage;
            } else{
                 $resultMessage = '<div style = "border: 1px solid black;">ERROR: Unable to execute ' .$sql. '</div>'; 
                 echo $resultMessage; 
            }
            if(isset($_SESSION["id"])) {
                header("Location:courses.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/form-style.css" rel="stylesheet">
</head>
<body>
<div class="sign-form">
        <h2>Login</h2>

    <form  method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" class="form-control" type="text" name="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" class="form-control" type="password" name="password">
        </div>
        <div class="form-btn-div">
            <button type="submit"  name="submit">Login</button>
        </div>
    </form>

    Don't have an account? <a href="register.php">Register here.</a>
    </div>
</body>
</html>