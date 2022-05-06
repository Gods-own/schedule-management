<?php
session_start();
?>

<?php
include "config.php";
?>

<?php
   $course = isset($_POST["course"]) ? $_POST["course"] :  '';
   $code = isset($_POST["code"]) ? $_POST["code"] :  '';

   $missingcourse = '<p style = "text-align: center;"><strong>Please enter course title!</strong></p>';
   $courseExists =  '<p style = "text-align: center;"><strong>Course exisits!</strong></p>';
   $missingcode = '<p style = "text-align: center;"><strong>Please enter course code!</strong></p>';
   $codeExists =  '<p style = "text-align: center;"><strong>Course Code exisits!</strong></p>';
   $error = '';

   if (isset($_POST["submit"])) {
        
    if(!$course) {
        $error .= $missingcourse;
    }
    else {
        $course = filter_var($course, FILTER_SANITIZE_STRING);
    }
    if(!$code) {
        $error .= $missingcode;
    }
    else {
        $code = filter_var($code, FILTER_SANITIZE_STRING);
    }
    $userID = $_SESSION["id"];
    $sql = "SELECT * FROM courses WHERE code = '$code' AND userID = '$userID'";

    if(mysqli_num_rows(mysqli_query($link, $sql)) > 0) {
        $error .= $codeExists;
    }
    $userID = $_SESSION["id"];
    $sql = "SELECT * FROM courses WHERE course = '$course' AND userID = '$userID'";

    if(mysqli_num_rows(mysqli_query($link, $sql)) > 0) {
        $error .= $courseExists;
    }
    if($error) {
        $resultMessage = '<div style = "border: 1px solid black;">' .$error. '</div>';
        echo $resultMessage;
    }
    else {
        $code = mysqli_real_escape_string($link, $code);
        $course = mysqli_real_escape_string($link, $course);
        $userID = $_SESSION["id"];
    
        $sql = "INSERT INTO courses (userID, code, course) VALUES ('$userID', '$code', '$course')";

        if (mysqli_query($link, $sql)) {
            $resultMessage = '<div style = "border: 1px solid black;">Successfully added</div>'; 
            echo $resultMessage;
        }
        else {
            $resultMessage = '<div style = "border: 1px solid black;">ERROR: Unable to execute ' .$sql. '</div>'; 
            echo $resultMessage;
        }
    }
}
?>

<?php
   if (isset($_POST["next"])) {
       header("Location:timetable.php");
   }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>

    <h2>Add courses</h2>

    <form method="post">
    <div class="form-group">
            <label for="code">Course Code</label>
            <input id="code" class="form-control" autofocus type="text" name="code">
        </div>
    <div class="form-group">
            <label for="course">Course Title</label>
            <input id="course" class="form-control" autofocus type="text" name="course">
        </div>
        <div class="form-btn-div">
            <button type="submit" name="submit">Add</button>
        </div>
    </form>
    </div>

    <div>
    <?php
        $userID = $_SESSION["id"];
        $sql = "SELECT * FROM courses WHERE userID = '$userID'";
        $result = mysqli_query($link, $sql);
        if($result) {
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<div><p>'.$row["code"]. ' - ' .$row["course"]. '</p></div>';
                }
            }
            mysqli_free_result($result);
        }
        else {
            echo "<p>mySQL returned an empty result set</p>";
        } 
        ?>
    </div>
    <form method="post">
    <div>
        <button type="submit" name="next">Next</button>
    </div>
    </form>
</body>
</html>