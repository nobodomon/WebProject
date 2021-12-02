<html lang="en">
    <?php include 'process_editprofilepicture.php' ?>
    <link rel="stylesheet" href="css/profilepage.css"/>
    <?php
    include "head.inc.php";

    if (!isset($_SESSION['userID'])) {
        header("Location: index.php");
        die();
    }
    ?>
    <body>
        <?php
        include "nav.inc.php";
        // get current user details
        $userID = $_SESSION['userID'];
        $user = getUserFromID($userID);
        ?>

        <main class="container-fluid mb-5">
            <div class="rounded d-flex justify-content-center">
                <div class=" col-lg-6 col-md-8 col-sm-8 shadow-lg p-4 bg-light">
                    <div class="text-center">
                        <h3 class="text-primary">Profile Picture Selection</h3>
                    </div>
                    <?php if (!empty($msg)): ?>
                        <div class ="alert <?php echo $css_class; ?>">
                            <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>
                    <form action="process_editprofilepicture.php" method="post" enctype="multipart/form-data">
                        <div class="col-4 offset-md-4 form-div"> 
                            <div class="form-group text-center mb-3">
                                <img src="<?= 'images/profilepics/'. $user['profilePic']?>" alt = "profile picutre" onclick="click()" id="profileDisplay" />
                                <label class="mt-2 mb-2" for="profileImage"> Profile Image </label>
                                <input class="form-control" type="file" id="profileImage" onchange="displayImage(this)" name="profileImage">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary  w-100" type="submit" name="save-user">
                                    Save picture
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </main>
        <script src="js/profilepic.js"></script>

    </body>
    <?php
    include "footer.inc.php"
    ?>
</html>