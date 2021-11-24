<?php
    $currUserID = $_GET['followerID'];
    session_start();
    if(empty($_SESSION['userID'])){
        $errorMsg = "Please login first.";
                
    }else{
        
    }
    follow($followerID, $_SESSION['userID']);
    function follow($currUserID,$subscriberID){
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn ->prepare("SELECT count(*) AS count FROM subscribers WHERE userID = ? AND subscriberID = ?");
        $stmt->bind_param("ii", $currUserID, $subscriberID);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        $conn->close();
        if($rows == 0)
        {
            //if follower record doesn't exist, insert follow record
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            $stmt = $conn -> prepare("INSERT INTO subscribers (userID, subscriberID) VALUES (?,?)");
            $stmt->bind_param("ii",$currUserID,$subscriberID);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }else{
            //if follower record exist, do unfollow.
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            $stmt = $conn -> prepare("DELETE FROM subscribers where userID = ? AND subscriberID = ?");
            $stmt->bind_param("ii",$currUserID,$subscriberID);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        header("Location: profile.php?userID=$currUserID");
    }
 ?>
