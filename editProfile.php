<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<<<<<<< Updated upstream
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>
=======

<html>
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
            include "nav.inc.php";
            // get current user details
            $userResults = getUserFromID($_SESSION['userID']);
        ?>

        
        <main class="container">
            <h1>Edit Profile</h1>
            <form action="process_editProfile.php" method="post">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input class="form-control" type="text" id="fname" name="fname" value="<?php echo $userResults['fname']; ?>"> 
                </div>
                <br/>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input class="form-control" type="text" id="lname" name="lname" value="<?php echo $userResults['lname']; ?>">
                </div>
                <br/>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" id="username" name="username" value="<?php echo $userResults['username']; ?>">
                </div>
                <br/>
                <div class="form-group">
                    <label for="biography">Bio:</label>
                    <input class="form-control" type="text" id="biography" name="biography" value="<?php echo $userResults['biography']; ?>">
                </div>
                <br/>
                 // Add image for the display picture 
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button> 
                </div>
                <br/>
            </form>
        </main>

    </body>
    <?php
        include "footer.inc.php"
    ?>
>>>>>>> Stashed changes
</html>
