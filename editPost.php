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
        $sessionUserID = -1;
        if (isset($_SESSION)) {
            if (empty($_SESSION["userID"])) {
                
            } else {
                $sessionUserID = $_SESSION["userID"];
            }
        }
        $categoriesResults = getAllCategories();
        $postInterests = getPostInterestTags($postID);
        if ($sessionUserID == $postInfo["author_id"]) {
            ?>

            <main class="container mb-5">
                <div class="card">
                    <div class="card-body">
                        <form class="comment-area-box mb-3" action="process_editPost.php?pid=<?php echo $postInfo['post_id'] ?>" method="post">

                            <label for="title" class="mt-2 mb-2 button-looking-text">Title:</label>
                            <div class="input-group">
                                <input class="form-control" type="text" onkeyup="titleCharacterCount()" maxlength="255" id="title" name="title" required placeholder="Enter title" value="<?= $postInfo["title"] ?>"> 
                                <span class="btn btn-dark text-white" id="title-label">0/255</span>
                            </div>
                            <span class="input-icon">
                                <label for="content" class="mt-2 mb-2 button-looking-text">Content:</label>
                                <textarea class="form-control" id="content" name="content" placeholder="Write something..."><?php echo $postInfo["content"] ?></textarea>
                            </span>

                            <div class="comment-area-btn">
                                <label for="interestType" class="mt-2 mb-2 button-looking-text">Interest Tags: </label>
                                <div  class="input-group">
                                    <input class="flex-grow-1 form-control" type="text" id="searchTagBox" onkeyup="searchUpdate()" placeholder="search for tag...">
                                </div>
                                <div class="d-flex interestTagGrp p-2" id="interestTagGrp">
                                    <?php
                                    $postInterestsID = array();
                                    while ($row1 = $postInterests->fetch_array(MYSQLI_NUM)) {
                                        array_push($postInterestsID, $row1[1]);
                                    }
                                    while ($row = $categoriesResults->fetch_array(MYSQLI_NUM)) {

                                        if (in_array($row[0], $postInterestsID)) {
                                            ?>
                                            <input type="checkbox" class="btn-check m-1" name="interest[]" id="btn-check<?php echo $row[0] ?>" checked autocomplete="off" value="<?php echo $row[0] ?>"/>
                                            <label class="btn btn-outline-dark m-1" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1] ?></label>
                                        <?php } else { ?>

                                            <input type="checkbox" class="btn-check m-1" name="interest[]" id="btn-check<?php echo $row[0] ?>" autocomplete="off" value="<?php echo $row[0] ?>"/>
                                            <label class="btn btn-outline-dark m-1" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1] ?></label>
                                            <?php
                                        }
                                        $interestFound = false;
                                        ?>
                                        <?php
                                    }
                                    ?>
                                    <br>
                                </div>

                                <label for="postType" class="mt-2 mb-2 button-looking-text">Post Privacy: </label>
                                <div class="input-group d-flex">
                                    <select id="postType" name="postType" class="form-select form-select-sm" aria-label=".form-select-lg postPrivacy">
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
                                    <div class="float-end">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Post</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        <?php } else {
            ?>
            <main class="container">
                <?php
                $errorMsg = "You do not have permissions to edit this post!";
                include("resources/templates/errorpage.php");
                ?>
            </main>
            <?php }
        ?>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>


