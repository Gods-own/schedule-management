<?php
session_start();
?>

<?php
include "config.php";
?>

<?php
   $course = isset($_POST["course"]) ? $_POST["course"] : '';
   $lectureDay = isset($_POST["lectureDay"]) ? $_POST["lectureDay"] : '';
   $lectureTime = isset($_POST["lectureTime"]) ? $_POST["lectureTime"] : '';


   $missingcourse = '<p style = "text-align: center;"><strong>Please enter course!</strong></p>';
   $missinglectureDay = '<p style = "text-align: center;"><strong>Please pick a day!</strong></p>';
   $missinglectureTime = '<p style = "text-align: center;"><strong>Please pick a time!</strong></p>';
   $error = '';

   if (isset($_POST["submit"])) {
        
    if(!$course) {
        $error .= $missingcourse;
    }
    else {
        $course = filter_var($course, FILTER_SANITIZE_STRING);
    }
    if(!$lectureDay) {
        $error .= $missinglectureDay;
    }
    else {
        $lectureDay = filter_var($lectureDay, FILTER_SANITIZE_STRING);
    }
    if(!$lectureTime) {
        $error .= $missinglectureTime;
    }
    else {
        $lectureTime = filter_var($lectureTime, FILTER_SANITIZE_STRING);
    }
    if($error) {
        $resultMessage = '<div style = "border: 1px solid black;">' .$error. '</div>';
        echo $resultMessage;
    }
    else {
        $course = mysqli_real_escape_string($link, $course);
        $lectureDay = mysqli_real_escape_string($link, $lectureDay);
        $lecture_time = mysqli_real_escape_string($link, $lectureTime);
        $lectureTime = $lecture_time. ':00';
        $userid = $_SESSION["id"];

        $sql = "INSERT INTO timetable (userid, courseid, Lecture_day, lecture_date) VALUES ('$userid', '$course', '$lectureDay', '$lectureTime')";

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
       header("Location:index.php");
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
<form method="post">
    <div class="form-group">
            <label for="course">Course Title</label>
            <select id="course" class="form-control" name="course" name="lectureDay">
                <option value="" selected>Course Title</option>
                <?php
                $userid = $_SESSION["id"];
                $sql = "SELECT * FROM courses WHERE userID = '$userid'";
                $result = mysqli_query($link, $sql);
                if($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo '<option value='.$row["id"].'>' .$row["code"]. ' - ' .$row["course"]. '</option>';
                        }
                    }
                    mysqli_free_result($result);
                }     
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="lectureDay">Lecture day</label>
            <select id="lectureDay" name="lectureDay">
                <option value="" selected>Day of the week</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="lectureTime">Lecture time</label>
            <input id="lectureTime" class="form-control" type="time" name="lectureTime">
        </div>
        <div class="form-btn-div">
            <button type="submit" name="submit">Add</button>
        </div>
    </form>
    </div>

    <div>
    <?php
        $userid = $_SESSION["id"];
        // $sql = "SELECT * FROM timetable WHERE userid = '$userid'";
        $sql = "SELECT courses.code, courses.course, timetable.Lecture_day, timetable.lecture_date FROM timetable INNER JOIN courses ON timetable.courseid=courses.id WHERE timetable.userid = '$userid'";
        $result = mysqli_query($link, $sql);
        if($result) {
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<div><p>'.$row["code"]. ' - ' .$row["course"]. '</p><p>'.$row["Lecture_day"].'</p><p>'.$row["lecture_date"].'</p></div>';
                }
            }
            mysqli_free_result($result);
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