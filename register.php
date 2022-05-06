<?php
include "config.php";
?>

<?php
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : '';
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : '';
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';
    $confirmation = isset($_POST["confirmation"]) ? $_POST["confirmation"] : '';

    $missingFirstName = '<p style = "text-align: center;"><strong>Please enter your Firstname!</strong></p>';
    $missingLastName = '<p style = "text-align: center;"><strong>Please enter your Lastname!</strong></p>';
    $missingUserName = '<p style = "text-align: center;"><strong>Please enter your Username!</strong></p>';
    $missingEmail = '<p style = "text-align: center;"><strong>Please enter your Email!</strong></p>';
    $invalidEmail = '<p style = "text-align: center;"><strong>Please enter a valid Email!</strong></p>';
    $matchingpasswords = '<p style = "text-align: center;"><strong>Passwords must Match!</strong></p>';
    $usernameTaken = '<p style = "text-align: center;"><strong>Username already taken</strong></p>';
    $error = '';
    
    

    if (isset($_POST["submit"])) {
        
        if(!$firstname) {
            $error .= $missingFirstName;
        }
        else {
            $firstname = filter_var($firstname, FILTER_SANITIZE_STRING);
        }
        if(!$lastname) {
            $error .= $missingLastName;
        }
        else {
            $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);
        }
        if(!$username) {
            $error .= $missingUserName;
        }
        else {
            $username = filter_var($username, FILTER_SANITIZE_STRING);
        }
        $sql = "SELECT * FROM users WHERE username = '$username'";

        if(mysqli_num_rows(mysqli_query($link, $sql)) > 0) {
            $error .= $usernameTaken;
        }
        if(!$email) {
            $error .= $missingEmail;
        }
        else {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        }
        if(!filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $error .= $invalidEmail;
        }
        if($password != $confirmation) {
            $error .= $matchingpasswords;
        }
        if($error) {
            $resultMessage = '<div style = "border: 1px solid black;">' .$error. '</div>';
            echo $resultMessage;
        }
        else {
            $firstname = mysqli_real_escape_string($link, $firstname);
            $lastname = mysqli_real_escape_string($link, $lastname);
            $username = mysqli_real_escape_string($link, $username);
            $email = mysqli_real_escape_string($link, $email);
            $password = md5($password);

            $sql = "INSERT INTO users (firstname, lastname, username, email, password) VALUES ('$firstname', '$lastname', '$username', '$email', '$password')";

            if (mysqli_query($link, $sql)) {
                header("Location:login.php");
            }
            else {
                $resultMessage = '<div style = "border: 1px solid black;">ERROR: Unable to execute ' .$sql. '</div>'; 
                echo $resultMessage;
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

    <h2>Register</h2>

    <form method="post">
    <div class="form-group">
            <label for="firstname">Firstname</label>
            <input id="firstname" class="form-control" autofocus type="text" name="firstname">
        </div>
        <div class="form-group">
            <label for="lastname">Lastname</label>
            <input id="lastname" class="form-control" autofocus type="text" name="lastname">
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" class="form-control" autofocus type="text" name="username">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" class="form-control" type="email" name="email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" class="form-control" type="password" name="password">
        </div>
        <div class="form-group">
            <label for="confirm">Confirmation</label>
            <input id="confirm" class="form-control" type="password" name="confirmation">
        </div>
        <div class="form-btn-div">
            <button type="submit" name="submit">Register</button>
        </div>
    </form>

    Already have an account? <a href="login.php">Log In here.</a>
    </div>
</body>
</html>