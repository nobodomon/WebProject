<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <?php
        $email = $errorMsg = $interest = "";
        $interest1 = ($_POST["int1"]);
        $interest2 = ($_POST["int2"]);
        $interest3 = ($_POST["int3"]);
        $interest = $interest1  .', '.$interest2 .', '.$interest3;
        $username = sanitize_input($_POST["username"]);
        $lname = sanitize_input($_POST["lname"]);
        $fname = sanitize_input($_POST["fname"]);
        $success = true;
        if (empty($_POST["email"]) || empty($_POST["username"])) 
        {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        } 
        else 
        {
            $email = sanitize_input($_POST["email"]);
            // Additional check to make sure e-mail address is well  -formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMsg = $errorMsg . "Invalid email format.<br>";
                $success = false;
            }
        }
        //check for if pass or cpass is empty
        if (empty($_POST["pwd"]))
        { 
            $errorMsg .= "Password is required <br>";
            $success = false; 
        }
        // Check if password and confirm passwords are the same
        else if ($_POST["pwd"] === $_POST["pwd_confirm"]) 
        {
            $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        }
        
        else if ($_POST["pwd"] != $_POST["pwd_confirm"])
        {
            $errorMsg .= "Password do not match.<br>";
            $success = false;
        }
        
        if ($success) 
        {
            //adding values into global variable for saving
            $fname = $fname;
            $lname = $lname;
            $email = $email;
            $pwd = $pwd;
            $errorMsg = $errorMsg;
            $username = $username;
            $success = $success;
            $interest = $interest;
            
            // calling function to save into mysql
            saveMemberToDB();
            
            $h3 = "<h3>Your Registration successful!</h3>";
            $h4 = "<h4>Thank you for signing up, ". $lname . " " . $fname ."</h4>";
            $btn = "<a href='login.php'><button class = 'btn btn-success'>Log-in </button></a><br>";
            echo"$interest";
        }
            
        else 
        {
            $h3 = "<h3>Oops!";
            $h4 = "<h4>The following input errors were detected:</h4>";
            $errors = "<p>" . $errorMsg . "</p>";
            $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
        }

        //Helper function that checks input for malicious or unwanted content. 
        function sanitize_input($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        function saveMemberToDB()
        {
            global $errorMsg, $success, $username, $fname, $lname, $email, $pwd, $interest;
            
            //Create database connection
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            
            // Check connection
            if($conn->connect_error)
            {
                $errorMsg = "Connection failed: " . $conn -> connect_error;
                $success = false;
                
                $h3 = "<h3>Oops!";
                $h4 = "<h4>The following input errors were detected:</h4>";
                $errors = "<p>" . $errorMsg . "</p>";
                $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
            }
            else
            {
                //Prepare the statement:
                $stmt = $conn -> prepare("INSERT INTO users (username, fname, lname, email, password, interest) VALUES (?,?,?,?,?,?)");
                
                //Bind & Execute the query statement:
                $pwd_hashed = $pwd;
                $stmt-> bind_param("ssssss", $username, $fname, $lname, $email, $pwd_hashed, $interest);
                
                if(!$stmt-> execute())
                {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                    $h3 = "<h3>Oops!</h3>";
                    $h4 = "<h4>The following input errors were detected:</h4>";
                    $errors = "<p>" . $errorMsg . "</p>";
                    $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
                }
                else
                {
                    
                    $h3 = "<h3>Your Registration successful!</h3>";
                    $h4 = "<h4>Thank you for signing up, ". $lname . " " . $fname ."</h4>";
                    $btn = "<a href='login.php'><button class = 'btn btn-success'>Log-in </button></a><br>";
                }
                $stmt->close();
                
            }
            $conn->close();
            return $success;
        }
        
        
        ?>
        <main class="container">
            <hr>
                <?php
                    echo $h3;
                    echo $h4;
                    if (empty($errors)){
                    }else{
                        echo $errors;
                    }
                    echo $btn;
                ?>
            <br>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>