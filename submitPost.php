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
        if (empty($_POST["title"]) || empty($_POST["content"])) {
            $errorMsg .= "Title and content is required.<br>";
            $success = false;
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $postType = $_POST['postType'];
            validatePostType();
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
            if (in_array((int)$postType, $allowedPostTypes, true)) {
                $success = true;
            } else {
                $success = false;
                $errorMsg .= "Invalid post type. <br>";
            }
        }

        function createPost() {
            global $success, $errorMsg, $title, $content, $postType;
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
                    $success = true;
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
                                <div class="mb-4 lead">Your post was created successfully!</div>
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