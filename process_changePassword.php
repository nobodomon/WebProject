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
        $h4 = $h3 = $btn = $errorMsg = "";
        $userID = $_SESSION['userID'];
        $oldPassword = $_POST["oldPassword"];
        $newPassword = $_POST["newPassword"];
        $confirmNewPassword = $_POST["confirmNewPassword"];
        // check if old password same as the hash password in db
        if (password_verify($oldPassword, $currentUserDetails["password"])) {
            // check and ensure that new password and confirm password same, if not throw error
            if ($newPassword == $confirmNewPassword) {
                // update new password
                $updatedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $config = parse_ini_file('../../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    $stmt = $conn->prepare("UPDATE users SET password=? WHERE userID = ?");
                    //Bind & Execute the query statement:
                    $stmt->bind_param("si", $updatedPassword, $userID);
                    if (!$stmt->execute()) {
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                    $stmt->close();
                }
                $conn->close();
                $h3 = "<h3>Change Password successful!</h3>";
                $h4 = "<h4></h4>";
                $btn = "<a href='index.php'><button class = 'btn btn-success'>Return to Home</button></a><br>";
            } else {
                $h3 = "<h3>Oops!</h3>";
                $h4 = "<h4>The following errors were detected:</h4>";
                $errorMsg = "<p> Password is not the same. Please try again....</p>";
                $btn = "<a href ='index.php'><button class = 'btn btn-warning'>Return to Home</button></a><br>";
            }
        } else {
            // incorrect old password input
            $h3 = "<h3>Oops!</h3>";
            $h4 = "<h4>The following errors were detected:</h4>";
            $errorMsg = "<p> Password is incorrect. Please try again....</p>";
            $btn = "<a href ='index.php'><button class = 'btn btn-warning'>Return to Home</button></a><br>";
        }
        
        ?>
        <main class="container">
            <hr>
            <?php
            echo $h3;
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