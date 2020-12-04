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


//Προσθέτω μερικές βιβλιοθήκες
include_once ( $_SESSION['base_path'] . "/functions/php/functions.php");
// Αντιγράφω τις POST values στον πίνακα SESSION αφού αφαιρέσω τυχόν κενούς χαρακτήρες που προστέθηκαν κατά λάθος κατά την πληκτρολόγηση
//Αν είναι ήδη συνδεδεμένος τότε δεν του επιτρέπω να συνεχίσει
if (isset($_SESSION['is_logged_in'])) {
    /* Είναι συνδεδεμένος, θα πρέπει να αποσυνδεθεί για να δημιουργήσει λογαριασμό */
    echo "must logout";
} else {

    check_user_existance();
}

function check_user_existance() {
    //Ελέγχω αν ο χρήστης είναι ήδη καταχωρημένος 


    try {

        $cxn = get_connection();
        $cxn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $sql_data = array();
        $email = mysqli_real_escape_string($cxn, $_POST['email']);
        $password = mysqli_real_escape_string($cxn, $_POST['password']);

        /* Ελέγχουμε αν το user_name και το password ανταποκρίνονται σε χρήστη */
        $sql_query = "SELECT `email`"
                . " FROM `users`   a"
                . " WHERE a.`email`='$email' ;";
//        echo $sql_query . "<br>";
        get_data_from_query_ASSOC($cxn, $sql_data, $sql_query);
//        print_r($sql_data);

        /* Αν υπάρχει ο χρήστης πρέπει επιστρέψει πίσω κωδικό λάθους */
        if (count($sql_data) > 0) {
            echo "This email exist";
        } else {
            /* Καταχωρώ τον χρήστη στη βάση σαν ανενεργό */
            $MD5_password = md5($password);
            $encrypted_password = hash('sha256', $MD5_password);
            $hash = md5(rand(0, 1000));


            /* Εισάγω τον χρήστη στη βάση σαν μη ενεργό */
            $sql_query = "INSERT INTO `users`(`email`, `Passwd`, `hash`)VALUES ('$email', '$encrypted_password','$hash');";
            $sql_query = "INSERT INTO `users`(`email`, `Passwd`, `hash`)VALUES (?,?,?);";
            $statement = $cxn->prepare($sql_query);
            $statement->bind_param('sss',$email, $encrypted_password, $hash);
//            echo $sql_query . "<br>";

            if ($statement->execute()) {
                send_email($email, $hash);
            } else {
               
                echo "There was an error";
//                die('Error : (' . $mysqli->errno . ') ' . $mysqli->error);
            }
            $statement->close();

        }

        $cxn->commit();

        mysqli_close($cxn);

//        send_mail($password, $email);
    } catch (Exception $exc) {
        $cxn->rollback();
        echo "Something wrong happened. Transaction did not completed. Please try again";
//        echo $exc->getTraceAsString();
    }
}

function send_email($mail_address, $hash) {
    try {
        require $_SESSION['base_path'] . '/PHPMailer/src/Exception.php';
        require $_SESSION['base_path'] . '/PHPMailer/src/PHPMailer.php';
        require $_SESSION['base_path'] . '/PHPMailer/src/SMTP.php';

        $link = "<a href='http://" .
                $_SERVER['HTTP_HOST']
                . '/' . $_SESSION['root_url']
                . "/login/user_account_activation_code.php?a1=" . $mail_address
                . "&a2=" . $hash . "'>Create User Account</a>";



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
        $message = 'We have a request for account creation<br>';
        $message = $message . '<br>If you want to activate your account please follow the link.<br>';
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
        echo "Please try once more";
    }
}

?>