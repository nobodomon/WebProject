<!doctype html>
<html lang="en">
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
                    <textarea id="content" name="content" placeholder="Enter your content here"></textarea>
                </div>
                <div class="form-group">
                    <label for="postType">Post Privacy: </label>
                    <select id="postType" name="postType" class="form-select form-select-lg mb-3" aria-label=".form-select-lg postPrivacy">
                        <option selected value="0">Public</option>
                        <option value="1">Followers Only</option>
                        <option value="2">Subscribers only</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Submit</button> 
                </div>
            </form>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>


