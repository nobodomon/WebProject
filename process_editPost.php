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
        $title = $errorMsg = "";
        $success = true;
        if (empty($_POST["interest"])) {
            $interests = array();
        } else {
            $interests = $_POST["interest"];
        }
        if (empty($_POST["title"]) || empty($_POST["content"])) {
            $errorMsg .= "Title and content is required.<br>";
            $success = false;
        } else {
            $postID = $_GET['pid'];
            $title = $_POST['title'];
            if (strlen($title) > 255) {
                $success = false;
                $errorMsg .= "Title is too long!";
            } else {
                $content = $_POST['content'];
                $postType = $_POST['postType'];
                validatePostType();
            }
        }

        function validatePostType() {
            global $postType, $success, $errorMsg;
            $allowedPostTypes = array(0, 1, 2);
            if (in_array((int) $postType, $allowedPostTypes, true)) {
                $success = true;
            } else {
                $success = false;
                $errorMsg .= "Invalid post type. <br>";
            }
        }

        function editPost() {
            global $success, $errorMsg,$successMsg, $title, $content, $postType, $postID, $interests;
            $sanitizedContent = htmLawed($content);
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if ($conn->connect_error) {
                $errorMsg .= "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {

                $dateTimeNow = date_create()->format('Y-m-d H:i:s');
                $edited = 1;
                $stmt = $conn->prepare("UPDATE post SET title =?, content =?, postType =?, editedDateTime =?, edited =? WHERE post_id =? AND author_id = ?");
                //Bind & Execute the query statement:
                $stmt->bind_param("ssisiii", $title, $sanitizedContent, $postType, $dateTimeNow, $edited, $postID, $_SESSION['userID']);
                if (!$stmt->execute()) {
                    $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    $success = true;
                    $stmt1 = $conn->prepare("DELETE FROM categoryForPost WHERE postID = ?");
                    $stmt1->bind_param("i", $postID);
                    if (!$stmt1->execute()) {
                        $success = false;
                        $errorMsg .= $stmt1->error;
                    } else {

                        foreach ($interests as $interest) {
                            $stmt2 = $conn->prepare("INSERT INTO categoryForPost(postID, categoryID) VALUES(?,?)");
                            $stmt2->bind_param("ii", $postID, $interest);
                            if (!$stmt2->execute()) {
                                $success = false;
                                $errorMsg .= $stmt2->error;
                            } else {
                                
                                $success = true;
                                $successMsg = "Post successfully edited!";
                            }
                            $stmt2->close();
                        }
                    }
                    $stmt1->close();
                }
                $stmt->close();
            }
            $conn->close();
            return $success;
        }
        ?>
        <main class="container">
            <?php
            if ($success) {
                if (editPost()) {
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