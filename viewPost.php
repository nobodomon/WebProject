<!doctype html>
<html lang="en">
<?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <?php

        ?>
        <main class="container">
            <hr>
                <?php
                    echo $h3;
                    echo $h4;
                    if (empty($errors)){
                    }else{
                        echo $errors;
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