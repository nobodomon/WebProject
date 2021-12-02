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
        if (empty($_POST["title"]) || empty($_POST["content"]) || empty($_POST["interest"])) {
            $interests = array();
            $errorMsg .= "Title and content and interest is required.<br>";
            $success = false;
        } else {
            if (empty($_POST["interest"])) {
                $interests = array();
            } else {
                $interests = $_POST["interest"];
            }
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
        if ($success) {
            //$h3 = "<h3>Your Registration successful!</h3>";
            //$h4 = "<h4>Thank you for signing up, ". $_POST["lname"]. " " . $_POST["fname"] ."</h4>";
            //$btn = "<a href='#'><button class = 'btn btn-success'>Log-in </button></a><br>";
            if (createPost()) {
                
            } else {
                $h3 = "<h3>Oops!";
                $h4 = "<h4>The following input errors were detected:</h4>";
                $errors = "<p>" . $errorMsg . "</p>";
                $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
            }
        } else {
            $h3 = "<h3>Oops!";
            $h4 = "<h4>The following input errors were detected:</h4>";
            $errors = "<p>" . $errorMsg . "</p>";
            $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
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

        function createPost() {
            global $success, $successMsg, $errorMsg, $title, $content, $postType, $interests;
            $sanitizedContent = htmLawed($content);
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if ($conn->connect_error) {
                $errorMsg .= "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $stmt = $conn->prepare("INSERT INTO post (author_id, title, content, postedDateTime, postType) VALUES (?,?,?,?,?)");
                //Bind & Execute the query statement:
                $dateTimeNow = date_create()->format('Y-m-d H:i:s');
                $stmt->bind_param("isssi", $_SESSION["userID"], $title, $sanitizedContent, $dateTimeNow, $postType);
                if (!$stmt->execute()) {
                    $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    // retrieve the post id
                    $stmt1 = $conn->prepare("SELECT * FROM post WHERE author_id = ? AND title = ? AND content = ? AND postType = ?");
                    $stmt1->bind_param("issi", $_SESSION["userID"], $title, $sanitizedContent, $postType);
                    $stmt1->execute();
                    $result = $stmt1->get_result();
                    $post = $result->fetch_assoc();

                    foreach ($interests as $interest) {
                        $stmt2 = $conn->prepare("INSERT INTO categoryForPost(postID, categoryID) VALUES(?,?)");
                        $stmt2->bind_param("ii", $post["post_id"], $interest);
                        if (!$stmt2->execute()) {
                            $errorMsg .= "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error;
                            $success = false;
                            break;
                        } else {
                            $successMsg = "Post successfully created!";
                            $success = true;
                        }
                        $stmt2->close();
                    }
                    $stmt1->close();
                }
                $stmt->close();
            }
            $conn->close();
            return $success;
        }

        if ($success) {
            include("resources/templates/successpage.php");
        } else {
            include("resources/templates/errorpage.php");
        }
        include "footer.inc.php"
        ?>
    </body>
</html>