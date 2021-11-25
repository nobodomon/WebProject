<?php
include "htmLawed.php";
include "db.php";
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);
if (!isset($_SESSION)) {

    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                if (empty($_SESSION["userID"])) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="index.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Login/Register
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="nav-item">
                                <a class="dropdown-item d-flex flex-row align-items-center" href="register.php">
                                    <span class="material-icons">emoji_people</span><span class="small"> Register</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item d-flex flex-row align-items-center" href="login.php">
                                    <span class="material-icons">login</span><span class="small"> Login</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <?php
                } else {
                    include "db.php";
                    $user = getUserFromID($_SESSION['userID']);
                    ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-row align-items-center" href="createPost.php">
                            <span class="material-icons">mode_edit</span><span class="small"> Create Post</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-row align-items-center" href="profile.php?userID=<?php echo $_SESSION['userID'] ?>">
                            <span class="material-icons">account_circle</span><span class="small"> My Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex flex-row align-items-center" href="logout.php">
                            <span class="material-icons">logout</span><span class="small"> Logout</span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <form class="d-flex" action="process_search.php" method="post">
                <div class="input-group" >
                    <input type="text" class="form-control" placeholder="Search..." name = "query" id = "query" required>
                    <button class="btn btn-outline-success" type="submit"><span class="material-icons">
                            search
                        </span></button>
                </div>
            </form>
        </div>
    </div>
</nav>
