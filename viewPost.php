<!doctype html>
<?php
session_start();
$postID = $_GET["postID"];
?>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <?php
        ?>
        <main class="container">
            <div class="border border-light p-2 mb-3">
                <?php
                $postExist = checkIfPostExist($postID);
                if ($postExist) {

                    $post = getPostByID($postID);
                    $postPrivacy = $post["postType"];
                    if (!isset($_SESSION['userID'])) {
                        $sessionUser = -1;
                        $followed = false;
                    } else {
                        $sessionUser = $_SESSION['userID'];
                        $followed = checkIfFollowed($post["author_id"], $_SESSION['userID']);
                    }
                }
                if ($postExist == false) {
                    ?>
                    <div class="page-wrap d-flex flex-row align-items-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12 text-center">
                                    <span class="display-1 d-block">Oops!</span>
                                    <div class="mb-4 lead">The post was not found or you do not have the permissions to view this post.</div>
                                    <a href="index.php" class="btn btn-link">Back to Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else if (($postExist == true) && (($post["author_id"] == $sessionUser) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed))) {
                    if ($post["author_id"] == $sessionUser) {
                        ?>
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Post Options">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="process_delete.php?postID=<?php echo $post["post_id"] ?>" class="dropdown-item">Delete Post</a>
                                <!-- item-->
                                <a href="editPost.php" class="dropdown-item">Edit Post</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="d-flex align-items-start" aria-labelledby="postContent">
                        <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="Generic placeholder image">
                        <div class="w-100">
                            <h5 class=""><a href="profile.php?userID=<?php echo $post["author_id"]?>" class="button four"><?php echo $post["title"] ?>   </a> <small class="text-muted"><?php echo time_elapsed_string($post["postedDateTime"]) ?></small></h5>
                            <p class="card-text">
                                <?php echo $post["content"] ?></p>
                            <div class="d-flex">
                                <br>
                                <?php
                                if ($sessionUser == -1) {
                                    $processLike = "login.php";
                                } else {

                                    $processLike = "process_like.php?postID=" . $post['post_id'];
                                }
                                ?>
                                <a href="<?php echo $processLike?>" class="button four d-inline-block mt-2 nav-link d-flex align-items-center" aria-label="Like this post">
                                    <?php
                                    $likes = getLikesForPost($post["post_id"]);
                                    $likeOrLikes = ($likes == 1) ? "Like" : "Likes";
                                    if ($sessionUser == -1) {
                                        echo '<span class="material-icons">favorite_border</span>';
                                    } else {

                                        echo checkIfLiked($post["post_id"], $sessionUser);
                                    }
                                    echo "&nbsp;(" . $likes . ' ' . $likeOrLikes . ")";
                                    ?> 
                                </a>
                                <a class="button four d-inline-block mt-2 commentToggleViewPost nav-link d-flex align-items-center" aria-label="Show/Hide Comments">
                                    <span class='material-icons'>chat_bubble</span>
                                    <span>
                                        <?php
                                        $commentCount = getCommentCountForPost($post["post_id"]);
                                        $commentOrComments = ($commentCount == 1) ? "Comment" : "Comments";
                                        echo "&nbsp;(" . $commentCount . " " . $commentOrComments . ")";
                                        ?> </span>
                                </a>
                            </div>
                            <hr>
                        </div>
                    </div>

                    <?php
                    $comments = getCommentsForPost($post["post_id"], -1);
                    if ($comments == "No post") {
                        
                    } else {
                        ?>
                        <div class="post-user-comment-box-viewPost" aria-labelledby="postComments">
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
                                        <h5 class="mt-0"><a href="profile.php?userID=<?php $currCommentingUser[0] ?>"><?php echo $currCommentingUser['fname'] . ' ' . $currCommentingUser['lname'] ?></a><small class="text-muted"> <?php echo time_elapsed_string($commentRows[4]) ?></small></h5>
                                        <?php echo $commentRows[3] ?>
                                    </div>
                                    <?php
                                    if ($currCommentingUser['userID'] == $sessionUser) {
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
                            if ($sessionUser == -1) {
                                $commentProcess = "login.php";
                            } else {
                                $commentProcess = 'process_comment.php?postID=$post["post_id"]' . '&userID=' . $post["author_id"] . '&redirectTo=' . 1;
                            }
                            ?>
                            <div class="d-flex align-items-start mt-2">
                                <a class="pe-2" href="#">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle" alt="Generic placeholder image" height="31">
                                </a>
                                <form class="d-flex" method="post" action="<?php echo $commentProcess ?>">
                                    <input type="text" id="comment" name="comment" class="form-control border-0 form-control-sm me-2" required placeholder="Add comment">
                                    <button type="submit" name="_submit" class="btn btnoutline-primary" value='Submit'>Submit</button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>