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
            $userInterests = getUserInterestCategories($_SESSION['userID']);
            $interestFound = false;
        ?>

        <main class="container-fluid vh-100" style="margin-top:100px">
            <div class="" style="margin-top:100px">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Edit Profile</h3>
                        </div>
                        <form action="process_payment.php" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-person text-white"></i>  Card Name</span>
                                    <input class="form-control" type="text" id="fname" name="fname">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-card-heading text-white"></i>  Card Number</span>
                                    <input class="form-control" type="text" id="lname" name="lname">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary text-white"><i
                                            class="bi bi-card-heading text-white"></i>  CVV</span>
                                    <input class="form-control" type="text" id="username" name="username">
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
