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
        if(empty($_POST["interest"])){
            $interests = array();
        }else{
            $interests = $_POST["interest"];
        }
        if (empty($_POST["title"]) || empty($_POST["content"])) {
            $errorMsg .= "Title and content is required.<br>";
            $success = false;
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $postType = $_POST['postType'];
            $postID = $_GET['pid'];
            validatePostType();
        }
        if ($success) {
            //$h3 = "<h3>Your Registration successful!</h3>";
            //$h4 = "<h4>Thank you for signing up, ". $_POST["lname"]. " " . $_POST["fname"] ."</h4>";
            //$btn = "<a href='#'><button class = 'btn btn-success'>Log-in </button></a><br>";
            if (editPost()) {
                
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

        function editPost() {
            global $success, $errorMsg, $title, $content, $postType, $postID, $interests;
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
                    $stmt1->execute();
                    $stmt1->close();
                    foreach ($interests as $interest) {
                        $stmt2 = $conn->prepare("INSERT INTO categoryForPost(postID, categoryID) VALUES(?,?)");
                        $stmt2->bind_param("ii", $postID, $interest);
                        $stmt2->execute();
                        $stmt2->close();
                    }
                }
                $stmt->close();
            }
            $conn->close();
            return $success;
        }
        ?>
        <main class="container">
            <div class="page-wrap d-flex flex-row align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <?php if ($success) { ?>

                                <span class="display-1 d-block">Yay!</span>
                                <div class="mb-4 lead">Your post was edited successfully!</div>
                                <a href="index.php" class="btn btn-link">Back to Home</a>
                            <?php } else { ?>

                                <span class="display-1 d-block">Oops!</span>
                                <div class="mb-4 lead">The following errors are detected: </div>
                                <p><?php echo $errorMsg ?></p>
                                <a href="index.php" class="btn btn-link">Back to Home</a>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>