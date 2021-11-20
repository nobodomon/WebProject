<html>
    <?php
        include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php"
        ?>
        <main class="container">
            <form action="submitPost.php" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input class="form-control" type="text" id="title" name="title" required placeholder="Enter title"> 
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <input class="form-control" type="text" id="content" name="content" required placeholder="Enter content"> 
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


