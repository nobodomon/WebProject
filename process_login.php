<html>
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <?php
        $email = $errorMsg = "";
        $success = true;
        if (empty($_POST["email"])) {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        } else {
            $email = sanitize_input($_POST["email"]);
            // Check if password and confirm passwords are the same
            $pwd = $_POST["pwd"];
            // Additional check to make sure e-mail address is well  -formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMsg = $errorMsg . "Invalid email format.<br>";
                $success = false;
            }
        }
        if ($success) {
            //$h3 = "<h3>Your Registration successful!</h3>";
            //$h4 = "<h4>Thank you for signing up, ". $_POST["lname"]. " " . $_POST["fname"] ."</h4>";
            //$btn = "<a href='#'><button class = 'btn btn-success'>Log-in </button></a><br>";
            retrieveMemberFromDB();

        } else {
            $h3 = "<h3>Oops!";
            $h4 = "<h4>The following input errors were detected:</h4>";
            $errors = "<p>" . $errorMsg . "</p>";
            $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
        }

        //Helper function that checks input for malicious or unwanted content. 
        function sanitize_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        function retrieveMemberFromDB(){
            global$email,$pwd, $lname, $fname, $h3, $h4, $btn, $errorMsg, $success;
            
            //Create database connection
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            
            // Check connection
            if($conn->connect_error){
                $errorMsg = "Connection failed: " . $conn -> connect_error;
                $success = false;
                
                $h3 = "<h3>Oops!";
                $h4 = "<h4>The following input errors were detected:</h4>";
                $errors = "<p>" . $errorMsg . "</p>";
                $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
            }else{
                //Prepare the statement:
                $stmt = $conn -> prepare("SELECT * FROM world_of_pets_members WHERE email =?");
                //Bind & Execute the query statement:
                $stmt->bind_param("s",$email);
                if(!$stmt-> execute()){
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                    $h3 = "<h3>Oops!";
                    $h4 = "<h4>The following errors were detected:</h4>";
                    $errorMsg = "<p>" . $errorMsg . "</p>";
                    $btn = "<a href='login.php'><button class='btn btn-warning'>Return to login </button></a><br>";
                }else{
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    if(password_verify($pwd,$user["password"])){
                        $lname = $user['lname'];
                        $fname = $user['fname'];
                        $h3 = "<h3>Login successful!</h3>";
                        $h4 = "<h4>Welcome back," . $lname . " " . $fname . ".</h4>";
                        $btn = "<a href='index.php'><button class = 'btn btn-success'>Return to Home</button></a><br>";
                    }else{
                        $h3 = "<h3>Oops!</h3>";
                        $h4 = "<h4>The following errors were detected:</h4>";
                        $errorMsg = "<p> Email not found or password doesn't match...</p>";
                        $btn = "<a href ='login.php'><button class = 'btn btn-warning'>Return to login</button></a><br>";
                        $success = false;
                    }
                    
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
                    if (empty($errorMsg)){
                    }else{
                        echo $errorMsg;
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