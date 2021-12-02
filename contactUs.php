<!DOCTYPE html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php";
        // get current user details
        ?>
        <main class="container-fluid mb-5">
            <div class="rounded d-flex justify-content-center">
                <div class=" col-lg-6 col-md-8 col-sm-8 shadow-lg p-5 bg-light">
                    <div class="text-center">
                        <h3 class="text-primary">Contact Us</h3>
                    </div>
                    <form action="contactmailer.php" method="post">
                        <div class="p-4">
                            <label for="email" class="mt-2 mb-2 button-looking-text">Your email:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary"><i
                                        class="bi bi-mailbox2 text-white"></i></span>
                                <input class="form-control" type="email" id="email" name="email" required placeholder="Enter your Email">
                            </div>
                            <label for="name" class="mt-2 mb-2 button-looking-text">Your name:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary"><i
                                        class="bi bi-person text-white"></i></span>
                                <input class="form-control" type="text" id="name" name="name" required maxlength="45" placeholder="Enter your Name">
                            </div>
                            <label for="subject" class="mt-2 mb-2 button-looking-text">Subject:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary"><i
                                        class="bi bi-card-heading text-white"></i></span>
                                <input class="form-control" type="text" id="subject" name="subject" required placeholder="Subject"> 
                            </div>
                            <label for="feedback" class="mt-2 mb-2 button-looking-text">Feedback:</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary"><i
                                        class="bi bi-card-text text-white"></i></span>
                                <textarea class="form-control" id="feedback" name="feedback" required placeholder="Feedback"></textarea>
                            </div>
                            <button class="btn btn-primary text-center mt-2" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </body>
    <?php
    include "footer.inc.php"
    ?>
</body>
</html>
