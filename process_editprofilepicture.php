
<!doctype html>
<html lang="en">
<?php
// get current user details
session_start();
if(!isset($_SESSION['userID'])){
    header("Location: index.php");
}
$msg = $css_class = "";
$success = true;
$userID = $_SESSION['userID'];

//Create database connection
$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    if (isset($_POST['save-user']))
    {
      $profileImagename = $userID . '_.jpg';

      $target = "images/profilepics/" . $profileImagename;
      $img_tmpName = $_FILES['profileImage']['tmp_name'];
      
        if (move_uploaded_file($img_tmpName, $target))
        { //$sql to write into DB
          $stmt = $conn->prepare("UPDATE users SET profilePic = ? WHERE userID = ?");
          $stmt->bind_param("si", $profileImagename, $userID);
          $stmt->execute();

          echo $stmt->error;

          if (!$stmt->error) {
            $msg = "Upload success";
            $css_class = "alert-success";
            header("Location: profile.php?userID=". $userID);
          } 
          else {
            $_SESSION['error'] = $stmt->error;
              $msg = "Failed to upload";
              $css_class = "alert-danger";
              header("Location: profile.php?userID=". $userID);
          }
        }
        else{
            $msg = "Failed to upload file to Apache";
            $css_class = "alert-danger";
            header("Location: profile.php?userID=". $userID);
            
        }
    }
//            $sql = "UPDATE users SET profilePic = ('$profileImagename') WHERE userID = ('$userID')";
//            if (mysqli_query($conn, $sql))
//            {
//                $msg = "Upload Sucess";
//                $css_class = "alert-success";
//            }
//            else
//            {
//                $msg = "Failed to upload to database";
//                $css_class = "alert-danger";
//            }
//            }
//            else
//            {
//            $msg = "Failed to upload";
//            $css_class = "alert-danger";
//            $success=false;
//            }
//            echo "<pre>", print_r($_POST), "</pre>";
//            echo "<pre>", print_r($_FILES), "</pre>";
//            echo "<pre>", print_r($_FILES['profileImage']), "</pre>";
//
//            $img = (file_get_contents($img_tmpName));
//            $stmt->send_long_data(0, $img);

?>