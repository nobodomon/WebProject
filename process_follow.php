<?php
    $followerID = $_GET['followerID'];
    session_start();
    follow($followerID, $_SESSION['userID']);
    function follow($followerID,$currUserID){
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn ->prepare("SELECT count(*) AS count FROM followers WHERE userID = ? AND followerID = ?");
        $stmt->bind_param("ii", $followerID, $currUserID);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        $conn->close();
        if($rows == 0)
        {
            //if follower record doesn't exist, insert follow record
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            $stmt = $conn -> prepare("INSERT INTO followers (userID, followerID) VALUES (?,?)");
            $stmt->bind_param("ii",$followerID,$currUserID);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }else{
            //if follower record exist, do unfollow.
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            $stmt = $conn -> prepare("DELETE FROM followers where userID = ? AND followerID = ?");
            $stmt->bind_param("ii",$followerID,$currUserID);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        header("Location: profile.php?userID=$followerID");
    }
 ?>
