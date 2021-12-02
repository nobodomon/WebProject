<?php
session_start();
$postID = $_GET['postID'];
$currUser = $_SESSION['userID'];
$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
if ($conn->connect_error) {
    $errorMsg = "Connect failed " . $conn->connect_error;
    $success = false;
} else {
    $stmt = $conn->prepare("DELETE FROM post WHERE post_id =? AND author_id =?");
    $stmt->bind_param("ii", $postID, $currUser);
    if (!$stmt->execute()) {
        $errorMsg = "Statement failed " . $stmt->error;
        $success = false;
    } else {
        $success = true;
        $successMsg = "Your post has been successfully deleted.";
    }
    $stmt->close();
}
$conn->close();
?>
<!doctype html>
<html lang="en">
    <?php include "head.inc.php" ?>
    <body>
        <?php include "nav.inc.php" ?>
        <main class="container">
            <?php
            if ($success) {
                include("resources/templates/successpage.php");
            } else {
                include("resources/templates/errorpage.php");
            }
            ?>
        </main>
        <?php include "footer.inc.php" ?>
    </body>

</html>
