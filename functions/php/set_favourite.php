<?php

session_start();
//error_reporting(E_ALL);


/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:../../index.php");
}

//Προσθέτω μερικές βιβλιοθήκες
include_once ( $_SESSION['base_path'] . "/functions/php/functions.php");
//print_r($_SESSION);
//Εδώ θα γραφεί κώδικας που ανάλογα με το $option θα ενημερώνει τη βάση με την επιθυμία του χρήστη.
//        Δηλαδή:
//    1) Αν η προτίμηση δεν είναι καταχωρημένη θα την καταχωρεί
//    2) Αν υπάρχει καταχωρημένη η προτίμηση θα την διαγράφει
//    
//Όταν γίνει επιτυχές transaction τότε θα επιστρέψει την τιμή 1 αλλιώς θα επιστρέψει την τιμή 0
//    Οπότε και το javascript θα αλλάξει ή όχι το εικονίδιο
//print_r($_POST);
/* Προχωράω στην ενημέρωση της Βάσης */
try {
    $cxn = get_connection();
    $cxn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

    $option = mysqli_real_escape_string($cxn, $_POST['option']);
    $event = mysqli_real_escape_string($cxn, $_POST['id']);
    $email = mysqli_real_escape_string($cxn, $_SESSION['email']);

//    echo "option:$option";


    if ($option == 1) {
        /* Αν το $option είναι 1 εισάγω την προτίμηση */
        $sql_query = "INSERT INTO `favorites`(`email`, `event_id`) VALUES ('$email',$event);";
    } elseif ($option == 0) {
        /* Αν το $option είναι 0 διαγράφω την προτίμηση */
        $sql_query = "DELETE FROM `favorites` WHERE `email`='$email' AND `event_id`=$event;";
    }
//    echo $sql_query;


    if ($cxn->query($sql_query) === TRUE) {
        echo 1;
    } else {
        echo 0;
    }
    $cxn->commit();

    mysqli_close($cxn);
} catch (Exception $exc) {
    $cxn->rollback();
    echo "Something wrong happened. Transaction did not completed. Please try again";
//    echo $exc->getTraceAsString();
}
?>