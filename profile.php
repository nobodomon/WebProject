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
        if (!isset($_SESSION['userID'])) {
            $sessionUserID = -1;
            $followed = false;
            $subscribed = false;
        } else {
            $sessionUserID = $_SESSION['userID'];
            $sessionUser = getUserFromID($_SESSION['userID']);
            $followed = checkIfFollowed($userID, $_SESSION['userID']);
            $subscribed = checkIfSubscribed($userID, $_SESSION['userID']);
        }
        ?>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <?php
                        if ($subscribed) {
                            $title = "Confirm to unsubscribe?";
                        } else {

                            $title = "Confirm subscription?";
                        }
                        ?>
                        <h5 class="modal-title" id="staticBackdropLabel"><?php echo $title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        if ($subscribed) {
                            //Unsubscribe dialog
                            $subBtn = "<a href='process_subscribe.php?subscriberID=$userID'class='button three'>Unsubscribe</a>";
                            ?>
                            <h3>Are you sure?</h3>
                            <p>Subscriptions are non refundable!</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <?= $subBtn ?>
                            </div>
                            <?php
                        } else {
                            //Subscribe dialog
                            $subBtn = "<button type='submit' class='button three'>Subscribe</button>";
                            ?>
                            <h3>Confirm Subscription?</h3>
                            <p>Subscribing to <?= "@" . $viewingUser['username'] ?> will allow you to see exclusive subscriber only posts.</p>
                            <form method="post" action="process_subscribe.php?subscriberID=<?php echo $userID ?>">
                                <div class="p-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary">Name on card</span>
                                        <input class="form-control" required type="text" id="cardName" name="cardName" placeholder="Enter Name on card">
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary">Credit Card Number</span>
                                        <input class="form-control" required type="number" inputmode="numeric" id="ccNo" pattern="[0-9]*" name="ccNo" placeholder="1111-2222-3333-4444">
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary">Expiry Month</span>
                                        <select class="form-control" required type="text" id="expiryMonth" min="1" max="12" name="expiryMonth" placeholder="Enter Name on card">
                                            <?php
                                            $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                            $monthIndex = 1;
                                            foreach ($months as $month) {
                                                ?>
                                                <option value="<?php echo $monthIndex ?>"><?php echo $month ?></option>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary">Expiry Year</span>
                                        <select class="form-control" required id="expiryYear" name="expiryYear" placeholder="Enter Name on card">
                                            <?php
                                            $min = date("Y");
                                            $max = date("Y") + 4;

                                            for ($i = $min; $i < $max + 1; $i++) {
                                                ?>
                                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-primary">CCV</span>
                                        <input class="form-control" required type="number" id="ccv" name="ccv" pattern="[0-9]*" placeholder="Enter ccv on card">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <?= $subBtn ?>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="followersModal" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="followersModalLabel">Followers</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $followers = getFollowers($userID);
                        $followerCount = getFollowerCount($userID);
                        if ($followerCount == 0) {
                            ?>
                            <div class="d-flex align-items-center mb-3">
                                <p class="text-center flex-grow-1">No followers</p>
                            </div>
                            <?php
                        } else {
                            while ($follower = $followers->fetch_array(MYSQLI_NUM)) {
                                ?>
                                <div class="d-flex align-items-start mb-3">
                                    <a href="profile.php?userID=<?= $follower[0] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $follower[8] ?>" alt="Generic placeholder image"></a>
                                    <div class="w-100 align-self-center">
                                        <a href="profile.php?userID=<?php echo $follower[0] ?>" class="button-nopadding six"><?php echo $follower[1] ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="button three" data-bs-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="followingModal" tabindex="-1" aria-labelledby="followingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="followingModalLabel">Following</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $following = getFollowing($userID);
                        $followingCount = getFollowingCount($userID);
                        if ($followingCount == 0) {
                            ?>
                            <div class="d-flex align-items-center mb-3">
                                <p class="text-center flex-grow-1">Not following any user</p>
                            </div>
                            <?php
                        } else {
                            while ($followingUser = $following->fetch_array(MYSQLI_NUM)) {
                                ?>
                                <div class="d-flex align-items-start mb-3">
                                    <a href="profile.php?userID=<?= $followingUser[0] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $followingUser[8] ?>" alt="Generic placeholder image"></a>
                                    <div class="w-100 align-self-center">
                                        <a href="profile.php?userID=<?php echo $followingUser[0] ?>" class="button-nopadding six"><?php echo $followingUser[1] ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="button three" data-bs-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="subscriberModal" tabindex="-1" aria-labelledby="subscriberModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subscriberModalLabel">Subscribers</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $subscribers = getSubscribers($userID);
                        $subscribersCount = getSubscribersCount($userID);
                        if ($subscribersCount == 0) {
                            ?>
                            <div class="d-flex align-items-center mb-3">
                                <p class="text-center flex-grow-1">No subscribers</p>
                            </div>
                            <?php
                        } else {
                            while ($subscriber = $subscribers->fetch_array(MYSQLI_NUM)) {
                                ?>
                                <div class="d-flex align-items-start mb-3">
                                    <a href="profile.php?userID=<?= $subscriber[0] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $subscriber[8] ?>" alt="Generic placeholder image"></a>
                                    <div class="w-100 align-self-center">
                                        <a href="profile.php?userID=<?php echo $subscriber[0] ?>" class="button-nopadding six"><?php echo $subscriber[1] ?></a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="button three" data-bs-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>
        <main class="container">
            <div class="row">
                <div class="col-xl-5">
                    <aside class="card">
                        <div class="card-body">
                            <?php
                            if ($userID == $sessionUserID) {
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
                                <img src="<?php echo 'images/' . $viewingUser['profilePic'] ?>" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                <div class="w-100 ms-3">
                                    <h4 class="my-0"><?php echo $viewingUser['fname'] . ' ' . $viewingUser['lname']; ?></h4>
                                    <p class="text-muted">@<?php echo $viewingUser['username'] ?></p>
                                    <?php
                                    if ($sessionUserID == $userID) {
                                        
                                    } else if ($followed == false) {
                                        if ($sessionUserID == -1) {

                                            echo "<a href='login.php' class='button three'>Follow</a>";
                                        } else {

                                            echo "<a href='process_follow.php?followerID=$userID' class='button three'>Follow</a>";
                                        }
                                    } else {

                                        echo "<a href='process_follow.php?followerID=$userID' class='button three'>Unfollow</a>";
                                    }
                                    ?>

                                    <?php
                                    if ($sessionUserID == $userID) {
                                        
                                    } else if ($subscribed == false) {
                                        if ($sessionUserID == -1) {

                                            echo "<a href='login.php' class='button three'>Subscribe</a>";
                                        } else {
                                            echo "<a href='#' class='button three' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Subscribe</a>";
                                        }
                                    } else {
                                        echo "<a href='#' class='button three' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Unsubscribe</a>";
                                    }
                                    ?>
                                    <!--upon clicking subscribe have a payment page form-->
                                    <!--need to get card no, card name, cvv-->
                                    <!--check if card is valid anot-->
                                    <!--validate client and server side-->


                                </div>
                            </div>

                            <div class="mt-3">
                                <h4 class="font-13 text-uppercase">About Me :</h4>
                                <p class="text-muted font-13 mb-3">
                                    <?php echo $viewingUser['biography'] ?>
                                </p>
                                <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2"><?php echo $viewingUser['fname'] . ' ' . $viewingUser['lname']; ?></span></p>
                            </div>   
                            <div class="mt-3">
                                <p class="text-muted mb-2 font-13"><strong>Interests :</strong> 
                                    <?php
                                    $interests = getCategoriesOfUser($userID);

                                    while ($interest = $interests->fetch_array(MYSQLI_NUM)) {
                                        ?>
                                        <span class="badge rounded-pill bg-dark"><?php echo $interest[3] ?></span>
                                        <?php
                                    }
                                    ?>
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
                    </aside> <!-- end card -->

                    <!--To implement follower and subscriber count-->
                    <section class="card">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-4 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Followers</h5>
                                        <a class="button-nopadding six statisticCount" data-bs-toggle="modal" data-bs-target="#followersModal"><?php echo $followerCount ?></a>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Following</h5>
                                        <a class="button-nopadding six statisticCount" data-bs-toggle="modal" data-bs-target="#followingModal"><?php echo $followingCount ?></a>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Subscribers</h5>
                                        <a class="button-nopadding six statisticCount" data-bs-toggle="modal" data-bs-target="#subscriberModal"><?php echo $subscribersCount ?></a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div> <!-- end col-->

                <div class="col-xl-7">
                    <section class="card" role="feed">
                        <div class="card-body">
                            <!-- comment box -->
                            <?php
                            if ($userID == $sessionUserID) {
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
                                                <button type="submit" class="btn btn-outline-primary waves-effect waves-light">Post</button>
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
                                if (($userID == $sessionUserID)) {

                                    echo "<p class='text-center flex-grow-1'>You have not not posted anything.</p>";
                                } else {
                                    echo "<p class='text-center flex-grow-1'>This user has not posted anything.</p>";
                                }
                            } else {

                                while ($row = $posts->fetch_array(MYSQLI_NUM)) {
                                    ?>

                                    <?php
                                    $postPrivacy = $row[5];
                                    if (($userID == $sessionUserID) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed) || ($postPrivacy == 2 && $subscribed)) {
                                        ?>

                                        <div class="border border-light p-2 mb-3">
                                            <?php
                                            if (($userID == $sessionUserID)) {
                                                ?>

                                                <div class="dropdown float-end">
                                                    <a href="#" class="nav-link dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Post Options">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- item-->
                                                        <a href="process_delete.php?postID=<?php echo $row[0] ?>" class="dropdown-item">Delete Post</a>
                                                        <!-- item-->
                                                        <a href="editPost.php?postID=<?php echo $row[0] ?>" class="dropdown-item">Edit Post</a>
                                                    </div>
                                                </div>
                                            <?php }
                                            ?>
                                            <article class="d-flex align-items-start">
                                                <a href="profile.php?userID=<?= $viewingUser['userID'] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $viewingUser['profilePic'] ?>" alt="Generic placeholder image"></a>
                                                <div class="w-100">
                                                    <a href="viewPost.php?postID=<?php echo $row[0] ?>" class="button-nopadding six"><?php echo $row[2] ?>   </a> <small class="text-muted"><?php echo time_elapsed_string($row[4]) ?></small>
                                                    <hr>
                                                    <section class="card-text">
                                                        <?php echo $row[3] ?></section>

                                                    <p class="text-right"><small>Posted By: 
                                                            <a href="profile.php?userID=<?php echo $row[1] ?>"  class="button-nopadding six">@<?php echo $viewingUser['username'] ?>
                                                            </a>
                                                            <?php
                                                            if ($row[6] == 0) {
                                                                ?>
                                                            <?php } else if ($row[6] == 1) { ?>
                                                                edited <?php echo time_elapsed_string($row[7]) ?>
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

                                                            $processLike = "process_like.php?postID=" . $row[0];
                                                        }
                                                        ?>
                                                        <a href="<?php echo $processLike ?>" class="button four d-inline-block mt-2 nav-link d-flex align-items-center" aria-label="Like this post">
                                                            <?php
                                                            $likes = getLikesForPost($row[0]);
                                                            $likeOrLikes = ($likes == 1) ? "Like" : "Likes";
                                                            if ($sessionUserID == -1) {
                                                                echo '<span class="material-icons">favorite_border</span>';
                                                            } else {

                                                                echo checkIfLiked($row[0], $_SESSION['userID']);
                                                            }
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
                                                </div>
                                            </article>

                                            <?php
                                            $comments = getCommentsForPost($row[0]);
                                            if ($comments == "No post") {
                                                
                                            } else {
                                                ?>
                                                <div class="post-user-comment-box">
                                                    <?php
                                                    if ($commentCount > 3) {
                                                        ?>
                                                        <div class='d-flex justify-content-between'>
                                                            <a href="viewPost.php?postID=<?php echo $row[0] ?>" class='text-center' >View More comments</a>
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
                                                            <a href="profile.php?userID=<?= $currCommentingUser['userID'] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/' . $currCommentingUser['profilePic'] ?>" alt="Generic placeholder image"></a>
                                                            <div class="w-100">
                                                                <a href="profile.php?userID=<?php echo $currCommentingUser['userID'] ?>" class="button-nopadding six"><?php echo $currCommentingUser['fname'] . ' ' . $currCommentingUser['lname'] ?></a><small class="text-muted"> <?php echo time_elapsed_string($commentRows[4]) ?></small><br>
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
                                                        $commentProcess = "process_comment.php?postID=" . $row[0] . "&userID=" . $row[1] . "&redirectTo=" . 0;
                                                        $placeHolderPic = 'images/' . $sessionUser['profilePic'];
                                                    }
                                                    ?>
                                                    <div class="d-flex align-items-start mt-2">
                                                        <a class="pe-2" href="profile.php?userID="<?php echo $viewingUser['userID'] ?>>
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
    </main>
    <?php
    include "footer.inc.php"
    ?>
</body>
</html>
