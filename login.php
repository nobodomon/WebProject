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
                            <h3 class="text-primary">Sign In</h3>
                        </div>
                        <form action="process_login.php" method="post">
                            <div class="p-4">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-person-plus-fill text-white"></i></span>
                                    <input type="email" id="email" name="email" required  class="form-control" placeholder="Email">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
                                            class="bi bi-key-fill text-white"></i></span>
                                    <input type="password" id="pwd" name="pwd" required placeholder="Enter password" class="form-control" >
                                </div>
                                <button class="btn btn-primary text-center mt-2" type="submit">
                                    Login
                                </button>
                                <p class="text-center mt-5">Don't have an account?
                                    <a href="register.php"><span class="text-primary">Sign Up</span></a>
                                </p>
                                <p class="text-center text-primary">Forgot your password?</p>
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
