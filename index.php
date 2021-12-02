<!doctype html>
<html lang="en">
    <?php
    header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
    include "head.inc.php"
    ?>
    <body>   
        <?php
        include "nav.inc.php";
        $list = "";
        $rowCount = 99;
        $array = array();
        $categoriesResults = getAllCategories();
        if (empty($_SESSION["userID"])) {
            ?>
            <main class="container notLoggedInIndex align-self-stretch">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="images/favicon.png" class="indexLogo">
                        <p class='text-center logo-index align-self-center'>stori</p>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center lead">Please Login or register!</div>
                    <div class="d-flex justify-content-center">

                        <a href="register.php" class="button three">Register</a>
                        <a href="login.php" class="button three">Login</a>
                    </div>
                </div>
            </main>
            <?php
        } else {
            $sessionUserID = $_SESSION['userID'];
            $sessionUser = getUserFromID($sessionUserID);
            $errorMsg = "";
            ?>
            <main class="container">
                <div class="row">
                    <div class="col-xl-5">
                        <!--Create post box-->
                        <section class ="card">
                            <div class="card-body">
                                <?php include("resources/templates/createpostwidget.php") ?>
                            </div>
                        </section>
                        <!---->
                    </div>
                    <div class="col-xl-7">
                        <section class="card" role="feed">
                            <div class="card-body">

                                <!-- Story Box-->
                                <?php
                                $posts = getHomePagePosts($sessionUserID);
                                $postCount = getHomePagePostCount($sessionUserID);
                                $author_id = -1;
                                $postAuthor;
                                if ($postCount > 0) {
                                    while ($row = $posts->fetch_array(MYSQLI_NUM)) {
                                        ?>

                                        <?php
                                        $followed = checkIfFollowed($row[1], $sessionUserID);
                                        $subscribed = checkIfSubscribed($row[1], $sessionUserID);
                                        $postID = $row[0];
                                        $title = $row[2];
                                        $content = $row[3];
                                        $postedDateTime = $row[4];
                                        $postPrivacy = $row[5];
                                        $edited = $row[6];
                                        $editedDateTime = $row[7];
                                        if ($author_id == -1) {
                                            $author_id = $row[1];
                                            $postAuthor = getUserFromID($author_id);
                                        } else if ($author_id != $row[1]) {
                                            $author_id = $row[1];
                                            $postAuthor = getUserFromID($author_id);
                                        }

                                        if (($sessionUserID == $row[1]) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed) || ($postPrivacy == 2 && $subscribed)) {
                                            include("resources/templates/post.php");
                                        }
                                    }
                                } else {
                                    ?>

                                    <div class="border border-light p-2">
                                        <p class="text-center">Your feed is empty!</p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div> <!-- end card-->

                        </section> <!-- end col -->
                    </div>
                    <!-- end row-->
                </div>
            </main>
            <?php
        }
        include "footer.inc.php"
        ?>
    </body>
</html>
