<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getUserFromID($id){
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if($conn->connect_error){
        $errorMsg = "Connection failed: " . $conn -> connect_error;
        $success = false;
    }else{
        //Prepare the statement:
        $stmt = $conn -> prepare("SELECT * FROM users WHERE userID =?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i",$id);
        if(!$stmt-> execute()){
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }else{
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        $stmt->close();

    }
    $conn->close();
    return $user;
}

function getPostsRelatedToQuery($query){
    $query = "%$query%";
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if($conn->connect_error){
        $errorMsg = "Connection failed: " . $conn -> connect_error;
        $success = false;
    }else{
        //Prepare the statement:
        $stmt = $conn -> prepare("SELECT * FROM post WHERE content LIKE ? OR title LIKE ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("ss",$query,$query);
        if(!$stmt-> execute()){
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }else{
            $result = $stmt->get_result();
        }
        $stmt->close();

    }
    $conn->close();
    return $result;
}
function getUserByUserName($query){
    $query = "%$query%";
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if($conn->connect_error){
        $errorMsg = "Connection failed: " . $conn -> connect_error;
        $success = false;
    }else{
        //Prepare the statement:
        $stmt = $conn -> prepare("SELECT * FROM users WHERE username LIKE ? OR fname LIKE ? or lname LIKE ? or email LIKE ?");
        
        //Bind & Execute the query statement:
        $stmt->bind_param("ssss",$query,$query,$query,$query);
        if(!$stmt-> execute()){
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }else{
            $result = $stmt->get_result();
        }
        $stmt->close();

    }
    $conn->close();
    return $result;
}

function getPostByUser($userID){
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn -> prepare("SELECT * FROM post WHERE author_id = ? ORDER BY postedDateTime DESC");
    $stmt->bind_param("i",$userID);
    if(!$stmt->execute()){
        return "No post";
    }else{
        
        $result = $stmt->get_result();
        return $result;
    }
}
function getPostByID($postID){
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn -> prepare("SELECT * FROM post WHERE post_id = ?");
    $stmt->bind_param("i",$postID);
    if(!$stmt->execute()){
        return "No post";
    }else{
        
        $result = $stmt->get_result();
        return $result;
    }
}

function follow($currUserID, $followToUserID){
    
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn -> prepare("INSERT INTO users (username, fname, lname, email, password) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ii",$currUserID,$followToUserID);
}
?>
