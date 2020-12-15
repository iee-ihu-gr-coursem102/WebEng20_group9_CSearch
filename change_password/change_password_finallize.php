<?php

session_start();
error_reporting(E_ALL);


/* Import PHPMailer classes into the global namespace
 * These must be at the top of your script, not inside a function */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* Αρχικοποιώ μεταβλητές */


$path = __DIR__;
$root_path = substr(__DIR__, 0, -15);

//echo $path."<br>";
require $root_path . '/PHPMailer/src/Exception.php';
require $root_path . '/PHPMailer/src/PHPMailer.php';
require $root_path . '/PHPMailer/src/SMTP.php';

/* Διαβάζω το αρχείο με τις παραμέτρους */
$data = file_get_contents($root_path . "config.json");
$json_object = json_decode($data, true);

/* Αρχικοποίηση παραμέτρων σύνδεσης με τη βάση */
$_SESSION['server'] = $server = $json_object['server'];
$_SESSION['db'] = $db = $json_object['db'];
$_SESSION['username'] = $username = $json_object['username'];
$_SESSION['password'] = $password = $json_object['password'];

/* Αρχικοποίηση παραμέτρων αποστολής email */
$_SESSION['sender'] = $json_object['sender'];
$_SESSION['smtp'] = $json_object['smtp'];
$_SESSION['port'] = $json_object['port'];


/* Ελέγχει αν έχει έρθει το Get με δύο παραμέτρους */
if (!empty($_GET["a1"]) && !empty($_GET["a2"])) {
    try {
        $check = 0;
        $functions = substr(__DIR__, 0, -15) . "functions/php/functions.php";
        include_once ($functions);
        $cxn = get_connection();
        /* Αρχίζω Transaction */
        $cxn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        /* Παίρνω τις τιμές από το GET */
        $email = mysqli_real_escape_string($cxn, $_GET["a1"]);
        $hash = mysqli_real_escape_string($cxn, $_GET["a2"]);

        /* Ελέγχω αν ο χρήστης έχει ζητήσει αλλαγή - το temp_passwd δεν είναι κενό */
        $check = user_wants_to_change_pass($cxn, $email, $hash);
        

        /* Αν περιμένει να αλλαγή την εκτελώ */
        if ($check > 0) {
            $check = change_password($cxn, $email);
        }

        /* Επικυρώνω το Transaction */
        $cxn->commit();

        /* Αν γίνει ενεργοποίηση στέλνω email */
        if ($check == 1) {
            $mail = new PHPMailer(true);
            include_once '../functions/php/send_email.php';
            $message = 'This message is autogenarated - Please dont answer<br><br> Your password has been changed';
            try {
                send_email($email, $message, 'Password Changed', $mail);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
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

/**
 * Επιστρέφει τον αριθμό των εγγραφών με αυτό το email
 * @param type $cxn
 * @param type $email
 * @param type $hash
 * @return array        
 */
function user_wants_to_change_pass($cxn, $email, $hash) {
    $sql_data = array();
    $lines = array();
    $sql_query = "SELECT LENGTH(`temp_passwd`) FROM `users` WHERE `email`='$email' AND `hash`='$hash' ;";
//    echo $sql_query . "<br>";
    get_data_from_query_NUM($cxn, $lines, $sql_query);
    if (sizeof($lines) == 0) {
        return 0;
    } else
        return $lines[0][0];
}

/**
 * Ενεργοποιώ τον χρήστη
 * @param type $cxn
 * @param type $email
 * @return type
 */
function change_password($cxn, $email) {
    /* Ενημερώνω το password kai διαγράφω το temp_password */
    $sql_query = "UPDATE `users` SET `Passwd`=`temp_passwd`, `temp_passwd`='' WHERE `email`='$email';";
    //        echo $sql_query . "<br>";
    if ($cxn->query($sql_query)) {
        return 1;
    }
}

?>
