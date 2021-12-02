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
        $categoriesResults = getAllCategories();
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
                            $subBtn = "<a href='process_subscribe.php?subscriberID=" . $userID . "' class='button three'>Unsubscribe</a>";
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
                            ?>
                            <h3>Confirm Subscription?</h3>
                            <p>Subscribing to <?= "@" . $viewingUser['username'] ?> will allow you to see exclusive subscriber only posts. Subscription Fee will be $10.</p>
                            <form method="post" action="process_subscribe.php?subscriberID=<?php echo $userID ?>">
                                    <label for="cardName" class="mt-2 mb-2 button-looking-text">Name on card:</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-primary text-white"><i
                                                class="bi bi-person-circle text-white"></i></span>
                                        <input class="form-control" required type="text" id="cardName" name="cardName" placeholder="Enter Name on card">
                                    </div>

                                    <label for="ccNo" class="mt-2 mb-2 button-looking-text">Credit Card Number:</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-primary text-white"><i class="bi bi-credit-card-2-back-fill"></i></span>
                                        <input class="form-control" required type="number" inputmode="numeric" id="ccNo" pattern="[0-9]*" name="ccNo" placeholder="1111-2222-3333-4444">
                                    </div>

                                    <label for="expiryMonth" class="mt-2 mb-2 button-looking-text">Expiry month:</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-primary text-white"><i class="bi bi-calendar2-month"></i></span>
                                        <select class="form-control" required type="text" id="expiryMonth" min="1" max="12" name="expiryMonth" placeholder="Expiry month">
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
                                    <label for="expiryYear" class="mt-2 mb-2 button-looking-text">Expiry year:</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-primary text-white"><i class="bi bi-calendar2"></i></span>
                                        <select class="form-control" required id="expiryYear" name="expiryYear" placeholder="Expiry year">
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

                                    <label for="ccv" class="mt-2 mb-2 button-looking-text">CCV:</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text bg-primary text-white"><i class="bi bi-credit-card-2-back"></i></span>
                                        <input class="form-control" required type="number" id="ccv" name="ccv" pattern="[0-9]*" placeholder="CCV on card">
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Subscribe</button>
                                </div>
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
                                    <a href="profile.php?userID=<?= $follower[0] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/profilepics/' . $follower[7] ?>" alt="Generic placeholder image"></a>
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
                                    <a href="profile.php?userID=<?= $followingUser[0] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/profilepics/' . $followingUser[7] ?>" alt="Generic placeholder image"></a>
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
                                    <a href="profile.php?userID=<?= $subscriber[0] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?php echo 'images/profilepics/' . $subscriber[7] ?>" alt="Generic placeholder image"></a>
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
                                        <a href="editprofilepicture.php" class="dropdown-item">Change Profile Picture</a>
                                        <a href="changePassword.php" class="dropdown-item">Change Password</a>
                                        <!-- item-->
                                        <a href="logout.php" class="dropdown-item">Logout</a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="d-flex align-items-start">
                                <img src="<?php echo 'images/profilepics/' . $viewingUser['profilePic'] ?>" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
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

                                <?php if (getInterestCount($sessionUserID) > 0) { ?>
                                    <p class="text-muted mb-2 font-13"><strong>Interests :</strong> 
                                        <?php
                                        $interests = getCategoriesOfUser($userID);

                                        while ($interest = $interests->fetch_array(MYSQLI_NUM)) {
                                            $categoryID = $interest[2];
                                            ?>

                                            <a href="process_category.php?categoryID=<?php echo $categoryID ?>" class="tags">
                                                <span class="badge rounded-pill bg-dark"><?php echo $interest[3] ?></span>
                                            </a>

                                            <?php
                                        }
                                        ?>

                                    <?php } else { ?>
                                    <div class="mt-3">
                                        <p class="text-muted mb-2 font-13"><strong>Interests : No Interests</strong></p>
                                    </div>
                                <?php } ?>     

                            </div>  
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
                    </section><section class="card">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-12 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Total Posts</h5>
                                    <span class="button-looking-text-big"><?php echo getPostCountsByUser($userID,3) ?></span>
                                </div>
                                <hr class="my-2">
                                <div class="col-4 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Public posts</h5>
                                    <span class="button-looking-text-big"><?php echo getPostCountsByUser($userID,0) ?></span>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Follower posts</h5>
                                        <span class="button-looking-text-big"><?php echo getPostCountsByUser($userID,1) ?></span>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <h6 class="text-muted mt-1 mb-2 fw-normal">Exclusive posts</h5>
                                        <span class="button-looking-text-big"><?php echo getPostCountsByUser($userID,2) ?></span>
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
                                include("resources/templates/createpostwidget.php");
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
                                    $postID = $row[0];
                                    $author_id = $row[1];
                                    $title = $row[2];
                                    $content = $row[3];
                                    $postedDateTime = $row[4];
                                    $postPrivacy = $row[5];
                                    $edited = $row[6];
                                    $editedDateTime = $row[7];
                                    $postAuthor = getUserFromID($author_id);
                                    if (($userID == $sessionUserID) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed) || ($postPrivacy == 2 && $subscribed)) {
                                        include("resources/templates/post.php");
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
