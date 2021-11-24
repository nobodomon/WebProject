<!doctype html>
<html lang="en">
    <?php
    include 'head.inc.php';
    $query = $_POST["query"];
    ?>
    <body>
        <?php
        include 'nav.inc.php';
        ?>
        <main class ="container">
            <!-- Tabs navs -->
            <h2> Your search for: <?php echo $query ?></h2>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Users</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Related Post</button>
                </li>
            </ul>
            <!-- Tabs navs -->
            
            <!-- Tabs content -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <?php
                    $userResults = getUserByUserName($query);
                    $postResults = getPostsRelatedToQuery($query);
                    while ($userRows = $userResults->fetch_array(MYSQLI_NUM)) {
                        ?>

                        <div class="d-flex align-items-start">
                            <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="Generic placeholder image">
                            <div class="w-100">
                                <h5 class=""><?php echo $userRows[1] ?></h5>
                                <p class="card-text">
                                    <?php echo $userRows[2] . " " . $userRows[3] ?></p>
                                <a href="profile.php?userID=<?php echo $userRows[0]?>"class="btn btn-primary">View user</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <?php
                    while ($postRows = $postResults->fetch_array(MYSQLI_NUM)) {
                        ?>

                        <div class="d-flex align-items-start">
                            <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="Generic placeholder image">
                            <div class="w-100">
                                <h5 class=""><?php echo $postRows[2] ?><small class="text-muted"><?php echo time_elapsed_string($row[4]) ?></small></h5>
                                <p class="card-text">
                                    <?php echo $postRows[3] ?></p>
                                <a href="#" class="btn btn-primary">View Post</a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <!-- Tabs content -->
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>