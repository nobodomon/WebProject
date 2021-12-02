<!doctype html>
<html lang="en">
    <?php
    include 'head.inc.php';
    // get the categoryID
    $categoryID = $_GET["categoryID"];
    ?>
    <body>
        <?php
        include 'nav.inc.php';
        // get posts based on category
        $postCountBasedOnCategory = getPostCountBasedOnCategoryID($categoryID);
        $userCountBasedOnCategory = getUserCountBasedOnCategoryID($categoryID);
        $categoryObject = getCategoryNameBasedOnCategoryID($categoryID);
        $postResults = getPostBasedOnCategoryID($categoryID);
        $userResults = getUsersBasedOnCategoryID($categoryID);
        if (!isset($_SESSION["userID"])) {
            $sessionUserID = -1;
        } else {
            $sessionUserID = $_SESSION["userID"];
        }
        ?>
        <main class ="container">
            <!-- Tabs navs -->
            <section aria-labelledBy="search header" class="d-flex justify-content-between">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Related Tags</li>
                    </ol>
                </nav>
                <!--change to category Name-->

                <span class="text-center flex-grow-1 button-looking-text-big"> You are viewing posts and users related to: <?php echo $categoryObject ?></span>
            </section>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item " role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Related Users (<?php echo $userCountBasedOnCategory ?>)</button>
                </li>
                <li class="nav-item " role="presentation">
                    <button class="nav-link" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-controls="posts" aria-selected="true">Related Post (<?php echo $postCountBasedOnCategory ?>)</button>
                </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="myTabContent">
                <section class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <?php
                    if ($userCountBasedOnCategory > 0) {
                        while ($userRows = $userResults->fetch_array(MYSQLI_NUM)) {
                            ?>
                            <article class="card mt-3">
                                <div class="card-body d-flex align-items-center">
                                    <a href="profile.php?userID=<?= $userRows[2] ?>"><img class="me-2 avatar-md rounded-circle" src="<?= 'images/profilepics/' . $userRows[9] ?>" alt="Generic placeholder image"></a>
                                    <div class="d-flex flex-grow-1 mr-3">
                                        <div class="flex-column">
                                            <a href="profile.php?userID=<?= $userRows[2] ?>" class="button-nopadding six">@<?php echo $userRows[3] ?></a>
                                            <p class="card-text">
                                                <?php echo $userRows[4] . " " . $userRows[5] ?></p>
                                            <div class="mt-3">

                                                <?php if (getInterestCount($userRows[2]) > 0) { ?>
                                                    <p class="text-muted mb-2 font-13"><strong>Interests :</strong> 
                                                        <?php
                                                        $interests = getCategoriesOfUser($userRows[2]);

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
                                    </div>
                                    <a href="profile.php?userID=<?php echo $userRows[3] ?>"class="button three d-flex flex-nowrap">View user</a>
                                </div>

                            </article>
                            <?php
                        }
                    } else {
                        ?>
                        <article class="card mt-3">
                            <div class="card-body d-flex justify-content-evenly">
                                <div class="w-100">
                                    <h5 class="text-center">No Users found</h5>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                    ?>
                </section>
                <section class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                    <?php
                    if ($postCountBasedOnCategory > 0) {
                        $authorID = -1;
                        $author;
                        while ($postRows = $postResults->fetch_array(MYSQLI_NUM)) {
                            $author = getUserFromID($postRows[3]);
                            $followed = checkIfFollowed($postRows[3], $sessionUserID);
                            $subscribed = checkIfSubscribed($postRows[3], $sessionUserID);
                            $postPrivacy = $postRows[7];
                            if ($authorID == -1) {
                                $authorID = $postRows[3];
                                $author = getUserFromID($authorID);
                                $followed = checkIfFollowed($authorID, $sessionUserID);
                                $subscribed = checkIfSubscribed($authorID, $sessionUserID);
                            } else if ($authorID != $postRows[3]) {
                                $authorID = $postRows[3];
                                $author = getUserFromID($authorID);
                                $followed = checkIfFollowed($authorID, $sessionUserID);
                                $subscribed = checkIfSubscribed($authorID, $sessionUserID);
                            }
                            $renderPost = false;
                            if ($sessionUserID == $authorID) {
                                $renderPost = true;
                            } else if ($postPrivacy == 0) {
                                $renderPost = true;
                            } else if ($postPrivacy == 1 && $followed) {
                                $renderPost = true;
                            } else if ($postPrivacy == 2 && $subscribed) {
                                $renderPost = true;
                            } else {
                                $renderPost = false;
                            }

                            if ($renderPost) {
                                ?>
                                <article class="card mt-3 searchResult">
                                    <div class="card-body d-flex align-items-center">
                                        <a href="profile.php?userID=<?= $postRows[3] ?>"><img class="me-2 avatar-md rounded-circle" src="<?= 'images/profilepics/' . $author['profilePic'] ?>" alt="Generic placeholder image">
                                            <div class="d-flex flex-grow-1 mr-3">
                                                <div class="flex-column">
                                                    <a href="viewPost.php?postID=<?= $postRows[2] ?>" class="button-nopadding six">
                                                        <?php echo $postRows[4] ?>
                                                    </a>
                                                    <small class="text-muted">
                                                        <?php echo time_elapsed_string($postRows[6]) ?>
                                                    </small>
                                                    <?php if (getPostTagCount($postRows[2]) > 0) { ?>
                                                        <div class="mt-3">
                                                            <p class="text-muted mb-2 font-13"><strong>Tags :</strong>
                                                                <?php
                                                                $tags = getInterestByPostID($postRows[2]);

                                                                while ($tag = $tags->fetch_array(MYSQLI_NUM)) {
                                                                    ?>
                                                                    <a href="process_category.php?categoryID=<?php echo $tag[1] ?>" class="tags">
                                                                        <span class="badge rounded-pill bg-dark"><?php echo $tag[3] ?></span>
                                                                    </a>
                                                                    <?php
                                                                }
                                                                ?></p>


                                                        </div>     
                                                    <?php } else { ?>
                                                        <div class="mt-3">
                                                            <p class="text-muted mb-2 font-13"><strong>Tags: No Tags</strong></p>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <a href="viewPost.php?postID=<?php echo $postRows[2] ?>" class="button three d-flex flex-nowrap">View Post</a>
                                    </div>
                                </article>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <article class="card mt-3">
                            <div class="card-body d-flex justify-content-evenly">
                                <div class="w-100">
                                    <h5 class="text-center">No posts found</h5>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                    ?>
                </section>
            </div>
            <!-- Tabs content -->
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>