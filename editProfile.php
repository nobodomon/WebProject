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
        ?>

        <main class="container-fluid mb-5">
            <div class="rounded d-flex justify-content-center">
                <div class=" col-lg-6 col-md-8 col-sm-8 shadow-lg p-5 bg-light mb-3">
                    <div class="text-center">
                        <h3 class="text-primary">Edit Profile</h3>
                    </div>
                    <form action="process_editProfile.php" method="post">
                        <div class="p-1">
                            <label for="fname" class="mt-2 mb-2 button-looking-text">First name:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" maxlength="45" onkeyup="registrationCharacterCount('fname','fname-label',45)" id="fname" name="fname" value="<?php echo $userResults['fname']; ?>">
                                <span class="btn btn-dark text-white" id="fname-label"><?=strlen($userResults['fname'])?>/45</span>
                            </div>
                            <label for="lname" class="mt-2 mb-2 button-looking-text">Last Name:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" maxlength="45" onkeyup="registrationCharacterCount('lname','lname-label',45)" id="lname" name="lname" value="<?php echo $userResults['lname']; ?>">
                                <span class="btn btn-dark text-white" id="lname-label"><?=strlen($userResults['lname'])?>/45</span>
                            </div>
                            <label for="username" class="mt-2 mb-2 button-looking-text">Username:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" maxlength="45" onkeyup="registrationCharacterCount('username','username-label',45)" id="username" name="username" value="<?php echo $userResults['username']; ?>">
                                <span class="btn btn-dark text-white" id="username-label"><?=strlen($userResults['username'])?>/45</span>
                            </div>
                            
                            <label for="username" class="mt-2 mb-2 button-looking-text">Email:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-envelope text-white"></i></span>
                                <input class="form-control" type="email" maxlength="45" onkeyup="registrationCharacterCount('email','email-label',45)"  id="email" name="email" value="<?php echo $userResults['email']; ?>">
                                <span class="btn btn-dark text-white" id="email-label"><?=strlen($userResults['email'])?>/45</span>
                            </div>
                            
                            <label for="username" class="mt-2 mb-2 button-looking-text">Confirm email:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-envelope text-white"></i></span>
                                <input class="form-control" type="email" maxlength="45" onkeyup="registrationCharacterCount('confirm-email','confirm-email-label',45)"  id="confirm-email" name="confirm-email" value="<?php echo $userResults['email']; ?>">
                                <span class="btn btn-dark text-white" id="confirm-email-label"><?=strlen($userResults['email'])?>/45</span>
                            </div>
                            
                            <label for="biography" class="mt-2 mb-2 button-looking-text">Biography:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" maxlength="512" onkeyup="registrationCharacterCount('biography','biography-label',512)"  id="biography" name="biography" value="<?php echo $userResults['biography']; ?>">
                                <span class="btn btn-dark text-white" id="biography-label"><?=strlen($userResults['biography'])?>/512</span>
                            </div>
                            <label class="mt-2 mb-2 button-looking-text">Your interests:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-bookmark-heart"></i></span>
                                <input class="flex-grow-1 form-control" type="text" id="searchTagBox" onkeyup="searchUpdate()" placeholder="search for tag...">
                            </div>
                            <div class="d-flex interestTagGrp p-2" id="interestTagGrp">
                                <?php
                                $userInterestsID = array();
                                while ($row1 = $userInterests->fetch_array(MYSQLI_NUM)) {
                                    array_push($userInterestsID, $row1[1]);
                                }
                                while ($row = $categoriesResults->fetch_array(MYSQLI_NUM)) {

                                    if (in_array($row[0], $userInterestsID)) {
                                        ?>
                                        <input type="checkbox" class="btn-check m-1" name="interest[]" id="btn-check<?php echo $row[0] ?>" checked autocomplete="off" value="<?php echo $row[0] ?>"/>
                                        <label class="btn btn-outline-dark m-1" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1]; ?></label>
                                    <?php } else { ?>
                                        <input type="checkbox" class="btn-check m-1" name="interest[]" id="btn-check<?php echo $row[0] ?>" autocomplete="off" value="<?php echo $row[0] ?>"/>
                                        <label class="btn btn-outline-dark m-1" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1]; ?></label> 
                                        <?php
                                    }
                                    $interestFound = false;
                                    ?>
                                    <?php
                                }
                                ?>
                            </div>

                            <button class="btn btn-primary text-center mt-2 w-100" type="submit">
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
