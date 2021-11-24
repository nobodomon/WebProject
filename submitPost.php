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
        }
        if ($success) {
            //$h3 = "<h3>Your Registration successful!</h3>";
            //$h4 = "<h4>Thank you for signing up, ". $_POST["lname"]. " " . $_POST["fname"] ."</h4>";
            //$btn = "<a href='#'><button class = 'btn btn-success'>Log-in </button></a><br>";
            if(createPost()){
                $h3 = "<h3>Post submitted!</h3>";
                
            }else{
                
            }

        } else {
            $h3 = "<h3>Oops!";
            $h4 = "<h4>The following input errors were detected:</h4>";
            $errors = "<p>" . $errorMsg . "</p>";
            $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
        }
        
        function createPost(){
            global $success,$errorMsg, $title, $content;
            
            $sanitizedContent = htmLawed($content);
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if($conn->connect_error){
                $errorMsg = "Connection failed: " . $conn -> connect_error;
                $success = false;
                
                $h3 = "<h3>Oops!";
                $h4 = "<h4>The following input errors were detected:</h4>";
                $errors = "<p>" . $errorMsg . "</p>";
                $btn = "<a href='register.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
            }else{
                $stmt = $conn -> prepare("INSERT INTO post (author_id, title, content,postedDateTime) VALUES (?,?,?,?)");

                //Bind & Execute the query statement:
                $dateTimeNow = date_create()->format('Y-m-d H:i:s');
                $stmt-> bind_param("isss", $_SESSION["userID"], $title, $sanitizedContent,$dateTimeNow);
                if(!$stmt->execute()){
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                    $h3 = "<h3>Oops!</h3>";
                    $h4 = "<h4>The following input errors were detected:</h4>";
                    $errors = "<p>" . $errorMsg . "</p>";
                    $btn = "<a href='index.php'><button class='btn btn-danger'>Return to Sign Up </button></a><br>";
                }else{
                    $h3 = "<h3>Post submitted!</h3>";
                    $btn = "<a href='#'><button class = 'btn btn-success'>Log-in </button></a><br>";
                }
                $stmt->close();
            }
            $conn->close();
            return $success;
        }
        ?>
        <main class="container">
            <hr>
                <?php
                    echo $h3;
                    echo $h4;
                    if (empty($errors)){
                    }else{
                        echo $errors;
                    }
                    echo $btn;
                    
                    header('Refresh: 5; Location:profile.php');
                ?>
            <br>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>