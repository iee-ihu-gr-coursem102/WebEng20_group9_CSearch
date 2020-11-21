<?php

session_start();
error_reporting(E_ALL);


$main_page = $_SESSION['root_url'];
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:$main_page");
}

if (isset($_POST['email']) && isset($_POST['password'])) {
//    $email = $_POST['email'];
//    $password = $_POST['password'];
    include_once  ($_SESSION ['base_path'] . "/functions/php/functions.php");
//    check_user_registration($email, $password);
    check_user_registration();
}

//function check_user_registration($email, $password) {
function check_user_registration() {
    //Ελέγχω αν τα στοιχεία αυτά είναι καταχωρημένα 
    try {
        $cxn = get_connection();
        $email = mysqli_real_escape_string($cxn, $_POST['email']);
        $password = mysqli_real_escape_string($cxn, $_POST['password']);

//         $email = $_POST['email'];
//    $password = $_POST['password'];
        $user_data = array();

        /* Ελέγχουμε αν το user_name και το password ανταποκρίνονται σε χρήστη */
        $sql_query = "SELECT count(*) as `how_many` "
                . " FROM `users`   "
                . " WHERE `email`='$email' and  `Passwd`=MD5('" . $password . "') ;";
//        echo $sql_query . "<br>";
        get_data_from_query_ASSOC($cxn, $user_data, $sql_query);
//        print_r($user_data);
        mysqli_close($cxn);

        /* Αν υπάρχει ο χρήστης πρέπει επιστρέψει πίσω κωδικό λάθους */
        if ($user_data[0]['how_many'] == 1) {
//            echo "User is ok";
            $_SESSION['login'] = true;
            $_SESSION['email'] = $email;
            echo "Hello user ".$email;
        } else {
            echo "This user does not exist";
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}
