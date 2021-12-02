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
                            <h3 class="text-primary">Change Password</h3>
                        </div>
                        <form action="process_changePassword.php" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-key text-white"></i></span>
                                    <br>
                                    <input type="password" id="oldPassword" name="oldPassword" required placeholder="Old password" class="form-control" >
                                    <span class="input-group-text bg-primary text-white"><i class="bi bi-eye-slash" id="oldTogglePassword"></i></span>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-key text-white"></i></span>
                                    <br>
                                    <input type="password" id="newPassword" name="newPassword" required placeholder="New password" class="form-control" >
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="bi bi-eye-slash" id="newTogglePassword"></i></span>

                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-key text-white"></i></span>
                                    <br>
                                    <input type="password" id="confirmNewPassword" name="confirmNewPassword" required placeholder="Confirm new password" class="form-control" >
                                    <span class="input-group-text bg-primary text-white"><i class="bi bi-eye-slash" id="confirmNewTogglePassword"></i></span>

                                </div>

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
