<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        ?>
        <?php
        $email = $errorMsg = $successMsg = "";
        $userID;
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

        //Helper function that checks input for malicious or unwanted content. 
        function sanitize_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function retrieveMemberFromDB() {
            global $userID, $email, $pwd, $lname, $fname, $h3, $h4, $btn, $errorMsg, $successMsg, $success;

            //Create database connection
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                //Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM users WHERE email =?");
                //Bind & Execute the query statement:
                $stmt->bind_param("s", $email);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    if (password_verify($pwd, $user["password"])) {
                        $lname = $user['lname'];
                        $fname = $user['fname'];
                        $userID = $user['userID'];
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        $_SESSION["userID"] = $user['userID'];
                        $_SESSION["fname"] = $user['fname'];
                        $_SESSION["lname"] = $user['lname'];
                        $successMsg = "You have successfully logged in!";
                        $success = true;
                    } else {
                        $errorMsg = "E-mail or passwords do not seem to match...";
                        $success = false;
                    }
                }
                $stmt->close();
            }
            $conn->close();
            return $success;
        }

        include "nav.inc.php"
        ?>
        <main class="container">
            <?php
            if ($success) {
                if (retrieveMemberFromDB()) {
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