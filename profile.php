<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->


<html>
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
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if ($userID == $_SESSION['userID']) {
                                ?> 
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
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
                                    <p class="text-muted"><?php echo $viewingUser['username'] ?></p>
                                    <button type="button" class="btn btn-soft-primary btn-xs waves-effect mb-2 waves-light">Follow</button>
                                    <button type="button" class="btn btn-soft-success btn-xs waves-effect mb-2 waves-light">Subscribe</button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h4 class="font-13 text-uppercase">About Me :</h4>
                                <p class="text-muted font-13 mb-3">
                                    To be implemented, short desc.
                                </p>
                                <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2"><?php echo $viewingUser['fname'] . ' ' . $viewingUser['lname']; ?></span></p>
                            </div>                                    

                            <ul class="social-list list-inline mt-3 mb-0">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item text-center border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item text-center border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item text-center border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item text-center border-secondary text-secondary"><i class="mdi mdi-github"></i></a>
                                </li>
                            </ul>   
                        </div>                                 
                    </div> <!-- end card -->

                    <!--To implement follower and subscriber count-->
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6 border-end border-light">
                                    <h5 class="text-muted mt-1 mb-2 fw-normal">Followers</h5>
                                    <h2 class="mb-0 fw-bold">0</h2>
                                </div>
                                <div class="col-6 border-end border-light">
                                    <h5 class="text-muted mt-1 mb-2 fw-normal">Subscribers</h5>
                                    <h2 class="mb-0 fw-bold">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col-->

                <div class="col-xl-7">
                    <div class="card">
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
                                        <textarea rows="3" class="form-control" id="content" name="content" placeholder="Write something..."></textarea>
                                    </span>
                                    <div class="comment-area-btn">
                                        <div class="float-end">
                                            <button type="submit" class="btn btn-sm btn-dark waves-effect waves-light">Post</button>
                                        </div>
                                        <div>
                                            <a href="#" class="btn btn-sm btn-light text-black-50"><i class="far fa-user"></i></a>
                                            <a href="#" class="btn btn-sm btn-light text-black-50"><i class="fa fa-map-marker-alt"></i></a>
                                            <a href="#" class="btn btn-sm btn-light text-black-50"><i class="fa fa-camera"></i></a>
                                            <a href="#" class="btn btn-sm btn-light text-black-50"><i class="far fa-smile"></i></a>
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>

                            <!-- end comment box -->

                            <!-- Story Box-->
                            <div class="border border-light p-2 mb-3">
                                <?php
                                if ($posts == "No post") {
                                    echo "<p> No posts! Try creating one </p>";
                                } else {

                                    while ($row = $posts->fetch_array(MYSQLI_NUM)) {
                                        ?>

                                        <div class="d-flex align-items-start">
                                            <img class="me-2 avatar-sm rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="Generic placeholder image">
                                            <div class="w-100">
                                                <h5 class=""><?php echo $row[2] ?><small class="text-muted"><?php echo $row[4] ?></small></h5>
                                                <p class="card-text"></p>
                                                <div class="">
                                                    <?php echo $row[3] ?>
                                                    <br>
                                                    <a href="javascript: void(0);" class="text-muted font-13 d-inline-block mt-2"><i class="mdi mdi-reply"></i> Reply</a>
                                                </div>
                                                <a href="#" class="btn btn-primary">View Post</a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div> <!-- end card-->

                    </div> <!-- end col -->
                </div>
                <!-- end row-->

            </div>
        </div>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>
