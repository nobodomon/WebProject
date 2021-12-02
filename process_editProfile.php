<!doctype html>
<html lang="en">
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
        if (empty($_POST["interest"])) {
            $interests = array();
        } else {
            $interests = $_POST["interest"];
        }
        $h4 = "";
        $btn = "";
//      check if any fields are empty
        if (empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["username"]) || empty($_POST["biography"])) {
            $errorMsg .= "All fields are required.<br>";
            $success = false;
        } else {
            $username = htmLawed($_POST["username"]);
            $fname = htmLawed($_POST["fname"]);
            $lname = htmLawed($_POST["lname"]);
            $biography = htmLawed($_POST["biography"]);
            if ($_POST["email"] != $_POST["confirm-email"]) {
                $success = false;
                $errorMsg .= "Emails do not match! <br>";
            } else {
                if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                    
                    $success = false;
                    $errorMsg .= "Email format is incorrect! <br>";
                }else{
                    if (strlen($_POST["email"]) > 45) {
                        $success = false;
                        $errorMsg .= "email is too long! <br>";
                    }else{
                        $email = $_POST["email"];
                    }
                }
            }
            if (strlen($username) > 45) {
                $success = false;
                $errorMsg .= "Username is too long! <br>";
            }
            if (strlen($fname) > 45) {
                $success = false;
                $errorMsg .= "First name is too long! <br>";
            }
            if (strlen($lname) > 45) {
                $success = false;
                $errorMsg .= "Last name is too long! <br>";
            }
            if (strlen($biography) > 512){
                $success = false;
                $errorMsg .= "Biography is too long! <br>";
            }
            if ($success) {
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
                if ($currentUserDetails["email"] == $email) {

                    $success = true;
                } else {
                    // if it is in $userResultsBasedOnUsername, dont allow as someone else taken the username
                    if ($userResultsBasedOnUsername["username"] == $email) {
                        $errorMsg .= "The email has been taken.<br>";
                        $success = false;
                    }
                    // check if in db if not then proceed to update
                    else {
                        $newUsername = $email;
                        $success = true;
                    }
                }
            }
        }
//        only add those additional interests
//        then those uncheck de need to remove
        if ($success) {
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, fname=?, lname=?, email =?, biography=? WHERE userID = ?");

                //Bind & Execute the query statement:
                $stmt->bind_param("sssssi",$username, $fname, $lname,$email, $biography, $_SESSION['userID']);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    $success = true;
                }
                $stmt->close();
                if ($success) {
                    $stmt = $conn->prepare("DELETE FROM interest WHERE userID = ?");
                    $stmt->bind_param("i", $_SESSION["userID"]);
                    if (!$stmt->execute()) {
                        $errorMsg .= $stmt->error;
                    } else {
                        foreach ($interests as $interest) {
                            $stmt = $conn->prepare("INSERT INTO interest(userID,categoryID) VALUES(?,?)");
                            $stmt->bind_param("ii", $_SESSION["userID"], $interest);
                            if (!$stmt->execute()) {

                                $success = false;
                                $errorMsg .= $stmt->error;
                            } else {
                                $success = true;
                                $successMsg = "Your profile has been updated!";
                            }
                            $stmt->close();
                        }
                    }
                } else {
                    
                }
            }
            $conn->close();
        }
        ?>
        <main class="container">
            <hr>
            <?php
            if ($success) {
                include("resources/templates/successpage.php");
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