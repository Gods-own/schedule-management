<?php
  session_start();
?>

<?php
include "config.php";
?>

<?php
   $title = isset($_POST["title"]) ? $_POST["title"] : '';
   $document = isset($_FILES["document"]) ? $_FILES["document"] : '';
   $courseid = isset($_POST["course"]) ? $_POST["course"] : '';

   $missingTitle = '<p style = "text-align: center;"><strong>Please enter document title!</strong></p>';
   $missingcourse = '<p style = "text-align: center;"><strong>Please select a course!</strong></p>';

   $missingdocument = '<p style = "text-align: center;"><strong>Please upload a file!</strong></p>';
   $documentExists = '<p style = "text-align: center;"><strong>Sorry document already exists!</strong></p>';
   $wrongFormat = '<p style = "text-align: center;"><strong>Sorry you can only upload pdf and text files!</strong></p>';
   $fileTooLarge = '<p style = "text-align: center;"><strong>You can only upload files smaller 5mb</strong></p>';

   $allowedFormat = array("pdf"=>"application/pdf", "text"=>"text/plain");
 
   $error = '';

   if (isset($_POST["submit"])) {

       $permamentdestination = "documents/" .$_FILES["document"]["name"];
       
       if(!$title) {
           $error .= $missingTitle;
       }
       else {
            $title = filter_var($title, FILTER_SANITIZE_STRING);
       }
       if(file_exists($permamentdestination)) {
           $error .= $documentExists;
       }
       if($_FILES["document"]["error"] == 4) {
        $error .= $missingdocument;
        }
        if($_FILES["document"]["size"] > 5 * 1024 * 1024) {
            $error .= $fileTooLarge;
        }
        if(!in_array($_FILES["document"]["type"], $allowedFormat)) {
            $error .= $wrongFormat;
        }    
       if(!$courseid) {
        $error .= $missingcourse;
    }
    else {
        $courseid = filter_var($courseid, FILTER_SANITIZE_STRING);
    }
       if($error) {
           $resultMessage = '<div style = "border: 1px solid black;">' .$error. '</div>';
           echo $resultMessage;
       }
       else {
           $title = mysqli_real_escape_string($link, $title);
           $courseid = mysqli_real_escape_string($link, $courseid);
           $userID = $_SESSION["id"];
           $filename = $_FILES["document"]["name"];

           if (move_uploaded_file($_FILES["document"]["tmp_name"], $permamentdestination)) {
            $resultMessage = '<div style = "border: 1px solid black;"> Document uploaded successfully</div>';
            echo $resultMessage;
           }
           else {
                $resultMessage = '<div style = "border: 1px solid black;"> Unable to upload document please try again later</div>';
                echo $resultMessage;
           }

           $sql = "INSERT INTO `documents` (userID, course_ID, fileTitle) VALUES ('$userID', '$courseid', '$filename')";

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

<h2>Add Documents</h2>

<form method="post" enctype="multipart/form-data">
<div class="form-group">
        <label for="title">Title</label>
        <input id="title" class="form-control" autofocus type="text" name="title">
    </div>  
<div class="form-group">
        <label for="document">Document</label>
        <input id="document" class="form-control" autofocus type="file" name="document">
    </div>
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
    <div class="form-btn-div">
        <button type="submit" name="submit">Add</button>
    </div>
</form>
</div>

<div>
    <?php
        $userID = $_SESSION["id"];
        // $sql = "SELECT * FROM documents WHERE userID = '$userID'";
        $sql = "SELECT courses.code, courses.course, document.fileTitle FROM documents INNER JOIN courses ON documents.courseID=courses.id WHERE documents.userID = '$userID'";
        $result = mysqli_query($link, $sql);
        if($result) {
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<div><p>'.$row["fileTitle"].' for ' .$row["code"]. ' - ' .$row["course"]. '</p></div>';
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