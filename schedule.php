<?php
  session_start();
?>

<?php
include "config.php";
?>

<?php
   $activity = isset($_POST["activity"]) ? $_POST["activity"] : '';
   $deadlineDate = isset($_POST["deadlineDate"]) ? $_POST["deadlineDate"] : '';
   $deadlineTime = isset($_POST["deadlineTime"]) ? $_POST["deadlineTime"] : '';

   $missingActivity = '<p style = "text-align: center;"><strong>Please enter Activity!</strong></p>';
   $missingDeadlineDate = '<p style = "text-align: center;"><strong>Please enter Deadline!</strong></p>';
   $missingDeadlineTime = '<p style = "text-align: center;"><strong>Please enter Deadline!</strong></p>';
 
   $error = '';

   if (isset($_POST["submit"])) {
       
       if(!$activity) {
           $error .= $missingActivity;
       }
       else {
          $activity = filter_var($activity, FILTER_SANITIZE_STRING);
       }
       if(!$deadlineDate) {
           $error .= $missingDeadlineDate;
       }
       else {
          $deadlineDate= filter_var($deadlineDate, FILTER_SANITIZE_STRING);
       }
       if(!$deadlineTime) {
        $error .= $missingDeadlineTime;
    }
    else {
       $deadlineTime= filter_var($deadlineTime, FILTER_SANITIZE_STRING);
    }
       if($error) {
           $resultMessage = '<div style = "border: 1px solid black;">' .$error. '</div>';
           echo $resultMessage;
       }
       else {
           $activity = mysqli_real_escape_string($link, $activity);
           $deadlineDate = mysqli_real_escape_string($link, $deadlineDate);
           $deadlineTime = mysqli_real_escape_string($link, $deadlineTime);
           $userid = $_SESSION["id"];

           $sql = "INSERT INTO schedules (userid, activity, deadlineDate, deadlineTime) VALUES ('$userid', '$activity', '$deadlineDate', '$deadlineTime')";

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

<h2>Add To do</h2>

<form method="post">
<div class="form-group">
        <label for="activity">Activity</label>
        <input id="activity" class="form-control" autofocus type="text" name="activity">
    </div>
<div class="form-group">
        <label for="deadlineDate">Deadline Date</label>
        <input id="deadlineDate" class="form-control" autofocus type="date" name="deadlineDate">
    </div>
    <div class="form-group">
        <label for="deadlineTime">Deadline Time</label>
        <input id="deadlineTime" class="form-control" autofocus type="time" name="deadlineTime">
    </div>
    <div class="form-btn-div">
        <button type="submit" name="submit">Add</button>
    </div>
</form>
</div>

<div>
    <?php
        $userid = $_SESSION["id"];
        $sql = "SELECT * FROM schedules WHERE userid = '$userid'";
        $result = mysqli_query($link, $sql);
        if($result) {
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<div><p>'.$row["activity"].'</p><p>Deadline: '.$row["deadlineDate"]. ' by ' .$row["deadlineTime"].'</p></div>';
                }
            }
            mysqli_free_result($result);
        }
        else {
            echo "<p>mySQL returned an empty result set</p>";
        } 
        ?>
        </div>

</body>
</html>