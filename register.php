<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <main class="container-fluid vh-100" style="margin-top:100px">
            <div class="" style="margin-top:100px">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Register</h3>
                        </div>
                        <form action="process_register.php" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person text-white"></i></span>
                                    <input class="form-control" type="text" id="fname" name="fname"placeholder="Enter first name">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person text-white"></i></span>
                                    <input class="form-control" type="text" id="lname" name="lname" required maxlength="45" placeholder="Enter last name">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person text-white"></i></span>
                                    <input class="form-control" type="text" id="username" name="username" required placeholder="Enter username"> 
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-inbox text-white"></i></span>
                                    <input class="form-control" type="email" id="email" name="email" required placeholder="Enter email"> 
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-key-fill text-white"></i></span>
                                    <input class="form-control" type="password" id="pwd" name="pwd" required placeholder="Enter password"> 
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-key-fill text-white"></i></span>
                                    <input class="form-control" type="password" id="pwd_confirm" name="pwd_confirm" required placeholder="Confirm password">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-book text-white"></i></span>
                                    <input class="form-control" type="interest" id="int1" name="int1" required placeholder="Insert your first interest(if any)">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-book text-white"></i></span>
                                    <input class="form-control" type="interest" id="int2" name="int2" required placeholder="Insert your second interest(if any)">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-book text-white"></i></span>
                                    <input class="form-control" type="interest" id="int3" name="int3" required placeholder="Insert your third interest(if any)">
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Agree to terms and conditions
                                    </label>
                                </div>
                                <button class="btn btn-primary text-center mt-2" type="submit">
                                    Register
                                </button>
                                <p class="text-center mt-5">Already have an account?
                                    <a href="login.php"><span class="text-primary">Log in</span></a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>
