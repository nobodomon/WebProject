<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <?php
        include "head.inc.php"
    ?>
    <body>
        <?php
            include "nav.inc.php"
        ?>
        <main class="container">
            <h1>Member Login</h1>
            <p>   For existing members log in here. FOr new members, please go to the
                <a href="register.php">Sign Up page</a>. 
            </p>
            <form action="process_login.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" type="email" id="email" name="email" required placeholder="Enter email"> 
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input class="form-control" type="password" id="pwd" name="pwd" required placeholder="Enter password"> 
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button> 
                </div>
            </form>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>
