<?php
include "db.php";
$postID = $_GET['postID'];
$redirect = $_GET['redirectTo'];
session_start();
addLike($postID, $_SESSION['userID']);

function addLike($postID, $userID) {
    global $redirect;
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT count(*) FROM likes WHERE postID = ? AND likeUserID = ?");
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];

    $stmt->close();
    $conn->close();
    if ($rows == 0) {
        //if follower record doesn't exist, insert follow record
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn->prepare("INSERT INTO likes (postID, likeUserID) VALUES (?,?)");
        $stmt->bind_param("ii", $postID, $userID);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    } else {
        //if follower record exist, do unfollow.
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn->prepare("DELETE FROM likes where postID = ? AND likeUserID = ?");
        $stmt->bind_param("ii", $postID, $userID);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT author_id FROM post WHERE post_id = ?");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $authorID = $result->fetch_object();
    $stmt->close();
    $conn->close();
    $notificationContent = "has liked your post!";
    if($rows == 0){
        processNotifications($authorID->author_id,$notificationContent,0,$userID,$postID);
    }
    
    header("Location:".$_SERVER["HTTP_REFERER"]);
}

?>
