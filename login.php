<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <main class="container-fluid">
            <div class="rounded d-flex justify-content-center">
                <div class=" col-lg-6 col-md-8 col-sm-8 shadow-lg p-5 bg-light">
                    <div class="text-center">
                        <h3 class="text-primary">Sign In</h3>
                    </div>
                    <form action="process_login.php" method="post">
                        <div class="p-2">
                            <label for="email" class="mt-2 mb-2 button-looking-text">Email:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-envelope text-white"></i></span>
                                <input class="form-control" type="email" id="email" name="email" placeholder="E-mail">
                            </div>
                            <label for="password" class="mt-2 mb-2 button-looking-text">Password:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="bi bi-key-fill text-white"></i></span>
                                <input class="form-control" type="password" id="pwd" name="pwd" placeholder="Password">
                            </div>
                            <button class="btn btn-primary text-center mt-2 w-100" type="submit">
                                Login
                            </button>
                            <p class="text-center mt-5">Don't have an account?
                                <a href="register.php"><span class="text-primary">Sign Up</span></a>
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
