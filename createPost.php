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
        <main class="container">
            <div class="card">
                <div class="card-body">

                    <?php include("resources/templates/createpostwidget.php") ?>
                </div>
            </div>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>


