<!doctype html>
<?php
session_start();
$postID = $_GET["postID"];
?>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php
        include "nav.inc.php";
        $categoriesResults = getAllCategories();
        ?>
        <?php
        ?>
        <main class="container">
            <div class="card">

                <div class="card-body">
                    <?php
                    $postExist = checkIfPostExist($postID);
                    if ($postExist) {
                        $sessionUserID;
                        $sessionUser;
                        $post = getPostByID($postID);
                        $author_id = $post["author_id"];
                        $postAuthor = getUserFromID($author_id);
                        $title = $post["title"];
                        $content = $post["content"];
                        $postedDateTime = $post["postedDateTime"];
                        $postPrivacy = $post["postType"];
                        $edited = $post["edited"];
                        $editedDateTime = $post["editedDateTime"];
                        if (!isset($_SESSION['userID'])) {
                            $sessionUserID = -1;
                            $followed = false;
                            $subscribed = false;
                        } else {
                            $sessionUserID = $_SESSION['userID'];
                            $sessionUser = getUserFromID($_SESSION['userID']);
                            $followed = checkIfFollowed($author_id, $_SESSION['userID']);
                            $subscribed = checkIfSubscribed($author_id, $_SESSION['userID']);
                        }
                        if (($post["author_id"] == $sessionUserID) || ($postPrivacy == 0 ) || ($postPrivacy == 1 && $followed) || ($postPrivacy == 2 && $subscribed)) {
                            include("resources/templates/post.php");
                        }else{
                            $errorMsg = "The post was not found or you do not have the permissions to view this post";
                            include("resources/templates/errorpage.php");
                        }
                    } else if ($postExist == false) {
                        ?>
                        <div class="border border-light p-2 mb-3">
                            <div class="page-wrap d-flex flex-row align-items-center">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 text-center">
                                            <span class="display-1 d-block">Oops!</span>
                                            <div class="mb-4 lead">The post was not found or you do not have the permissions to view this post.</div>
                                            <a href="index.php" class="btn btn-link">Back to Home</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php
    include "footer.inc.php"
    ?>
</body>
</html>