<?php
include "CreditCard.php";
$subscriberID = $_GET['subscriberID'];
$cardName = $_POST["cardName"];
$ccNo = $_POST["ccNo"];
$expiryMonth = $_POST["expiryMonth"];
$expiryYear = $_POST["expiryYear"];
$ccv = $_POST["ccv"];
session_start();
$errorMsg = $successMsg = "";

function add_months($months, DateTime $dateObject) {
    $next = new DateTime($dateObject->format('Y-m-d'));
    $next->modify('last day of +' . $months . ' month');

    if ($dateObject->format('d') > $next->format('d')) {
        return $dateObject->diff($next);
    } else {
        return new DateInterval('P' . $months . 'M');
    }
}

function endCycle($d1, $months) {
    $date = new DateTime($d1);

    // call second function to add the months
    $newDate = $date->add(add_months($months, $date));

    // goes back 1 day from date, remove if you want same day of month
    $newDate->sub(new DateInterval('P1D'));

    //formats final date to Y-m-d form
    $dateReturned = $newDate->format('Y-m-d');

    return $dateReturned;
}

function validateCC() {
    global $ccNo, $expiryYear, $expiryMonth, $ccv, $errorMsg;
    $ccNo = (string) $ccNo;
    $ccv = (string) $ccv;
    $expiryYear = (string) $expiryYear;
    $expiryMonth = (string) $expiryMonth;
    $card = \Inacho\CreditCard::validCreditCard($ccNo);
    if ($card['valid'] == 1) {
        $validDate = \Inacho\CreditCard::validDate($expiryYear, $expiryMonth);
        if ($validDate) {
            $validCCV = \Inacho\CreditCard::validCvc($ccv, $card['type']);
            if ($validCCV) {
                $errorMsg = "VALID CARD!";
                return true;
            } else {
                $errorMsg = "CVV INVALID";
                return false;
            }
        } else {
            $errorMsg = "DATE INVALID";
            return false;
        }
    } else {
        $errorMsg = "CC INVALID";
        return false;
    }
}

function subscribe($subscriberID, $currUserID, $startDate, $endDate) {
    global $success, $errorMsg, $successMsg;
    $success = false;
    $subbedTo = getUserFromID($subscriberID);
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    if ($conn->connect_error) {
        $errorMsg .= "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        $stmt = $conn->prepare("SELECT count(*) AS count FROM subscribers WHERE userID = ? AND subscriberID = ?");
        $stmt->bind_param("ii", $subscriberID, $currUserID);
        if (!$stmt->execute()) {
            $success = false;
            $errorMsg .= $stmt->error;
        } else {
            
            $rows = $stmt->get_result()->fetch_row()[0];
            $success = true;
        }
        $stmt->close();

        $balance = 10.00;
    }


    $conn->close();
    if ($rows == 0 && $success == true) {
        //if follower record doesn't exist, insert subscribe record
        // get todays date and add one month
//            $date = new DateTime('2010-05-31');
//            addMonths($date, 1);
//            print_r($date);
        // but first need to ask for payment method first
        if (validateCC()) {
            $ongoing = "Y";
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if ($conn->connect_error) {
                $errorMsg .= "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $stmt = $conn->prepare("INSERT INTO subscribers (userID, subscriberID, startDate, endDate) VALUES (?,?,?,?)");
                $stmt->bind_param("iiss", $subscriberID, $currUserID, $startDate, $endDate);
                if (!$stmt->execute()) {
                    $success = false;
                    $errorMsg .= $stmt->error;
                } else {
                    $stmt1 = $conn->prepare("INSERT INTO transactions (transactionDateTime, payerID, payeeID, balance, ongoing) VALUES (?,?,?,?,?)");
                    $stmt1->bind_param("siiss", $startDate, $currUserID, $subscriberID, $balance, $ongoing);
                    if (!$stmt1->execute()) {
                        $success = false;
                        $errorMsg .= $stmt1->error;
                    } else {
                        $success = true;
                        $successMsg = "You have successfully subscribed to @" . $subbedTo["username"] . "!";
                    }
                    $stmt1->close();
                }
                $stmt->close();
            }
            $conn->close();
        } else {
            $success = false;
            $errorMsg = "Invalid credit/debit card!";
        }
    } else {
        $ongoing = "N";
        //if subscriber record exist, do unsubscribe.
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        if ($conn->connect_error) {
            $errorMsg .= "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            $stmt = $conn->prepare("UPDATE transactions SET transactionDateTime = ?, ongoing = ? WHERE payerID = ? AND payeeID = ? ORDER BY transactionID  DESC LIMIT 1");
            $stmt->bind_param("ssii", $startDate, $ongoing,$currUserID ,$subscriberID );
            if (!$stmt->execute()) {
                $success = false;
                $errorMsg .= $stmt->error;
            } else {

                $stmt1 = $conn->prepare("DELETE FROM subscribers where userID = ? AND subscriberID = ?");
                $stmt1->bind_param("ii", $subscriberID, $currUserID);
                if (!$stmt1->execute()) {

                    $success = false;
                    $errorMsg .= $stmt1->error;
                } else {

                    $success = true;
                    $successMsg = "You have successfully unsubscribed from @" . $subbedTo["username"] . "!";
                }
                $stmt1->close();
            }
            $stmt->close();
        }
        $conn->close();
    }

    if ($rows == 0 && $success) {
        $content = "has subscribed to you!";
        processNotifications($subscriberID, $content, 3, $currUserID);
    } else {
        
    }
    return $success;
}
?>
<!doctype html>
<html lang="en">
    <?php
    include "head.inc.php"
    ?>
    <body>
        <?php include "nav.inc.php"
        ?>
        <main class="container">
            <?php
            if (empty($_SESSION['userID'])) {
                $errorMsg = "Please login first.";
                include("resources/templates/errorpage.php");
            } else {
                $startDate = date("Y-m-d"); // get today's date
                $nMonths = 1; // monthly subscription so +1 month
                $endDate = endCycle($startDate, $nMonths); // output: 2014-07-02
                if (subscribe($subscriberID, $_SESSION['userID'], $startDate, $endDate)) {
                    include("resources/templates/successpage.php");
                } else {
                    include("resources/templates/errorpage.php");
                }
            }
            ?>
        </main>
        <?php
        include "footer.inc.php"
        ?>
    </body>
</html>