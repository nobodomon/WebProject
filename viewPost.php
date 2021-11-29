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
            <div class="card">
                <div class="border border-light p-2 mb-3 card-body">
                    <?php
                    $postExist = checkIfPostExist($postID);
                    if ($postExist) {
                        $post = getPostByID($postID);
                        $author = getUserFromID($post['author_id']);
                        $sessionUser;
                        $postPrivacy = $post["postType"];
                        if (!isset($_SESSION['userID'])) {
                            $sessionUserID = -1;
                            $followed = false;
                            $subscribed = false;
                        } else {
                            $sessionUserID = $_SESSION['userID'];
                            $sessionUser = getUserFromID($_SESSION['userID']);
                            $followed = checkIfFollowed($post["author_id"], $_SESSION['userID']);
                            $subscribed = checkIfSubscribed($post["author_id"], $_SESSION['userID']);
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
                    } else if (($postExist == true) && (($post["author_id"] == $sessionUserID) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed) || ($postPrivacy == 2 && $subscribed))) {
                        if ($post["author_id"] == $sessionUserID) {
                            ?>
                            <div class="dropdown float-end">
                                <a href="#" class="dropdown-toggle arrow-none card-drop nav-link" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Post Options">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="process_delete.php?postID=<?php echo $post["post_id"] ?>" class="dropdown-item">Delete Post</a>
                                    <!-- item-->
                                    <a href="editPost.php?postID=<?php echo $post["post_id"] ?>" class="dropdown-item">Edit Post</a>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <article class="d-flex align-items-start">
                            <img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $author['profilePic'] ?>" alt="Generic placeholder image">
                            <div class="w-100">
                                <a href="profile.php?userID=<?php echo $post["author_id"] ?>"  class="button-nopadding six"><?php echo $post["title"] ?>   </a> <small class="text-muted"><?php echo time_elapsed_string($post['postedDateTime']) ?></small>
                                <hr>
                                <section class="card-text">
                                    <?php echo $post["content"] ?></section>

                                <p class="text-right"><small>Posted By: 
                                    <a href="profile.php?userID=<?php echo $post["author_id"] ?>" class="button-nopadding six">
                                        @<?php echo $author['username'] ?>
                                    </a>
                                    <?php
                                    if ($post['edited'] == 0) {
                                        ?>
                                    <?php } else if ($post['edited'] == 1) { ?>
                                        edited <?php echo time_elapsed_string($post['editedDateTime']) ?>
                                        <?php
                                    } else {
                                        
                                    }
                                    ?>
                                    </small></p>
                                <div class="d-flex">
                                    <?php
                                    if ($sessionUserID == -1) {
                                        $processLike = "login.php";
                                    } else {

                                        $processLike = "process_like.php?postID=" . $post['post_id'];
                                    }
                                    ?>
                                    <a href="<?php echo $processLike ?>" class="button four d-inline-block mt-2 nav-link d-flex align-items-center">
                                        <?php
                                        $likes = getLikesForPost($post["post_id"]);
                                        $likeOrLikes = ($likes == 1) ? "Like" : "Likes";
                                        if ($sessionUserID == -1) {
                                            echo '<span class="material-icons">favorite_border</span>';
                                        } else {

                                            echo checkIfLiked($post["post_id"], $sessionUserID);
                                        }
                                        echo "&nbsp;(" . $likes . ' ' . $likeOrLikes . ")";
                                        ?> 
                                    </a>
                                    <a class="button four d-inline-block mt-2 commentToggleViewPost nav-link d-flex align-items-center">
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
                        </article>

                        <?php
                        $comments = getCommentsForPost($post["post_id"], -1);
                        if ($comments == "No post") {
                            
                        } else {
                            ?>
                            <div class="post-user-comment-box-viewPost">
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
                                    <div class="d-flex align-items-start" role="comment"  data-author="<?php echo $currCommentingUserID ?>">
                                        <img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $currCommentingUser['profilePic'] ?>" alt="Generic placeholder image">
                                        <div class="w-100">
                                            <a href="profile.php?userID=<?php echo $currCommentingUser["userID"] ?>" class="button-nopadding six"><?php echo $currCommentingUser['fname'] . ' ' . $currCommentingUser['lname'] ?></a><small class="text-muted"> <?php echo time_elapsed_string($commentRows[4]) ?></small><br>
                                            <?php echo $commentRows[3] ?>
                                        </div>
                                        <?php
                                        if ($currCommentingUser['userID'] == $sessionUserID) {
                                            ?>
                                            <div class="dropdown float-end">
                                                <a href="#" class="dropdown-toggle arrow-none card-drop nav-link" data-bs-toggle="dropdown" aria-label="Profile options" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <!-- item-->
                                                    <a href="process_deletecomment.php?commentID=<?php echo $commentRows[0] ?>" class="dropdown-item">Delete comment</a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                if ($sessionUserID == -1) {
                                    $commentProcess = "login.php";
                                    $placeHolderPic = "images/default.jpg";
                                } else {
                                    $commentProcess = 'process_comment.php?postID=' . $post["post_id"] . '&userID=' . $post["author_id"] . '&redirectTo=' . 1;
                                    $placeHolderPic = 'images/' . $sessionUser['profilePic'];
                                }
                                ?>
                                <div class="d-flex align-items-start mt-2">
                                    <a class="pe-2" href="#">
                                        <img src="<?php echo $placeHolderPic ?>" class="rounded-circle" alt="Generic placeholder image" height="31" width="31">
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
            </div>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>