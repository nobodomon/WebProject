<!doctype html>
<html lang="en
      </body>
            <?php
            $msg = $css_class = "";
            $success = true;
            
            //Create database connection
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            
                if (isset($_POST['save-user']))
                {
                  //echo "<pre>", print_r($_POST), "</pre>";
                  //echo "<pre>", print_r($_FILES), "</pre>";
                  //echo "<pre>", print_r($_FILES['profileImage']), "</pre>";
                  $profileImagename = time() . '_' . $_FILES['profileImage']['name'];

                  $target = "images/" . $profileImagename;

                  if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target))
                  { //$sql to write into DB
                    $sql = "INSERT INTO users (profilePic) VALUES ('$profileImagename')";
                    if (mysqli_query($conn, $sql))
                    {
                        $msg = "Image upload and saved to database";
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
            ?>
      </body>
</html>