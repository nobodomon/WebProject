<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$email = $name = $subject = $feedback = $errorMsg = "";
$success = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// email validation
    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["email"]);

        // Additional check to make sure e-mail address is well-formed.    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }
    if (empty($_POST["name"])) {
        $errorMsg .= "Name is required.<br>";
        $success = false;
    } else {
        $name = sanitize_input($_POST["name"]);
    }
    if (empty($_POST["subject"])) {
        $errorMsg .= "Subject is required.<br>";
        $success = false;
    } else {
        $subject = sanitize_input($_POST["subject"]);
    }
    if (empty($_POST["feedback"])) {
        $errorMsg .= "Feedback is required.<br>";
        $success = false;
    } else {
        $feedback = sanitize_input($_POST["feedback"]);
    }
}

//Helper function that checks input for malicious or unwanted content.

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function contact_us($email, $name, $subject, $feedback) {
    global $email, $name, $subject, $feedback, $successMsg, $errorMsg, $success, $mail;
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {

        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                // Enable verbose debug output
        $config = parse_ini_file('../../private/db-config.ini');
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $config['email'];                     // SMTP username
        $mail->Password = $config['pass'];                           // SMTP password
        //    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption 
        $mail->Port = 587;                                    // TCP port to connect to
        //Recipients
        $mail->setFrom($email, $name);          //This is the email your form sends From
        $mail->addAddress($config['email'], 'Stori'); // Add a recipient address
        $mail->addReplyTo($email, $name);
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = "<p>Name: " . $name . "</p>";
        $mail->Body .= "<p>Email: " . $email . "</p>";
        $mail->Body .= "<p>Subject: " . $subject . "</p>";
        $mail->Body .= "<p>Feedback: " . $feedback . "</p>";
        $mail->AltBody = $feedback;

        $mail->send();
        $success = true;
        $successMsg = "Thank you for contacting us we will get back to you as soon as possible";
    } catch (Exception $e) {
        $success = false;
        $errorMsg = "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Stori</title>
        <?php
        include "head.inc.php";
        ?>
    </head>
    <body>
        <?php
        include "nav.inc.php";
        ?>
        <main class="container">
            <?php
            if ($success) {
                //echo $mail;
                contact_us($email, $name, $subject, $feedback);
                if ($success) {
                    include("resources/templates/successpage.php");
                } else {
                    include("resources/templates/errorpage.php");
                }
            }
            ?>
        </main>
        <br>
<?php
include "footer.inc.php";
?>
    </body>
</html>
