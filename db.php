<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

function getHomePagePosts($userID) {
    global $list, $array, $errorMsg;
    $types = "";
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    $array = getFollowingArray($userID);
    $followingstr = str_repeat('?,', count($array) - 1) . '?';
    $stmt = $conn->prepare("SELECT * FROM post WHERE author_id IN ($followingstr) ORDER BY postedDateTime DESC");
    $types = $types ?: str_repeat('i', count($array));
    $stmt->bind_param($types, ...$array);
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

function getPostsRelatedToQuery($query) {
    global $postSearchSuccess, $postSearchErrorMsg;
    if ($query == "") {
        $postSearchErrorMsg = "Search is empty";
        $postSearchSuccess = false;
    } else {
        $splited_query = explode(' ', $query);
        $sql_prep = "SELECT * FROM post WHERE title OR content LIKE ?";
        $splited_query[0] = "%$splited_query[0]%";
        $types = "s";
        for ($i = 1; $i < count($splited_query); $i++) {
            $sql_prep = $sql_prep . " OR title OR content LIKE ?";
            $types = $types . "s";
            $splited_query[$i] = "%$splited_query[$i]%";
        }

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
            $stmt->bind_param($types, ...$splited_query);
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

function getUserByUserName($query) {
    global $userSearchSuccess, $userSearchErrorMsg;
    if ($query == "") {
        $userSearchErrorMsg = "Search is empty";
        $userSearchSuccess = false;
    } else {
        $splited_query = explode(' ', $query);
        $sql_prep = "SELECT * FROM users WHERE username OR fname OR lname OR email LIKE ?";
        $splited_query[0] = "%$splited_query[0]%";
        $types = "s";
        for ($i = 1; $i < count($splited_query); $i++) {
            $sql_prep = $sql_prep . " OR username OR fname OR lname OR email LIKE ?";
            $types = $types . "s";
            $splited_query[$i] = "%$splited_query[$i]%";
        }
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
            $stmt->bind_param($types, ...$splited_query);
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

function getCommentsForPost($postID, $limit = 3) {

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
?>
