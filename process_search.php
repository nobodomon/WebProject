<!doctype html>
<html lang="en">
    <?php
    include 'head.inc.php';
    $query = $_POST["query"];
    ?>
    <body>
        <?php
        include 'nav.inc.php';
        $userCount = searchUserNameCount($query);
        $postCount = searchPostCount($query);
        ?>
        <main class ="container">
            <!-- Tabs navs -->
            <section aria-labelledBy="search header" class="d-flex justify-content-between">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.php">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Search Results</li>
                    </ol>
                </nav>
                <h2 class="text-center flex-grow-1"> Your search for: <?php echo $query ?></h2>
            </section>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Users (<?php echo $userCount ?>)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Related Post (<?php echo $postCount ?>)</button>
                </li>
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="myTabContent">
                <section class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <?php
                    $userSearchSuccess;
                    $userSearchErrorMsg;
                    $userResults = getUserByUserName($query);
                    $postSearchSuccess;
                    $postSearchErrorMsg;
                    $postResults = getPostsRelatedToQuery($query);
                    if ($userSearchSuccess == true) {
                        if ($userResults->num_rows > 0) {
                            while ($userRows = $userResults->fetch_array(MYSQLI_NUM)) {
                                ?>
                                <article class="card mt-3">
                                    <div class="card-body d-flex align-items-start">
                                        <a href="profile.php?userID=<?=$userRows[0]?>"><img class="me-2 avatar-sm rounded-circle" src="<?='images/'.$userRows[8]?>" alt="Generic placeholder image"></a>
                                        <div class="flex-grow-1">
                                            <a href="profile.php?userID=<?=$userRows[0]?>" class="button-nopadding six"><?php echo $userRows[1] ?></a>
                                            <p class="card-text">
                                                <?php echo $userRows[2] . " " . $userRows[3] ?></p>
                                        </div>
                                        <a href="profile.php?userID=<?php echo $userRows[0] ?>"class="button three">View user</a>
                                    </div>
                                </article>
                                <?php
                            }
                        } else {
                            ?>
                            <article class="card mt-3">
                                <div class="card-body d-flex justify-content-evenly">
                                    <div class="w-100">
                                        <h5 class="text-center">No user found</h5>
                                    </div>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        ?>
                        <article class="card mt-3">
                            <div class="card-body d-flex justify-content-evenly">
                                <div class="w-100">
                                    <h5 class="">The following errors were detected:</h5>
                                    <p class="card-text text-center">
                                        <?php echo $userSearchErrorMsg ?>
                                    </p>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                    ?>

                </section>
                <section class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <?php
                    if ($postSearchSuccess == true) {
                        if ($postResults->num_rows > 0) {

                            $authorID = -1;
                            $author;
                            while ($postRows = $postResults->fetch_array(MYSQLI_NUM)) {
                                if ($authorID == -1) {
                                    $authorID = $postRows[1];
                                    $author = getUserFromID($postRows[1]);
                                } else if ($authorID != $postRows[1]) {
                                    $authorID = $postRows[1];
                                    $author = getUserFromID($postRows[1]);
                                }
                                ?>
                                <article class="card mt-3">
                                    <div class="card-body d-flex align-items-start">
                                        <a href="profile.php?userID=<?= $postRows[1] ?>"><img class="me-2 avatar-sm rounded-circle" src="<?= 'images/' . $author['profilePic'] ?>" alt="Generic placeholder image">
                                            <div class="flex-grow-1">
                                                <a href="viewPost.php?postID=<?= $postRows[0] ?>" class="button-nopadding six"><?php echo $postRows[2] ?></a> <small class="text-muted"><?php echo time_elapsed_string($postRows[4]) ?></small>
                                                <p class="card-text">
                                                    <?php echo $postRows[3] ?></p>
                                            </div>
                                            <a href="viewPost.php?postID=<?php echo $postRows[0] ?>" class="button three">View Post</a>
                                    </div>
                                </article>
                                <?php
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
                    } else {
                        ?>
                        <article class="card mt-3">
                            <div class="card-body d-flex justify-content-evenly">
                                <div class="w-100">
                                    <h5 class="text-center">The following errors were detected:</h5>
                                    <p class="card-text text-center">
                                        <?php echo $userSearchErrorMsg ?>
                                    </p>
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