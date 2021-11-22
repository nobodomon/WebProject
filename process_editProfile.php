<html>
    <?php
        include "head.inc.php"
    ?>
    <body>
        <?php
            include "nav.inc.php";
            // get current user details
            $currentUserDetails = getUserFromID($_SESSION['userID']);
        ?>
        <?php
            $errorMsg = "";
            $success = true;
            $newUsername = "";
            
//          check if any fields are empty
            if (empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["username"]) || empty($_POST["biography"])) {
                $errorMsg .= "All fields are required.<br>";
                $success = false;
            }
            $userResultsBasedOnUsername = getUserFromUsername($_POST["username"]);
            if ($currentUserDetails["username"] == $_POST["username"]) {
                // same username, no chnage in username
                $success = true;
            } else {
                // if it is in $userResultsBasedOnUsername, dont allow as someone else taken the username
                if ($userResultsBasedOnUsername["username"] == $_POST["username"]) {
                    $errorMsg .= "The username has been taken.<br>";
                    $success = false;
                }
                // check if in db if not then proceed to update
                else {
                    $newUsername = $_POST["username"];
                    $success = true;
                }
            }

            if ($success) {
                $config = parse_ini_file('../../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
                if($conn->connect_error){
                    $errorMsg = "Connection failed: " . $conn -> connect_error;
                    $success = false;
                }else{
                    $stmt = $conn -> prepare("UPDATE users SET username=?, fname=?, lname=?, biography=? WHERE userID = ?");

                    //Bind & Execute the query statement:
                    $stmt-> bind_param("ssssi", $_POST["username"], $_POST["fname"], $_POST["lname"], $_POST["biography"], $_SESSION['userID']);
                    if(!$stmt->execute()){
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $success = false;
                    }else{
                        $success = true;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
        
        ?>
        <main class="container">
            <hr>
                <?php
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