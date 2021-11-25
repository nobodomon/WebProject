<?php include 'process_editprofilepicture.php'?>
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
        ?>

        <main class="container-fluid vh-100" style="margin-top:100px">
            <div class="" style="margin-top:100px">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Profile Picture Selection</h3>
                        </div>
                        <?php if (!empty($msg)): ?>
                            <div class ="alert <?php echo $css_class; ?>">
                                <?php echo $msg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="editprofilepicture.php" method="post" enctype="multipart/form-data">
                            <div class="col-4 offset-md-4 form-div"> 
                                <div class="form-group">
                                    <label for="profileImage"> Profile Image </label>
                                    <input class="form-control" type="file" id="profileImage" name="profileImage">
                                </div>
                                
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-key-fill text-white"></i> Biography</span>
                                    <input class="form-control" type="text" id="biography" name="biography" value="<?php echo $userResults['biography']; ?>">
                                </div>
                                <button class="btn btn-primary text-center mt-2" type="submit" name="save-user">
                                    Save picture
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
