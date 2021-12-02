<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php";
        $categoriesResults = getAllCategories();
        ?>
        <main class="container-fluid mb-5">
            <div class="rounded d-flex justify-content-center">
                <div class=" col-lg-6 col-md-8 col-sm-8 shadow-lg p-5 mb-3 bg-light">
                    <div class="text-center">
                        <h3 class="text-primary">Register</h3>
                    </div>
                    <form action="process_register.php" method="post">
                        <div class="p-4">
                            <label for="fname" class="mt-2 mb-2 button-looking-text">First name:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" max-length="45" onkeyup="registrationCharacterCount('fname','fname-label',45)"  id="fname" name="fname" placeholder ="First name">
                                <span class="btn btn-dark text-white" id="fname-label">0/45</span>
                            </div>
                            <label for="lname" class="mt-2 mb-2 button-looking-text">Last Name:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" max-length="45" onkeyup="registrationCharacterCount('lname','lname-label',45)"  id="lname" name="lname" placeholder ="Last name">
                                <span class="btn btn-dark text-white" id="lname-label">0/45</span>
                            </div>
                            <label for="username" class="mt-2 mb-2 button-looking-text">Username:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" max-length="45" onkeyup="registrationCharacterCount('username','username-label',45)" id="username" name="username" placeholder="Username">
                                <span class="btn btn-dark text-white" id="username-label">0/45</span>
                            </div>
                            <label for="email" class="mt-2 mb-2 button-looking-text">Email:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-envelope text-white"></i></span>
                                <input class="form-control" type="email" max-length="45" onkeyup="registrationCharacterCount('email','email-label',45)" id="email" name="email" placeholder="E-mail">
                                <span class="btn btn-dark text-white" id="email-label">0/45</span>
                            </div>
                            <label for="password" class="mt-2 mb-2 button-looking-text">Password:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-key-fill text-white"></i></span>
                                <input class="form-control" type="password" id="pwd" name="pwd" placeholder="Password">
                            </div>
                            <label for="pwd_confirm" class="mt-2 mb-2 button-looking-text">Confirm password:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-key-fill text-white"></i></span>
                                <input class="form-control" type="password" id="pwd_confirm" name="pwd_confirm" placeholder="Confirm password">
                            </div>
                            <label class="mt-2 mb-2 button-looking-text">Your interests:</label>
                            <div class="input-group mb-1">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-bookmark-heart"></i></span>
                                <input class="flex-grow-1 form-control" type="text" id="searchTagBox" onkeyup="searchUpdate()" placeholder="search for tag...">
                            </div>
                            <div class="d-flex interestTagGrp p-2" id="interestTagGrp">
                                <?php while ($row = $categoriesResults->fetch_array(MYSQLI_NUM)) { ?>
                                    <input type="checkbox" class="btn-check m-1" name="interest[]" id="btn-check<?php echo $row[0] ?>" autocomplete="off" value="<?php echo $row[0] ?>"/>
                                    <label class="btn btn-outline-dark m-1" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1]; ?></label>
                                <?php } ?>

                            </div>
                            <br>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Agree to terms and conditions
                                </label>
                            </div>
                            <button class="btn btn-primary text-center mt-2 w-100" type="submit">
                                Register
                            </button>
                            <p class="text-center mt-5">Already have an account?
                                <a href="login.php"><span class="text-primary">Log in</span></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>
