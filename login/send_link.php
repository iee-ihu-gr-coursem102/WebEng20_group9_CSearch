<?php

session_start();

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);


$main_page = $_SESSION['root_url'];
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:$main_page");
}


if (isset($_POST['submit_email']) && $_POST['email']) {
    include_once ( $_SESSION['base_path'] . "/functions/php/functions.php");
    $email = trim($_POST['email']);
    try {
        if (check_user_existance($email) == 1) {
            send_mail($email);
        } else {
            echo "<script language='javascript'>alert('Το email που πληκτρολογήσατε δεν είναι καταχωρημένο');</script>";
        }
    } catch (Exception $exc) {
        echo "Something wrong happened. Please try again";
//        echo $exc->getTraceAsString();
    }
}


/* Ελέγχω αν το email είναι καταχωρημένο */

function check_user_existance($email) {

    try {
        $cxn = get_connection();
        $data = array();
        $email = mysqli_real_escape_string($cxn, $email);

        /* Ελέγχουμε αν το user_name και το password ανταποκρίνονται σε χρήστη */
        $sql_query = "SELECT `email`"
                . " FROM `users`   a"
                . " WHERE a.`email`='$email';";
//        echo $sql_query . "<br>";
        get_data_from_query_ASSOC($cxn, $data, $sql_query);
//        print_r($data);
        mysqli_close($cxn);

        /* Αν υπάρχει ο χρήστης στέλνει μήνυμα για αλλαγή του password */
        if (count($data) > 0) {
            return 1;
        } else {
            return 0;
        }
    } catch (Exception $exc) {
        mysqli_close($cxn);
        echo "Something wrong happened. Please try again";
//        echo $exc->getTraceAsString();
    } 
}

function send_mail($mail_address) {
    try {
        require $_SESSION['base_path'] . '/PHPMailer/src/Exception.php';
        require $_SESSION['base_path'] . '/PHPMailer/src/PHPMailer.php';
        require $_SESSION['base_path'] . '/PHPMailer/src/SMTP.php';

        $link = "<a href='http://" .
                $_SERVER['HTTP_HOST']
                . '/' . $_SESSION['root_url']
                . "/login/reset.php?a1=" . $mail_address . "'>Change Password</a>";

        $mail = new PHPMailer(true);


        $mail->IsSMTP();
        $mail->CharSet = "UTF-8";




        //Set the hostname of the mail server  
        $mail->Host = $_SESSION['smtp'];
        $mail->Port = $_SESSION['port'];

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;


        /* Αποστολέας */
//    $sender = $_SESSION['sender'];
        $mail->setFrom($_SESSION['sender'], 'CSearch - All about music events');


        /* Παραλήπτης */
        $mail->addAddress($mail_address, 'Dear Customer');

        /* Θέμα μηνύματος */
        $mail->Subject = 'Sign Up Request';

        /* Σώμα μηνύματος */
        $message = 'This message is autogenarate - Please dont answer<br><br>';
        $message = 'We have a request for password change<br>';
        $message = $message . '<br>If you want to change your password please follow the link.<br>';
        $message = $message . '<br>' . $link;
        $mail->IsHTML(true);


        $mail->Body = $message;

        if ($mail->Send()) {
            echo "Please check your email";
        } else {
//        echo "Mail Error - >" . $mail->ErrorInfo;
            echo "Please try again";
        }
    } catch (Exception $exc) {
//        echo $exc->getTraceAsString();
        echo "Please try again";
    }
}

?>
