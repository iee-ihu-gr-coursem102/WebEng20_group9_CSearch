<?php

session_start();

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);


$path = __DIR__;
$path = substr($path, 0, -5) . "config.json";


$data = file_get_contents($path);
$json_object = json_decode($data, true);

$_SESSION['server'] = $server = $json_object['server'];
$_SESSION['db'] = $db = $json_object['db'];
$_SESSION['username'] = $username = $json_object['username'];
$_SESSION['password'] = $password = $json_object['password'];
$sender = $json_object['sender'];
$port = $json_object['port'];
$smtp = $json_object['smtp'];



if (!empty($_GET["a1"]) && !empty($_GET["a2"])) {
    $email = $_GET["a1"];
    $hash = $_GET["a2"];

// 
    try {
        $check = 0;
        $functions = substr(__DIR__, 0, -5) . "functions/php/functions.php";
        include_once ($functions);
        $cxn = get_connection();
        /* Αν ο χρήστης έχει κάνει προεγγραφή, τότε τον κάνω activate */
        /* Begin Transaction */
        $cxn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $sql_data = array();
        $lines = array();
        $sql_query = "SELECT COUNT(*) FROM `users` "
                . " WHERE  `email`='$email' "
                . "AND `hash`='$hash' AND `active`=0;";
//        echo $sql_query . "<br>";


        get_data_from_query_NUM($cxn, $lines, $sql_query);
//        print_r($lines);
        $total_lines = $lines[0][0];
//        echo $total_lines;
        if ($lines[0][0] == 1) {
            $sql_query = "UPDATE `users` SET `active`=1 WHERE `email`='$email';";
            //        echo $sql_query . "<br>";

            if ($cxn->query($sql_query)) {
                $check = 1;
            }
        }
        $cxn->commit();
        if ($check == 1) {
            send_verification_email($email, $sender, $smtp, $port);
        }
    } catch (Exception $exc) {
        $cxn->rollback();

        echo $exc->getTraceAsString();
//                mysqli_close($cxn);
    } finally {
        mysqli_close($cxn);
        echo "<script>window.close();</script>";
    }
}

session_destroy();

function send_verification_email($mail_address, $sender, $smtp, $port) {

    $path = substr(__DIR__, 0, -5);
    require $path . '/PHPMailer/src/Exception.php';
    require $path . '/PHPMailer/src/PHPMailer.php';
    require $path . '/PHPMailer/src/SMTP.php';


    $mail = new PHPMailer(true);
    $mail->CharSet = "utf-8";

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();




    //Set the hostname of the mail server
    $mail->Host = $smtp;
    $mail->Port = $port;
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;


    /* Αποστολέας */
    $mail->setFrom($sender, 'CSearch - All about music events');


    /* Παραλήπτης */
    $mail->addAddress($mail_address, 'Dear Customer');

    /* Θέμα μηνύματος */
    $mail->Subject = 'Account Activation';

    /* Σώμα μηνύματος */
    $message = 'This message is autogenarate - Please dont answer<br><br>'
            . ' Your account has been activated';
    $mail->IsHTML(true);


    $mail->Body = $message;
    $mail->Send();
}

?>
