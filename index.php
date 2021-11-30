<!doctype html>
<html lang="en">
    <?php
    header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
    include "head.inc.php";
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
            <main class="container notLoggedInIndex">
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center">
                        <div class="d-flex justify-content-center">
                            <img src="images/favicon.png" width="256px" height="256px">
                            <p class='text-center logo-index align-self-center'>stori</p>
                        </div>
                        <br>
                        <div class="mb-4 lead">Please Login or register!</div>
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
                    <section class="col-xl-5">
                        <!--Create post box-->
                        <aside class ="card">
                            <div class="card-body">
                                <form action="submitPost.php" method="post" class="comment-area-box mb-3">
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input class="form-control" type="text" id="title" name="title" required placeholder="Enter title"> 
                                    </div>
                                    <span class="input-icon">
                                        <label for="content">Content:</label>
                                        <textarea rows="3" class="form-control" id="content" name="content" placeholder="Write something..."></textarea>
                                    </span>
                                    <div class="p-4">
                                        <label for="interestType">Interest Tags: </label>
                                        <div class="input-group  d-flex">
                                            <?php while ($row = $categoriesResults->fetch_array(MYSQLI_NUM)) { ?> 
                                                <input type="checkbox" class="btn-check" name="interest[]" id="btn-check<?php echo $row[0] ?>" autocomplete="off" value="<?php echo $row[0] ?>"/>
                                                <label class="btn btn-outline-dark" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1]; ?></label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="comment-area-btn">
                                        <label for="postType">Post Privacy: </label>
                                        <div class="input-group  d-flex">
                                            <select id="postType" name="postType" class="form-select form-select-sm" aria-label=".form-select-lg postPrivacy">
                                                <option selected value="0">Public</option>
                                                <option value="1">Followers Only</option>
                                                <option value="2">Subscribers only</option>
                                            </select>
                                            <div class="float-end">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Post</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </aside>
                        <!---->
                    </section>
                    <div class="col-xl-7">
                        <section class="card" role="feed">
                            <div class="card-body">

                                <!-- Story Box-->
                                <?php
                                $posts = getHomePagePosts($sessionUserID);
                                $author_id = -1;
                                $postAuthor;
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
