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
        
        if (empty($_POST["interest"])) {
            $interests = array();
        } else {
            $interests = $_POST["interest"];
        }
        $success = true;
        if (empty($_POST["email"]) || empty($_POST["username"]) || empty($_POST["fname"]) || empty($_POST['lname']) || empty($_POST["pwd"]) || empty($_POST["pwd_confirm"])) {
            $errorMsg .= "All fields are required!";
            $success = false;
        } else {
            $username = sanitize_input($_POST["username"]);
            $email = sanitize_input($_POST["email"]);
            $lname = sanitize_input($_POST["lname"]);
            $fname = sanitize_input($_POST["fname"]);
            if(strlen($username) > 45){
                $success = false;
                $errorMsg .="Username is too long! <br>";
            }
            if(strlen($email) > 45){
                $success = false;
                $errorMsg .= "email is too long! <br>";
            }
            if(strlen($fname) > 45){
                $success = false;
                $errorMsg .= "First name is too long! <br>";
            }
            if(strlen($lname) > 45){
                $success = false;
                $errorMsg .= "Last name is too long! <br>";
            }
            // Additional check to make sure e-mail address is well  -formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMsg .= "Invalid email format!";
                $success = false;
            }
            if ($_POST["pwd"] === $_POST["pwd_confirm"]) {
                $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
            } else if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
                $errorMsg .= "Password do not match!";
                $success = false;
            }
        }

        //Helper function that checks input for malicious or unwanted content. 
        function sanitize_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function saveMemberToDB() {
            global $successMsg, $errorMsg, $success, $username, $fname, $lname, $email, $pwd, $interests;

            //Create database connection
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg .= "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                //Prepare the statement:
                $stmt = $conn->prepare("INSERT INTO users (username, fname, lname, email, password) VALUES (?,?,?,?,?)");

                //Bind & Execute the query statement:
                $pwd_hashed = $pwd;
                $stmt->bind_param("sssss", $username, $fname, $lname, $email, $pwd_hashed);

                if (!$stmt->execute()) {
                    $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    $success = true;
                    $stmt = $conn->prepare("SELECT userID FROM users WHERE email =?");
                    $stmt->bind_param("s", $email);
                    if (!$stmt->execute()) {
                        $success = false;
                        $errorMsg .= $stmt->error;
                    } else {
                        $user = $stmt->get_result()->fetch_row()[0];
                        foreach ($interests as $interest) {
                            $stmt = $conn->prepare("INSERT INTO interest(userID,categoryID) VALUES(?,?)");
                            $stmt->bind_param("ii", $user, $interest);

                            if (!$stmt->execute()) {
                                $success = false;
                                $errorMsg .= $stmt->error;
                            } else {
                                $success = true;
                                $successMsg = "<h4>Thank you for signing up, " . $lname . " " . $fname . "</h4>";
                            }
                            $stmt->close();
                        }
                    }
                }
            }
            $conn->close();
            return $success;
        }
        ?>
        <main class="container">
            <?php
            if ($success) {
                if (saveMemberToDB()) {
                    include("resources/templates/successpage.php");
                } else {
                    include("resources/templates/errorpage.php");
                }
            } else {
                include("resources/templates/errorpage.php");
            }
            ?>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>