<?php
/*
  Requires
 *$sessionUserID -> $_SESSION["userID];
 *$sessionUser -> getUserFromID($sessionUserID);
  $postID = $row[0];
  $author_id = $row[1];
  $title = $row[2];
  $content = $row[3];
  $postedDateTime = $row[4];
  $postPrivacy = $row[5];
  $edited = $row[6];
  $editedDateTime = $row[7];
  $postAuthor = getUserFromID($author_id);
 */
?>

<div class="border border-light p-2 mb-3">
    <?php
    if (($author_id == $sessionUserID)) {
        ?>

        <div class="dropdown float-end">
            <a href="#" class="nav-link dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Post Options">
                <i class="mdi mdi-dots-vertical"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <a href="process_delete.php?postID=<?php echo $postID ?>" class="dropdown-item">Delete Post</a>
                <!-- item-->
                <a href="editPost.php?postID=<?php echo $postID ?>" class="dropdown-item">Edit Post</a>
            </div>
        </div>
    <?php }
    ?>
    <article class="d-flex align-items-start">
        <a href="profile.php?userID=<?= $author_id ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/profilepics/' . $postAuthor['profilePic'] ?>" alt="Generic placeholder image"></a>
        <div class="w-100">
            <a href="viewPost.php?postID=<?php echo $postID ?>" class="button-nopadding six"><?php echo $title ?>   </a> <small class="text-muted"><?php echo time_elapsed_string($postedDateTime) ?></small>
            <hr>
            <section class="card-text">
                <?php echo $content ?></section>

            <?php if (getPostTagCount($postID) > 0) { ?>
                <div class="mt-3">
                    <p class="text-muted mb-2 font-13"><strong>Tags :</strong>
                        <?php
                        $tags = getInterestByPostID($postID);

                        while ($tag = $tags->fetch_array(MYSQLI_NUM)) {
                            ?>
                            <span class="badge rounded-pill bg-dark"><?php echo $tag[3] ?></span>
                            <?php
                        }
                        ?></p>
                </div>     
            <?php } else { ?>
                <div class="mt-3">
                    <p class="text-muted mb-2 font-13"><strong>Tags :  No Tags</strong></p>
                </div>
            <?php } ?>

            <p class="text-right"><small>Posted By: 
                    <a href="profile.php?userID=<?php echo $author_id ?>"  class="button-nopadding six">@<?php echo $postAuthor["username"] ?>
                    </a>
                    <?php
                    if ($edited == 0) {
                        ?>
                    <?php } else if ($edited == 1) { ?>
                        edited <?php echo time_elapsed_string($editedDateTime) ?>
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
                    $processLike = "process_like.php?postID=" . $postID;
                }
                ?>
                <a href="<?php echo $processLike ?>" class="button four d-inline-block mt-2 nav-link d-flex align-items-center" aria-label="Like this post">
                    <?php
                    $likes = getLikesForPost($postID);
                    $likeOrLikes = ($likes == 1) ? "Like" : "Likes";
                    if ($sessionUserID == -1) {
                        echo '<span class="material-icons">favorite_border</span>';
                    } else {

                        echo checkIfLiked($postID, $sessionUserID);
                    }
                    echo "&nbsp;(" . $likes . ' ' . $likeOrLikes . ")";
                    ?> 
                </a>
                <a class="button four d-inline-block mt-2 commentToggle nav-link d-flex align-items-center" aria-label="Show/Hide Comments">
                    <span class='material-icons'>chat_bubble_outline</span>
                    <span>
                        <?php
                        $commentCount = getCommentCountForPost($postID);
                        $commentOrComments = ($commentCount == 1) ? "Comment" : "Comments";
                        echo "&nbsp;(" . $commentCount . " " . $commentOrComments . ")";
                        ?> </span>
                </a>
            </div>
        </div>
    </article>

    <?php
    $comments = getCommentsForPost($postID);
    if ($comments == "No post") {
        
    } else {
        ?>
        <div class="post-user-comment-box">
            <?php
            if ($commentCount > 3) {
                ?>
                <div class='d-flex justify-content-between'>
                    <a href="viewPost.php?postID=<?php echo $postID ?>" class='text-center' >View More comments</a>
                    <br>
                    <hr/>
                </div>
                <?php
            }

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
                <div class="d-flex align-items-start" role="comment">
                    <a href="profile.php?userID=<?= $currCommentingUser['userID'] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/profilepics/' . $currCommentingUser['profilePic'] ?>" alt="Generic placeholder image"></a>
                    <div class="w-100">
                        <a href="profile.php?userID=<?php echo $currCommentingUser['userID'] ?>" class="button-nopadding six"><?php echo $currCommentingUser['fname'] . ' ' . $currCommentingUser['lname'] ?></a><small class="text-muted"> <?php echo time_elapsed_string($commentRows[4]) ?></small><br>
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
                $placeHolderPic = "images/profilepics/default.jpg";
            } else {
                $commentProcess = "process_comment.php?postID=" . $postID . "&userID=" . $author_id;
                $placeHolderPic = 'images/profilepics/' . $sessionUser['profilePic'];
            }
            ?>
            <div class="d-flex align-items-start mt-2">
                <a class="pe-2" href="profile.php?userID=<?php echo $sessionUserID ?>">
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
    ?>
</div>