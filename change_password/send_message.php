<?php

session_start();

//error_reporting(E_ALL);
//print_r($_SESSION);
/* Import PHPMailer classes into the global namespace
 *  These must be at the top of your script, not inside a function */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
//    header("location:../index.php");
    echo "home_page";
} else {

    require $_SESSION['base_path'] . '/PHPMailer/src/Exception.php';
    require $_SESSION['base_path'] . '/PHPMailer/src/PHPMailer.php';
    require $_SESSION['base_path'] . '/PHPMailer/src/SMTP.php';

//Προσθέτω μερικές βιβλιοθήκες
    include_once ( $_SESSION['base_path'] . "/functions/php/functions.php");
    /* Αντιγράφω τις POST values στον πίνακα SESSION αφού αφαιρέσω τυχόν κενούς χαρακτήρες που προστέθηκαν 
     * κατά λάθος κατά την πληκτρολόγηση
     * Αν είναι ήδη συνδεδεμένος τότε δεν του επιτρέπω να συνεχίσει */
    if (isset($_SESSION['is_logged_in'])) {
        /* Είναι συνδεδεμένος, θα πρέπει να αποσυνδεθεί για να δημιουργήσει λογαριασμό */
        echo "must logout";
    } else {
        /* Προχωράω στην ενημέρωση της Βάσης */
        try {
            $cxn = get_connection();
            $cxn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

            $email = trim(mysqli_real_escape_string($cxn, $_POST['email']));
            $password = trim(mysqli_real_escape_string($cxn, $_POST['password']));


            /* Αν δεν υπάρχει ο χρήστης πρέπει επιστρέψει πίσω κωδικό λάθους */
            if (check_user_existance($cxn, $email) == 0) {
                echo "This email does not exist";
            } else {
                /* Aν καταχωρήσω επιτυχώς την αλλαγή στη βάση στέλνω email */
                $hash = md5(rand(0, 1000));
                if (update_database($cxn, $password, $email, $hash) == 1) {
                    $mail = new PHPMailer(true);
                    include_once '../functions/php/send_email.php';
                    send_email($email, create_message($email, $hash), 'Change Password Request', $mail);
                } else {
//                echo $exc->getTraceAsString();
                    echo "There was an error";
                }
            }

            $cxn->commit();
            mysqli_close($cxn);
        } catch (Exception $exc) {
            $cxn->rollback();
            echo "Something wrong happened. Transaction did not completed. Please try again";
            echo $exc->getTraceAsString();
        }
    }
}

/**
 * 
 * @param type $cxn         database connection
 * @param type $password    temp password to store
 * @param type $email       email address
 * @param type $hash        hash value
 * @return int              return value 1 or 0
 */
function update_database($cxn, $password, $email, $hash) {
    $MD5_password = md5($password);
    $encrypted_password = hash('sha256', $MD5_password);

    /* Εισάγω τον νέο κωδικό στον πίνακα προσωρινών κωδικών */
    $sql_query = "UPDATE `users` SET`temp_passwd`='$encrypted_password'"
            . ", `hash`='$hash' ,`timestamp`=CURRENT_TIMESTAMP "
            . " WHERE `email` ='$email';";



    if ($cxn->query($sql_query) === TRUE) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Δημιουργεί το μήνυμα που θα σταλθεί
 * @param type $email
 * @param type $hash
 * @return string
 */
function create_message($email, $hash) {
    $link = "<a href='http://" .
            $_SERVER['HTTP_HOST']
            . '/' . $_SESSION['root_url']
            . "/change_password/change_password_finallize.php?a1=" . $email
            . "&a2=" . $hash . "'>Change Password</a>";

    $message = 'This message is autogenarated - Please dont answer<br><br>';
    $message = 'We have a changing password request<br>';
    $message = $message . '<br>If you want to change your password please follow the link.<br>';
    $message = $message . '<br>After that wait untill the confirmation message before you try to login<br>';
    $message = $message . '<br>' . $link;
    return $message;
}

?>