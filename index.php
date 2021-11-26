<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>   
        <?php
        include "nav.inc.php";
        $list = "";
        $rowCount = 99;
        $array = array();
        if (empty($_SESSION["userID"])) {
            ?>
            <div class="page-wrap d-flex flex-row align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            <p class='text-center logo-index'>stori</p>
                            <br>
                            <div class="mb-4 lead">Please Login or register!</div>
                            <a href="register.php" class="button three">Register</a>
                            <a href="login.php" class="button three">Login</a>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <?php
        } else {
            $userID = $_SESSION['userID'];
            $errorMsg = "";
            ?>
            <header class="jumbotron text-center" id="home">
            </header>
            <main class="container">
                <div class="row">
                    <section class="col-xl-4">
                        <!--Create post box-->
                        <article class = "card" aria-labelledby="Create a new post box">
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
                                    <div class="comment-area-btn">
                                        <label for="postType">Post Privacy: </label>
                                        <div class="input-group  d-flex">
                                            <select id="postType" name="postType" class="form-select form-select-sm" aria-label=".form-select-lg postPrivacy">
                                                <option selected value="0">Public</option>
                                                <option value="1">Followers Only</option>
                                                <option value="2">Subscribers only</option>
                                            </select>
                                            <div class="float-end">
                                                <button type="submit" class="btn btn-outline-success waves-effect waves-light">Post</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </article>
                        <!---->
                    </section>
                    <div class="col-xl-8">
                        <section class="card" aria-labelledby="Posts">
                            <div class="card-body">

                                <!-- Story Box-->
                                <?php
                                $posts = getHomePagePosts($userID);
                                $currUserID = -1;
                                $currUser;
                                while ($row = $posts->fetch_array(MYSQLI_NUM)) {
                                    ?>

                                    <div class="border border-light p-2 mb-3">
                                        <?php
                                        $postPrivacy = $row[5];
                                        $followed = checkIfFollowed($row[1], $userID);
                                        if ($currUserID == -1) {
                                            $currUserID = $row[1];
                                            $currUser = getUserFromID($currUserID);
                                        } else if ($currUser != $row[1]) {
                                            $currUserID = $row[1];
                                            $currUser = getUserFromID($currUserID);
                                        }
                                        if (($userID == $row[1]) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed)) {
                                            if ($userID == $row[1]) {
                                                ?>

                                                <div class="dropdown float-end">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop nav-link " data-bs-toggle="dropdown" aria-expanded="false" aria-label="Post Options">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- item-->
                                                        <a href="process_delete.php?postID=<?php echo $row[0] ?>" class="dropdown-item">Delete Post</a>
                                                        <!-- item-->
                                                        <a href="editPost.php" class="dropdown-item">Edit Post</a>
                                                    </div>
                                                </div>
                                            <?php }
                                            ?>
                                            <div class="d-flex align-items-start" aria-labelledby="postContent">
                                                <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="Generic placeholder image">
                                                <div class="w-100">
                                                    <h5 class=""><a href="viewPost.php?postID=<?php echo $row[0] ?>" class="button four"><?php echo $row[2] ?>   </a> <small class="text-muted"><?php echo time_elapsed_string($row[4]) ?></small></h5>
                                                    <p class="card-text">
                                                        <?php echo $row[3] ?></p>

                                                    <p class="text-right">Posted By: <a href="profile.php?userID=<?php echo $currUserID ?>" class="button four">@<?php echo $currUser['username'] ?></a></p>
                                                    <div class="d-flex">
                                                        <br>
                                                        <a href="process_like.php?postID=<?php echo $row[0] . '&redirectTo=' . 2 ?>" class="button four d-inline-block mt-2 nav-link d-flex align-items-center" aria-label="Like this post">
                                                            <?php
                                                            $likes = getLikesForPost($row[0]);
                                                            $likeOrLikes = ($likes == 1) ? "Like" : "Likes";
                                                            echo checkIfLiked($row[0], $_SESSION['userID']);
                                                            echo "&nbsp;(" . $likes . ' ' . $likeOrLikes . ")";
                                                            ?> 
                                                        </a>
                                                        <a href="#<?php echo $row[0] ?>" class="button four d-inline-block mt-2 commentToggle nav-link d-flex align-items-center" aria-label="Show/Hide Comments">
                                                            <span class='material-icons'>chat_bubble_outline</span>
                                                            <span>
                                                                <?php
                                                                $commentCount = getCommentCountForPost($row[0]);
                                                                $commentOrComments = ($commentCount == 1) ? "Comment" : "Comments";
                                                                echo "&nbsp;(" . $commentCount . " " . $commentOrComments . ")";
                                                                ?> </span>
                                                        </a>
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>

                                            <?php
                                            $comments = getCommentsForPost($row[0]);
                                            if ($comments == "No post") {
                                                
                                            } else {
                                                ?>
                                                <div class="post-user-comment-box" aria-labelledby="postComments">
                                                    <?php
                                                    $currCommentingUserID = -1;
                                                    $currCommentingUser;
                                                    while ($commentRows = $comments->fetch_array(MYSQLI_NUM)) {
                                                        if ($currCommentingUserID == -1) {
                                                            $currCommentingUserID = $commentRows[2];
                                                            $currCommentingUser = getUserFromID($commentRows[2]);
                                                        } else if ($currCommentingUserID != $commentRows[2]) {
                                                            $currCommentingUserID = $commentRows[2];
                                                            $currCommentingUser = getUserFromID($commentRows[2]);
                                                        }
                                                        ?>
                                                        <div class="d-flex align-items-start">
                                                            <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="Generic placeholder image">
                                                            <div class="w-100">
                                                                <h5 class="mt-0"><a href="profile.php?userID=<?php echo $currCommentingUser["userID"] ?>" class="button four"><?php echo $currCommentingUser['fname'] . ' ' . $currCommentingUser['lname'] ?></a><small class="text-muted"> <?php echo time_elapsed_string($commentRows[4]) ?></small></h5>
                                                                <?php echo $commentRows[3] ?>
                                                            </div>
                                                            <?php
                                                            if ($currCommentingUser['userID'] == $userID) {
                                                                ?>
                                                                <div class="dropdown float-end">
                                                                    <a href="#" class="dropdown-toggle arrow-none card-drop nav-link" data-bs-toggle="dropdown" aria-label="Profile options" aria-expanded="false">
                                                                        <i class="mdi mdi-dots-vertical"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <!-- item-->
                                                                        <a href="process_deletecomment.php?commentID=<?php echo $commentRows[0] ?>" class="dropdown-item">Delete comment</a>
                                                                        <!-- item-->
                                                                        <a href="logout.php" class="dropdown-item">Edit comment</a>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="d-flex align-items-start mt-2">
                                                        <a class="pe-2" href="#">
                                                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle" alt="Generic placeholder image" height="31">
                                                        </a>
                                                        <form class="d-flex" method="post" action="process_comment.php?postID=<?php echo $row[0] . '&userID=' . $row[1] . '&redirectTo=' . 2 ?>">
                                                            <input type="text" id="comment" name="comment" class="form-control border-0 form-control-sm me-2" required placeholder="Add comment">
                                                            <button type="submit" name="_submit" class="btn btnoutline-primary" value='Submit'>Submit</button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
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
