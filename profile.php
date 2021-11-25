<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php";
        $userID = $_GET['userID'];
        $viewingUser = getUserFromID($userID);
        $posts = getPostByUser($userID);
        ?>
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <section class="card" aria-labelledby="Profile Card">
                        <div class="card-body">
                            <?php
                            if ($userID == $_SESSION['userID']) {
                                ?> 
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop nav-link " data-bs-toggle="dropdown" aria-label="Profile options" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <!-- item-->
                                        <a href="editProfile.php" class="dropdown-item">Edit Profile</a>
                                        <!-- item-->
                                        <a href="logout.php" class="dropdown-item">Logout</a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="d-flex align-items-start">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                <div class="w-100 ms-3">
                                    <h4 class="my-0"><?php echo $viewingUser['fname'] . ' ' . $viewingUser['lname']; ?></h4>
                                    <p class="text-muted">@<?php echo $viewingUser['username'] ?></p>
                                    <?php
                                    $followed = checkIfFollowed($userID, $_SESSION['userID']);
                                    if ($_SESSION['userID'] == $userID) {
                                        
                                    } else if ($followed == false) {

                                        echo "<a href='process_follow.php?followerID=$userID' class='button three'>Follow</a>";
                                    } else {

                                        echo "<a href='process_follow.php?followerID=$userID' class='button three'>Unfollow</a>";
                                    }
                                    ?>
                                    <button type="button" class="btn btn-soft-success btn-xs waves-effect mb-2 waves-light">Subscribe</button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h4 class="font-13 text-uppercase">About Me :</h4>
                                <p class="text-muted font-13 mb-3">
                                    <?php echo $viewingUser['biography'] ?>
                                </p>
                                <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2"><?php echo $viewingUser['fname'] . ' ' . $viewingUser['lname']; ?></span></p>
                            </div>                                    

                            <ul class="social-list list-inline mt-3 mb-0">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item text-center border-primary text-primary" aria-label="Facebook"><i class="mdi mdi-facebook"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item text-center border-info text-info" aria-label="Twitter"><i class="mdi mdi-twitter"></i></a>
                                </li>
                            </ul>   
                        </div>                                 
                    </section> <!-- end card -->

                    <!--To implement follower and subscriber count-->
                    <section class="card" aria-labelledby="Profile statistics">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-4 border-end border-light">
                                    <h5 class="text-muted mt-1 mb-2 fw-normal">Followers</h5>
                                    <h2 class="mb-0 fw-bold"><?php echo getFollowerCount($userID) ?></h2>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <h5 class="text-muted mt-1 mb-2 fw-normal">Following</h5>
                                    <h2 class="mb-0 fw-bold"><?php echo getFollowingCount($userID) ?></h2>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <h5 class="text-muted mt-1 mb-2 fw-normal">Subscribers</h5>
                                    <h2 class="mb-0 fw-bold">0</h2>
                                </div>
                            </div>
                        </div>
                    </section>
                </div> <!-- end col-->

                <div class="col-xl-7">
                    <section class="card" aria-labelledby="Posts">
                        <div class="card-body">
                            <!-- comment box -->
                            <?php
                            if ($userID == $_SESSION['userID']) {
                                ?> 
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
                                <?php
                            }
                            ?>

                            <!-- end comment box -->

                            <!-- Story Box-->
                            <?php
                            $postCount = getPostCountOfUser($userID);
                            if ($postCount == 0) {
                                if (($userID == $_SESSION['userID'])) {

                                    echo "<p>You have not not posted anything.</p>";
                                } else {
                                    echo "<p>This user has not posted anything.</p>";
                                }
                            } else {

                                while ($row = $posts->fetch_array(MYSQLI_NUM)) {
                                    ?>

                                    <div class="border border-light p-2 mb-3">
                                        <?php
                                        $postPrivacy = $row[5];
                                        if (($userID == $_SESSION['userID']) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed)) {
                                            if (($userID == $_SESSION['userID'])) {
                                                ?>

                                                <div class="dropdown float-end">
                                                    <a href="#" class="nav-link dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Post Options">
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
                                                    <h5 class=""><a href="viewPost.php?postID=<?php echo $row[0] ?>" class="button two"><?php echo $row[2] ?>   </a> <small class="text-muted"><?php echo time_elapsed_string($row[4]) ?></small></h5>
                                                    <p class="card-text">
                                                        <?php echo $row[3] ?></p>

                                                    <p class="text-right">Posted By: <a href="profile.php?userID=<?php echo $row[1] ?>" class="button two">@<?php echo $viewingUser['username'] ?></a></p>
                                                    <div class="d-flex">
                                                        <br>
                                                        <a href="process_like.php?postID=<?php echo $row[0] ?>" class="button four d-inline-block mt-2 nav-link d-flex align-items-center" aria-label="Like this post">
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
                                                        if($currCommentingUserID == -1){
                                                            $currCommentingUserID = $commentRows[2];
                                                            $currCommentingUser = getUserFromID($commentRows[2]);
                                                        }else if ($currCommentingUserID != $commentRows[2]){
                                                            $currCommentingUserID = $commentRows[2];
                                                            $currCommentingUser = getUserFromID($commentRows[2]);
                                                        }
                                                        ?>
                                                        <div class="d-flex align-items-start">
                                                            <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="Generic placeholder image">
                                                            <div class="w-100">
                                                                <h5 class="mt-0"><a href="profile.php?userID=<?php echo $currCommentingUser['userID'] ?>" class="button two"><?php echo $currCommentingUser['fname'] . ' ' . $currCommentingUser['lname'] ?></a><small class="text-muted"> <?php echo time_elapsed_string($commentRows[4]) ?></small></h5>
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
                                                        <form class="d-flex" method="post" action="process_comment.php?postID=<?php echo $row[0] . '&userID=' . $row[1] . '&redirectTo=' . 0 ?>">
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
                            }
                            ?>
                        </div> <!-- end card-->

                    </section> <!-- end col -->
                </div>
                <!-- end row-->
            </div>
        </div>
    </div>
    <?php
    include "footer.inc.php"
    ?>
</body>
</html>
