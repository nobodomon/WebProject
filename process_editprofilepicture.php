
            <?php
            // get current user details
            session_start();
            $msg = $css_class = "";
            $success = true;
            $userID = $_SESSION['userID'];
            
            //Create database connection
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            
                if (isset($_POST['save-user']))
                {
                  $profileImagename = $userID . '_.jpg';
                  
                  $target = "images/" . $profileImagename;

                  if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target))
                  { //$sql to write into DB
                    $sql = "UPDATE users SET profilePic = ('$profileImagename') WHERE userID = ('$userID')";
                  
                    if (mysqli_query($conn, $sql))
                    {
                        $msg = $sql;
                        $css_class = "alert-success";
                    }
                    else
                    {
                        $msg = "Failed to upload to database";
                        $css_class = "alert-danger";
                    }
                    
                  }
                  else
                  {
                    $msg = "Failed to upload";
                    $css_class = "alert-danger";
                    $success=false;
                  }
                }
                    
                    
                    
                  //echo "<pre>", print_r($_POST), "</pre>";
//                  echo "<pre>", print_r($_FILES), "</pre>";
                  //echo "<pre>", print_r($_FILES['profileImage']), "</pre>";
                    
//                  $img_tmpName = $_FILES['profileImage']['tmp_name'];
//                  $img = (file_get_contents($img_tmpName));
//                  
//                  $stmt = $conn->prepare("UPDATE users SET profilePic = ? WHERE userID = ?");
//                  $stmt->bind_param("bi", $img, $userID);
//                  $stmt->send_long_data(0, $img);
//                  $stmt->execute();
//                  echo $stmt->error;
//                  
//                  if (!$stmt->error) 
//                  {
//                    $msg = "Upload success";
//                    $css_class = "alert-success";
//                    header("Location: editProfile.php");
//                  } 
//                  else 
//                  {
//                
//                    $_SESSION['error'] = $stmt->error;
//                      $msg = "Failed to upload";
//                      $css_class = "alert-danger";
//                      header("Location: editprofilepicture.php");
//                  }
                  
            ?>