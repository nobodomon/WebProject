<?php include 'process_editprofilepicture.php'?>
<link rel="stylesheet" href="css/profilepage.css"/>
    <?php
    include "head.inc.php";
    
    if(!isset($_SESSION['userID'])){
            header("Location: index.php");
            die();
        }
    ?>
    <body>
        <?php
        include "nav.inc.php";
        // get current user details
        $userID = $_SESSION['userID'];
        ?>

        <main class="container-fluid vh-100" style="margin-top:100px">
            <div class="" style="margin-top:100px">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-10 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Profile Picture Selection</h3>
                        </div>
                        <?php if (!empty($msg)): ?>
                            <div class ="alert <?php echo $css_class; ?>">
                                <?php echo $msg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="process_editprofilepicture.php" method="post" enctype="multipart/form-data">
                            <div class="col-4 offset-md-4 form-div"> 
                                <div class="form-group text-center">
                                    <img src="images/default.jpg" onclick="click()" id="profileDisplay" />
                                    <label for="profileImage"> Profile Image </label>
                                    <input class="form-control" type="file" id="profileImage" onchange="displayImage(this)" name="profileImage">
                                </div>
                                <div class="form-group text-center">
                                <button class="btn btn-primary text-center" type="submit" name="save-user">
                                    Save picture
                                </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/profilepic.js"></script>
    
    </body>
    <?php
    include "footer.inc.php"
    ?>
