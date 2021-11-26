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
        <a class="navbar-brand" href="index.php" class='logo'>stori</a>
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
                    $notificationsList = getNotification($_SESSION['userID']);
                    $notificationCount = getNotificationCount($_SESSION['userID']);
                    ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-icons">
                                notifications
                            </span>
                            <span class="notificationBadge"> <?php echo $notificationCount ?></span>
                        </a>
                        <ul class="dropdown-menu notification-box" aria-labelledby="navbarDropdown">
                            <?php
                            if ($notificationCount == 0) {
                                ?>
                                <li class="nav-item">
                                    <p class="dropdown-item d-flex flex-row align-items-center">
                                        <span class="small"> No notifications!</span>
                                    </p>
                                </li>
                                <?php
                            } else {
                                $notificationUserID = -1;
                                $notificationUser;
                                ?>

                                <li class="nav-item">

                                    <a class="dropdown-item d-flex flex-row align-items-center" href="process_clearNotifications.php">
                                        <span class="material-icons">
                                            clear_all
                                        </span>
                                        <span class="small"> Clear all notifications</span>
                                    </a>
                                </li>
                                <?php
                                while ($notification = $notificationsList->fetch_array(MYSQLI_NUM)) {
                                    if ($notificationUserID == -1) {
                                        $notificationUserID = $notification[1];
                                        $notificationUser = getUserFromID($notificationUserID);
                                    } else if ($notificationUserID != $notification[1]) {
                                        $notificationUserID = $notification[1];
                                        $notificationUser = getUserFromID($notificationUserID);
                                    }
                                    ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="nav-item">
                                        <?php
                                        $href = "";
                                        switch ($notification[4]):
                                            case 0:
                                                $href = "viewPost.php?postID=" . $notification[6];
                                                break;
                                            case 1:
                                                $href = "viewPost.php?postID=" . $notification[6];
                                                break;
                                            case 2:
                                                $href = "profile.php?userID=" . $notification[5];
                                                break;
                                            case 3:
                                                $href = "viewPost.php?postID=" . $notification[6];
                                                break;
                                            default:

                                        endswitch;
                                        ?>
                                        <div class="d-flex">
                                            <a href="process_deleteNotification.php?notificationID=<?php echo $notification[0] ?>" class ="align-self-center">
                                                <span class="material-icons">
                                                    clear
                                                </span>
                                            </a>
                                            <div class="flex-grow-1">
                                                <a href="profile.php?userID=<?php echo $notification[5] ?>">@<?php echo $notificationUser["username"] ?></a> 
                                                <br>
                                                <?php echo $notification[2] ?>
                                            </div>
                                            <a href="<?php echo $href ?>" class ="align-self-center">
                                                <span class="material-icons">
                                                    chevron_right
                                                </span>
                                            </a>
                                        </div>

                                    </li>

                                    <?php
                                }
                            }
                        }
                        ?>
                    </ul>
                </li>
            </ul>

            <form class="d-flex" action="process_search.php" method="post">
                <div class="input-group" >
                    <input type="text" class="form-control" placeholder="Search..." name = "query" id = "query" required>
                    <button class="btn btn-outline-success" type="submit">
                        <span class="material-icons">
                            search
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>
