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

$server = $json_object['server'];
$db= $json_object['db'];
$username = $json_object['username'];
$password = $json_object['password'];
$sender= $json_object['sender'];


if (!empty($_GET["a1"]) && !empty($_GET["a2"])) {
    $email = $_GET["a1"];
    $password = $_GET["a2"];

    $path = substr(__DIR__, 0, -5);
    $path = $path . "useful/functions.php";

    include ($path);

    //Ελέγχω αν ο χρήστης είναι ήδη καταχωρημένος 
    try {
        $cxn = get_connection();
        /* Εισάγω τον χρήστη στη βάση */
        $sql_query = "INSERT INTO `users`(`email`,  `Passwd`, `group_id`)"
                . " VALUES ('$email',MD5('" . $password . "'),2);";
//        echo $sql_query . "<br>";

        if ($cxn->query($sql_query)) {
            send_verification_email($email,$sender);
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    } finally {
        mysqli_close($cxn);

        echo "<script>window.close();</script>";
    }
}

function send_verification_email($mail_address,$sender) {
    $path = substr(__DIR__, 0, -5);
    require $path . '/PHPMailer/src/Exception.php';
    require $path . '/PHPMailer/src/PHPMailer.php';
    require $path . '/PHPMailer/src/SMTP.php';


    $mail = new PHPMailer(true);
    $mail->CharSet = "utf-8";

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();




    //Set the hostname of the mail server
    $mail->Host = 'smtp.teithe.gr';
    $mail->Port = 25;
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
