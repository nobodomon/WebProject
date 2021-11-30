<!doctype html>
<?php
?>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php";
        $postID = $_GET['postID'];
        $postInfo = getPostByID($postID);
        $categoriesResults = getAllCategories();
        $postInterests = getPostInterestTags($postID);
        ?>
        <main class="container">
            <form action="process_editPost.php?pid=<?php echo $postInfo['post_id'] ?>" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input class="form-control" type="text" value="<?php echo $postInfo['title'] ?>" id="title" name="title" required placeholder="Enter title"> 
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" placeholder="Enter your content here"><?php echo $postInfo["content"] ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="interestType">Interest Tags: </label>
                    <div class="input-group d-flex">
                        <?php
                        $postInterestsID = array();
                        while ($row1 = $postInterests->fetch_array(MYSQLI_NUM)) {
                            array_push($postInterestsID, $row1[1]);
                        }
                        while ($row = $categoriesResults->fetch_array(MYSQLI_NUM)) {

                            if (in_array($row[0], $postInterestsID)) {
                            ?>
                                <input type="checkbox" class="btn-check" name="interest[]" id="btn-check<?php echo $row[0] ?>" checked autocomplete="off" value="<?php echo $row[0] ?>"/>
                                <label class="btn btn-outline-dark" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1]; ?></label>
                            <?php } else { ?>
                                <input type="checkbox" class="btn-check" name="interest[]" id="btn-check<?php echo $row[0] ?>" autocomplete="off" value="<?php echo $row[0] ?>"/>
                                <label class="btn btn-outline-dark" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1]; ?></label> 
                                <?php
                            }
                            $interestFound = false;
                            ?>
                            <?php
                        }
                        ?>
                        <br>
                    </div>
                </div>
                
               
                <div class="form-group">
                    <label for="postType">Post Privacy:</label>
                    <select id="postType" name="postType" class="form-select form-select-lg mb-3" aria-label=".form-select-lg postPrivacy">
                        <?php
                        $postPrivacyText = array("Public", "Followers Only", "Subscribers only");
                        $i = 0;
                        foreach ($postPrivacyText as $postPrivacy) {
                            if ($postInfo["postType"] == $i) {
                                ?>
                                <option selected value="<?php echo $i ?>"><?php echo $postPrivacy ?></option>
                                <?php
                            } else {
                                ?>

                                <option value="<?php echo $i ?>"><?php echo $postPrivacy ?></option>
                                <?php
                            }
                            $i++;
                        }
                        ?>
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


