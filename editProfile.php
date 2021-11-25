<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php";
        // get current user details
        $userResults = getUserFromID($_SESSION['userID']);
        $categoriesResults = getAllCategories();
        ?>

        <main class="container-fluid vh-100" style="margin-top:100px">
            <div class="" style="margin-top:100px">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Edit Profile</h3>
                        </div>
                        <form action="process_editProfile.php" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-person text-white"></i>  First Name</span>
                                    <input class="form-control" type="text" id="fname" name="fname" value="<?php echo $userResults['fname']; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-person text-white"></i>  Last Name</span>
                                    <input class="form-control" type="text" id="lname" name="lname" value="<?php echo $userResults['lname']; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-person text-white"></i>  Username</span>
                                    <input class="form-control" type="text" id="username" name="username" value="<?php echo $userResults['username']; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-key-fill text-white"></i>  Biography</span>
                                    <input class="form-control" type="text" id="biography" name="biography" value="<?php echo $userResults['biography']; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <a href="editprofilepicture.php"><span class="text-primary">Change Profile Picture</span></a>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-bookmark-heart"></i>  Interests</span>
                                </div>
                                <?php while($row = $categoriesResults -> fetch_array(MYSQLI_NUM)){ ?>
                                    <input type="checkbox" class="btn-check" id="btn-check" autocomplete="off" />
                                    <label class="btn btn-outline-dark" for="btn-check" value="<?php echo $row[1]; ?>"></label>
                                <?php } ?>

                                <br>
                                <button class="btn btn-primary text-center mt-2" type="submit">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>


    </body>
    <?php
    include "footer.inc.php"
    ?>
</html>
