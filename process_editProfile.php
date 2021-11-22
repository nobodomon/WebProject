<html>
    <?php
        include "head.inc.php"
    ?>
    <body>
        <?php
            include "nav.inc.php";
            // get current user details
            $currentUserDetails = getUserFromID($_SESSION['userID']);
        ?>
        <?php
            $errorMsg = "";
            $success = true;
            $newUsername = "";
            $newBiography = "HELLOW THERE";
//            editProfileUpdate($newBiography,$newBiography,$newBiography,$newBiography,$newBiography);

        
        
        ?>
        <main class="container">
            <hr>
                <?php
                    if (empty($errorMsg)){
                    }else{
                        echo $errorMsg;
                    }
                    echo $btn;
                ?>
            <br>
        </main>
        <?php
            include "footer.inc.php"
        ?>
    </body>
</html>