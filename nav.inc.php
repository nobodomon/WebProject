<?php
session_start();
include "db.php";
$user = getUserFromID($_SESSION['userID']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="images/logo.png" class="logo" alt="logo"/></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navBarToggler" aria-controls="navBarToggler" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navBarToggler">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <form class="d-flex" action="process_search.php" method="post">
                <div class="input-group" >
                    <input type="text" class="form-control" placeholder="Search..." name = "query" id = "query">
                    <button type="submit" class="btn btn-secondary"><span class="material-icons">
                            search
                        </span></button>
                </div>
            </form>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if (empty($_SESSION["userID"])) {
                ?>

                <li>
                    <a class="nav-link" href="login.php">
                        <span class="material-icons">login</span>Login
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="register.php">
                        <span class="material-icons">emoji_people</span>Register
                    </a>
                </li>
                <?php
            } else {
                ?>

                <li>
                    <a class="nav-link" href="createPost.php">
                        <span class="material-icons">mode_edit</span>Create Post
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="profile.php?userID=<?php echo $_SESSION['userID']?>">
                        <span class="material-icons">account_circle</span>My Profile
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="logout.php">
                        <span class="material-icons">logout</span>logout
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>
