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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php" class='logo'>
            <img src="images/favicon.png" alt="logo" width="30" height="30" class="d-inline-block align-text-top">
            stori
        </a>
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
                            
                            <li><hr class="dropdown-divider"></li>
                            <li class="nav-item">
                                <a class="dropdown-item d-flex flex-row align-items-center" href="login.php">
                                    <span class="material-icons">login</span><span class="small"> Login</span>
                                </a>
                            </li>
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
                        <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                                        $notificationUserID = $notification[5];
                                        $notificationUser = getUserFromID($notificationUserID);
                                    } else if ($notificationUserID != $notification[5]) {
                                        $notificationUserID = $notification[5];
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
                                                $href = "profile.php?userID=" . $notification[5];
                                                break;
                                            default:

                                        endswitch;
                                        ?>
                                        <div class="d-flex">
                                            <a href="process_deleteNotification.php?notificationID=<?php echo $notification[0] ?>" class ="d-flex deleteNoti button-nopadding">
                                                <span class="material-icons align-self-center">
                                                    clear
                                                </span>
                                            </a>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <div class="d-flex flex-grow-1 notiHeader align-items-center">
                                                    <a href="profile.php?userID=<?php echo $notification[5] ?>" class="button-nopadding small">@<?php echo $notificationUser["username"] ?></a> 
                                                    <p class="notiTimeStamp flex-grow-1 align-self-stretch"><?php echo time_elapsed_string_short($notification[3]) ?></p>
                                                </div>
                                                <span><?php echo $notification[2] ?></span>
                                            </div>
                                            <a href="<?php echo $href ?>" class ="align-self-center button-nopadding">
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
            <form class="d-flex" action="process_search.php" method="post" aria-label="search box">
                <div class="input-group" >
                    <input type="text" class="form-control" placeholder="Search..." name = "query" id = "query" required role="searchbox">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>
