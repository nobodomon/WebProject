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
        $interests = $_POST["interest"];
        $h4 = "";
        $btn = "";
//      check if any fields are empty
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
//        only add those additional interests
//        then those uncheck de need to remove
        if ($success) {
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, fname=?, lname=?, biography=? WHERE userID = ?");

                //Bind & Execute the query statement:
                $stmt->bind_param("ssssi", $_POST["username"], $_POST["fname"], $_POST["lname"], $_POST["biography"], $_SESSION['userID']);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    $success = true;
                }
                $stmt->close();
                $stmt = $conn->prepare("DELETE FROM interest WHERE userID = ?");
                $stmt->bind_param("i", $_SESSION["userID"]);
                $stmt->execute();
                $stmt->close();
                foreach ($interests as $interest) {
                    $stmt = $conn->prepare("INSERT INTO interest(userID,categoryID) VALUES(?,?)");
                    $stmt->bind_param("ii", $_SESSION["userID"], $interest);
                    $stmt->execute();
                    $stmt->close();
                }
                
                $h4 = "<h4>Edit Profile Successfully</h4>";
                $btn = "<a href='index.php'><button class = 'btn btn-success'>Return to Home Page</button></a><br>";
            }
            $conn->close();
        }
        ?>
        <main class="container">
            <hr>
            <?php
            echo $h4;
            if (empty($errorMsg)) {
                
            } else {
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