<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function time_elapsed_string_short($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'y',
        'm' => 'm',
        'w' => 'w',
        'd' => 'd',
        'h' => 'hr',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getPostByUser($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT * FROM post WHERE author_id = ? ORDER BY postedDateTime DESC");
    $stmt->bind_param("i", $userID);
    if (!$stmt->execute()) {
        return "No post";
    } else {

        $result = $stmt->get_result();
        return $result;
    }
}

function getPostCountsByUser($userID, $privacyType) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    if ($privacyType == 3) {

        $stmt = $conn->prepare("SELECT count(*) FROM post WHERE author_id = ?");
        $stmt->bind_param("i", $userID);
    } else {
        $stmt = $conn->prepare("SELECT count(*) FROM post WHERE author_id = ? AND postType = ?");
        $stmt->bind_param("ii", $userID, $privacyType);
    }
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getFollowingArray($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT * FROM followers WHERE followerID = ?");
    $stmt->bind_param("i", $userID);
    $following = array($userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($rows = $result->fetch_array(MYSQLI_NUM)) {
        array_push($following, $rows[1]);
    }
    $stmt->close();
    $conn->close();
    return $following;
}

function getSubscribingArray($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT * FROM subscribers WHERE subscriberID = ? AND endDate>NOW()");
    $stmt->bind_param("i", $userID);
    $subscribing = array($userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($rows = $result->fetch_array(MYSQLI_NUM)) {
        array_push($subscribing, $rows[0]);
    }
    $stmt->close();
    $conn->close();
    return $subscribing;
}

function getInterestArray($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT * FROM interest WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $interests = array();
    $stmt->execute();
    $result = $stmt->get_result();
    while ($rows = $result->fetch_array(MYSQLI_NUM)) {
        array_push($interests, $rows[1]);
    }
    $stmt->close();
    $conn->close();
    return $interests;
}

function getHomePagePosts($userID) {
    global $list, $array, $errorMsg;
    $types = "";
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $followingArray = getFollowingArray($userID);
    $subscribingArray = getSubscribingArray($userID);
    $followingstr = str_repeat('?,', count($followingArray) - 1) . '?';
    $subscribingstr = str_repeat('?,', count($subscribingArray) - 1) . '?';
    $stmt = $conn->prepare("SELECT * FROM post WHERE author_id IN ($followingstr) OR author_id IN ($subscribingstr)  ORDER BY postedDateTime DESC");
    $types = $types ?: str_repeat('i', count($followingArray) + count($subscribingArray));
    $stmt->bind_param($types, ...$followingArray, ...$subscribingArray);
    $list = $followingstr;
    if (!$stmt->execute()) {
        $errorMsg = $stmt->error;
    } else {
        $posts = $stmt->get_result();
        $stmt->close();
        $conn->close();
    }
    return $posts;
}

function getHomePagePostCount($userID) {
    global $list, $array, $errorMsg;
    $types = "";
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $array = getFollowingArray($userID);
    $followingstr = str_repeat('?,', count($array) - 1) . '?';
    $stmt = $conn->prepare("SELECT count(*) FROM post WHERE author_id IN ($followingstr) ORDER BY postedDateTime DESC");
    $types = $types ?: str_repeat('i', count($array));
    $stmt->bind_param($types, ...$array);
    $list = $followingstr;
    if (!$stmt->execute()) {
        $errorMsg = $stmt->error;
    } else {
        $result = $stmt->get_result();
        $rows = $result->fetch_row()[0];
        $stmt->close();
        $conn->close();
    }
    return $rows;
}

function getUserFromID($id) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM users WHERE userID =?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
    $conn->close();
    return $user;
}

/*
 * Retrieve user details using username
 */

function getUserFromUsername($id) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM users WHERE username =?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
    $conn->close();
    return $user;
}

function searchPostCount($query) {
    global $postSearchSuccess, $postSearchErrorMsg;
    if (isset($_SESSION["userID"])) {
        if (empty($_SESSION["userID"])) {
            $userID = -1;
        } else {
            $userID = $_SESSION["userID"];
        }
    } else {
        $userID = -1;
    }
    if ($query == "") {
        $postSearchErrorMsg = "Search is empty";
        $postSearchSuccess = false;
    } else {
        $splited_query = explode(' ', $query);
        $sql_prep = "SELECT count(*) FROM post WHERE (title LIKE ? OR content LIKE ?";
        $types = "ss";
        $and_query = array();
        $and_types = "";
        $and_sql = "";
        if(strpos($splited_query[0], "&quot;") !== false){
            array_push($and_query, "%".str_replace("&quot;", '', $splited_query[0])."%", "%".str_replace("&quot;", '', $splited_query[0]))."%";
            $and_types .= "ss";
            $and_sql .= " AND (title LIKE ? OR content LIKE ?)";
        }
        $splited_query[0] = "%" . str_replace("&quot;", '', $splited_query[0]) . "%";
        $param = array_fill(0, 2, $splited_query[0]);
        for ($i = 1; $i < count($splited_query); $i++) {
            if(strpos($splited_query[$i], "&quot;") !== false){
                array_push($and_query, "%" . str_replace("&quot;", '', $splited_query[$i]) . "%", "%" . str_replace("&quot;", '', $splited_query[$i]) . "%");
                $and_types .= "ss";
                $and_sql .= " AND (title LIKE ? OR content LIKE ?)";
            }
            $sql_prep = $sql_prep . " OR title LIKE ? OR content LIKE ?";
            $types = $types . "ss";
            $splited_query[$i] = "%" . str_replace("&quot;", '', $splited_query[$i]) . "%";
            for ($j = 0; $j < 2; $j++) {
                array_push($param, $splited_query[$i]);
            }
            
        }
        $sql_prep = $sql_prep . ")". $and_sql . " AND (postType=0 or author_id = " . $userID . " or author_id in (select userID from subscribers where subscriberID=" . $userID . " and endDate>now()) or author_id in(select userID from followers where followerID = " . $userID . "))";
        $types .= $and_types;
        $param = array_merge($param, $and_query);

        //Create database connection
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $postSearchErrorMsg = "Connection failed: " . $conn->connect_error;
            $postSearchSuccess = false;
        } else {
            //Prepare the statement:
            $stmt = $conn->prepare($sql_prep);
            //Bind & Execute the query statement:
            $stmt->bind_param($types, ...$param);
            if (!$stmt->execute()) {
                $postSearchErrorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $postSearchSuccess = false;
            } else {
                $result = $stmt->get_result();
                $rows = $result->fetch_row()[0];
                $postSearchSuccess = true;
            }
            $stmt->close();
        }
        $conn->close();
        return $rows;
    }
}

function getPostsRelatedToQuery($query) {
    global $postSearchSuccess, $postSearchErrorMsg;
    if (isset($_SESSION["userID"])) {
        if (empty($_SESSION["userID"])) {
            $userID = -1;
        } else {
            $userID = $_SESSION["userID"];
        }
    } else {
        $userID = -1;
    }
    if ($query == "") {
        $postSearchErrorMsg = "Search is empty";
        $postSearchSuccess = false;
    } else {
        $splited_query = explode(' ', $query);
        $sql_prep = "SELECT * FROM post WHERE (title LIKE ? OR content LIKE ?";
        $types = "ss";
        $and_query = array();
        $and_types = "";
        $and_sql = "";
        if(strpos($splited_query[0], "&quot;") !== false){
            array_push($and_query, "%".str_replace("&quot;", '', $splited_query[0])."%", "%".str_replace("&quot;", '', $splited_query[0]))."%";
            $and_types .= "ss";
            $and_sql .= " AND (title LIKE ? OR content LIKE ?)";
        }
        $splited_query[0] = "%" . str_replace("&quot;", '', $splited_query[0]) . "%";
        $param = array_fill(0, 2, $splited_query[0]);
        for ($i = 1; $i < count($splited_query); $i++) {
            if(strpos($splited_query[$i], "&quot;") !== false){
                array_push($and_query, "%" . str_replace("&quot;", '', $splited_query[$i]) . "%", "%" . str_replace("&quot;", '', $splited_query[$i]) . "%");
                $and_types .= "ss";
                $and_sql .= " AND (title LIKE ? OR content LIKE ?)";
            }
            $sql_prep = $sql_prep . " OR title LIKE ? OR content LIKE ?";
            $types = $types . "ss";
            $splited_query[$i] = "%" . str_replace("&quot;", '', $splited_query[$i]) . "%";
            for ($j = 0; $j < 2; $j++) {
                array_push($param, $splited_query[$i]);
            }
            
        }
        $sql_prep = $sql_prep . ")". $and_sql . " AND (postType=0 or author_id = " . $userID . " or author_id in (select userID from subscribers where subscriberID=" . $userID . " and endDate>now()) or author_id in(select userID from followers where followerID = " . $userID . "))";
        $types .= $and_types;
        $param = array_merge($param, $and_query);

        //Create database connection
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $postSearchErrorMsg = "Connection failed: " . $conn->connect_error;
            $postSearchSuccess = false;
        } else {
            //Prepare the statement:
            $sql_prep .= " ORDER BY postedDateTime DESC";
            $stmt = $conn->prepare($sql_prep);
            //Bind & Execute the query statement:
            $stmt->bind_param($types, ...$param);
            if (!$stmt->execute()) {
                $postSearchErrorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $postSearchSuccess = false;
            } else {
                $result = $stmt->get_result();
                $postSearchSuccess = true;
            }
            $stmt->close();
        }
        $conn->close();
        return $result;
    }
}

function searchUserNameCount($query) {
    if ($query == "") {
        $userSearchErrorMsg = "Search is empty";
        $userSearchSuccess = false;
    } else {
        $splited_query = explode(' ', $query);
        $sql_prep = "SELECT count(*) FROM users WHERE (username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?";
        $types = "ssss";
        $and_query = array();
        $and_types = "";
        $and_sql = "";
        if(strpos($splited_query[0], "&quot;") !== false){
            for ($j = 0; $j < 4; $j++) {
                array_push($and_query, "%" . str_replace("&quot;", '', $splited_query[0]) . "%");
            }
            $and_types .= "ssss";
            $and_sql .= " AND (username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?)";
        }
        $splited_query[0] = "%" . str_replace("&quot;", '', $splited_query[0]) . "%";
        $param = array_fill(0, 4, $splited_query[0]);
        for ($i = 1; $i < count($splited_query); $i++) {
            if(strpos($splited_query[$i], "&quot;") !== false){
                for ($j = 0; $j < 4; $j++) {
                    array_push($and_query, "%" . str_replace("&quot;", '', $splited_query[$i]) . "%");
                }
                $and_types .= "ssss";
                $and_sql .= " AND (username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?)";
            }
            $sql_prep = $sql_prep . " OR username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?";
            $types = $types . "ssss";
            $splited_query[$i] = "%" . str_replace("&quot;", '', $splited_query[$i]) . "%";
            for ($j = 0; $j < 4; $j++) {
                array_push($param, $splited_query[$i]);
            }
            
        }
        $sql_prep = $sql_prep . ")". $and_sql;
        $types .= $and_types;
        $param = array_merge($param, $and_query);
        
        //Create database connection
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $userSearchErrorMsg = "Connection failed: " . $conn->connect_error;
            $userSearchSuccess = false;
        } else {
            //Prepare the statement:
            $stmt = $conn->prepare($sql_prep);

            //Bind & Execute the query statement:
            $stmt->bind_param($types, ...$param);
            if (!$stmt->execute()) {
                $userSearchErrorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $userSearchSuccess = false;
            } else {
                $result = $stmt->get_result();
                $rows = $result->fetch_row()[0];
                $userSearchSuccess = true;
            }
            $stmt->close();
        }
        $conn->close();
        return $rows;
    }
}

function getUserByUserName($query) {
    global $userSearchSuccess, $userSearchErrorMsg;
    if ($query == "") {
        $userSearchErrorMsg = "Search is empty";
        $userSearchSuccess = false;
    } else {
        $splited_query = explode(' ', $query);
        $sql_prep = "SELECT * FROM users WHERE (username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?";
        $types = "ssss";
        $and_query = array();
        $and_types = "";
        $and_sql = "";
        if(strpos($splited_query[0], "&quot;") !== false){
            for ($j = 0; $j < 4; $j++) {
                array_push($and_query, "%" . str_replace("&quot;", '', $splited_query[0]) . "%");
            }
            $and_types .= "ssss";
            $and_sql .= " AND (username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?)";
        }
        $splited_query[0] = "%" . str_replace("&quot;", '', $splited_query[0]) . "%";
        $param = array_fill(0, 4, $splited_query[0]);
        for ($i = 1; $i < count($splited_query); $i++) {
            if(strpos($splited_query[$i], "&quot;") !== false){
                for ($j = 0; $j < 4; $j++) {
                    array_push($and_query, "%" . str_replace("&quot;", '', $splited_query[$i]) . "%");
                }
                $and_types .= "ssss";
                $and_sql .= " AND (username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?)";
            }
            $sql_prep = $sql_prep . " OR username LIKE ? OR fname LIKE ? OR lname LIKE ? OR email LIKE ?";
            $types = $types . "ssss";
            $splited_query[$i] = "%" . str_replace("&quot;", '', $splited_query[$i]) . "%";
            for ($j = 0; $j < 4; $j++) {
                array_push($param, $splited_query[$i]);
            }
        }
        $sql_prep = $sql_prep . ")". $and_sql;
        $types .= $and_types;
        $param = array_merge($param, $and_query);
        
        //Create database connection
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error) {
            $userSearchErrorMsg = "Connection failed: " . $conn->connect_error;
            $userSearchSuccess = false;
        } else {
            //Prepare the statement:
            $stmt = $conn->prepare($sql_prep);

            //Bind & Execute the query statement:
            $stmt->bind_param($types, ...$param);
            if (!$stmt->execute()) {
                $userSearchErrorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $userSearchSuccess = false;
            } else {
                $result = $stmt->get_result();
                $userSearchSuccess = true;
            }
            $stmt->close();
        }
        $conn->close();
        return $result;
    }
}

function getPostCountOfUser($userID) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from post WHERE author_id =?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getCommentsForPost($postID, $limit = -1) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    if ($limit == -1) {

        $stmt = $conn->prepare("SELECT * FROM comments WHERE postID = ? ORDER BY postedDate DESC");
        $stmt->bind_param("i", $postID);
    } else {

        $stmt = $conn->prepare("SELECT * FROM comments WHERE postID = ? ORDER BY postedDate DESC LIMIT ?");
        $stmt->bind_param("ii", $postID, $limit);
    }
    if (!$stmt->execute()) {
        return "No comments";
    } else {

        $result = $stmt->get_result();
        return $result;
    }
}

function getAuthorOfPost($postID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM post WHERE post_id =?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $postID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
    $conn->close();
    return $user;
}

function getPostByID($postID) {
    global $success, $errorMsg;
    (int) $postID;
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
        return $success;
    } else {
        $stmt = $conn->prepare("SELECT * FROM post WHERE post_id =?");
        $stmt->bind_param("i", $postID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
            return $success;
        } else {
            $result = $stmt->get_result();
            $post = $result->fetch_assoc();
        }
        $stmt->close();
    }

    $conn->close();
    return $post;
}

function checkIfPostExist($postID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT count(*) AS count FROM post WHERE post_id = ?");
    $stmt->bind_param("i", $postID,);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    if ($rows == 0) {
        return false;
    } else {
        //if follower record exist, do unfollow.
        return true;
    }
}

function getSubscribers($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'SELECT * FROM users INNER JOIN subscribers ON users.userID = subscribers.subscriberID WHERE subscribers.userID = ? AND  endDate > NOW()';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result;
}

function getFollowers($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'SELECT * FROM users INNER JOIN followers ON users.userID = followers.followerID WHERE followers.userID = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result;
}

function getFollowing($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'SELECT * FROM users INNER JOIN followers ON users.userID = followers.userID WHERE followers.followerID = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result;
}

function getFollowerCount($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from followers WHERE userID =?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getFollowingCount($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from followers WHERE followerID =?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getLikesForPost($postID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from likes WHERE postID =?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getCommentCountForPost($postID) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from comments WHERE postID =?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function checkIfLiked($postID, $userID) {
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
        return '<span class="material-icons">favorite_border</span>';
    } else {
        //if follower record exist, do unfollow.
        return '<span class="material-icons">favorite</span>';
    }
}

function checkIfFollowed($userID, $currUserID) {
    if ($userID == $currUserID) {
        return true;
    } else {

        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn->prepare("SELECT count(*) AS count FROM followers WHERE userID = ? AND followerID = ?");
        $stmt->bind_param("ii", $userID, $currUserID);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        $conn->close();
        if ($rows == 0) {
            return false;
        } else {
            return true;
        }
    }
}

function editProfileUpdate($newUsername, $newFirstName, $newLastName, $newBiography, $userid) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("UPDATE users SET biography = 'hellooo' ");
    $stmt->bind_param("ssssi", $newUsername, $newFirstName, $newLastName, $newBiography, $userid);
}

function getAllCategories() {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM categories");
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

function getCategoriesOfUser($userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM interest INNER JOIN categories ON categories.categoryID = interest.categoryID WHERE userID=?");
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

function getTotalCategoriesCount() {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT count(*) FROM categories");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
    $conn->close();
    return $user;
}

// related 0 = Like
// related 1 = comment
// related 2 = follow
// related 3 = subscribe
function processNotifications($userID, $content, $related, $fromUserID, $relatedContentID = 0) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $dateTimeNow = date_create()->format('Y-m-d H:i:s');
        if ($related != 1 && $related != 0) {
            $stmt = $conn->prepare("INSERT into notifications (userID,notificationContent,notificationDateTime,related,relatedID) VALUES (?,?,?,?,?)");
            $stmt->bind_param("issii", $userID, $content, $dateTimeNow, $related, $fromUserID);
        } else {
            $stmt = $conn->prepare("INSERT into notifications (userID,notificationContent,notificationDateTime,related,relatedID,relatedContentID) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("issiii", $userID, $content, $dateTimeNow, $related, $fromUserID, $relatedContentID);
        }
        //Bind & Execute the query statement:
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            
        }
        $stmt->close();
    }
    $conn->close();
}

function getNotification($userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE userID=? ORDER BY notificationDateTime DESC");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $notifications = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $notifications;
}

function getNotificationCount($userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT count(*) FROM notifications WHERE userID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
            $notificationCount = $result->fetch_row()[0];
        }
        $stmt->close();
    }
    $conn->close();
    return $notificationCount;
}

function getUserInterestCategories($userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM interest WHERE userID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

function getUserSubscribedList($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $stmt = $conn->prepare("SELECT * FROM subscribers WHERE subscriberID = ?");
    $stmt->bind_param("i", $userID);
    $following = array($userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($rows = $result->fetch_array(MYSQLI_NUM)) {
        array_push($following, $rows[1]);
    }
    $stmt->close();
    $conn->close();
    return $following;
}

function checkIfSubscribed($userID, $currUserID) {
    if ($userID == $currUserID) {
        return true;
    } else {

        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn->prepare("SELECT count(*) FROM subscribers WHERE userID = ? AND subscriberID = ? AND endDate > NOW()");
        $stmt->bind_param("ii", $userID, $currUserID);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        $conn->close();
        if ($rows == 0) {
            return false;
        } else {
            return true;
        }
    }
}

function getSubscribersCount($userID) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from subscribers WHERE userID =? AND endDate > NOW()';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getInterestByPostID($postID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM categoryForPost INNER JOIN categories ON categoryForPost.categoryID = categories.categoryID WHERE postID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $postID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

function getPostInterestTags($postID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM categoryForPost WHERE postID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $postID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

function getPostTagCount($postID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT count(*) FROM categoryForPost INNER JOIN categories ON categoryForPost.categoryID = categories.categoryID WHERE postID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $postID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $rows = $stmt->get_result()->fetch_row()[0];
        }
        $stmt->close();
    }
    $conn->close();
    return $rows;
}

function getInterestCount($userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT count(*) FROM interest WHERE userID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $rows = $stmt->get_result()->fetch_row()[0];
        }
        $stmt->close();
    }
    $conn->close();
    return $rows;
}

function getPostCountBasedOnCategoryID($categoryID) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'select count(*) from categoryForPost INNER JOIN post ON categoryForPost.postID = post.post_id WHERE categoryForPost.categoryID = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoryID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getUserCountBasedOnCategoryID($categoryID) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'SELECT count(*) FROM interest INNER JOIN users ON interest.userID = users.userID WHERE interest.categoryID = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoryID);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    $conn->close();
    return $rows;
}

function getCategoryNameBasedOnCategoryID($categoryID) {

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $sql = 'SELECT categoryName FROM categories WHERE categoryID = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $categoryID);
    if (!$stmt->execute()) {
        return "";
    } else {
        
        $rows = $stmt->get_result()->fetch_row();
        $stmt->close();
        $conn->close();
        if($rows == NULL){
            return "";
        }else{
            
            return $rows[0];
        }
    }
}

function getPostBasedOnCategoryID($categoryID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM categoryForPost INNER JOIN post ON categoryForPost.postID = post.post_id WHERE categoryForPost.categoryID = ? ORDER BY post.postedDateTime DESC");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $categoryID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

function getUsersBasedOnCategoryID($categoryID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM interest INNER JOIN users ON interest.userID = users.userID WHERE interest.categoryID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $categoryID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $result = $stmt->get_result();
        }
        $stmt->close();
    }
    $conn->close();
    return $result;
}

?>
