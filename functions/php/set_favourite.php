<?php


session_start();

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
error_reporting(E_ALL);


$main_page = $_SESSION['root_url'];
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:$main_page");
}


//print_r($_POST);
$action=$_POST('action');
$id=$_POST('id');

//Εδώ θα γραφεί κώδικας που ανάλογα με το $action θα ενημερώνει τη βάση με την επιθυμία του χρήστη.
//        Δηλαδή:
//    1) Αν η προτίμηση δεν είναι καταχωρημένη θα την καταχωρεί
//    2) Αν υπάρχει καταχωρημένη η προτίμηση θα την διαγράφει
//    
//Όταν γίνει επιτυχές transaction τότε θα επιστρέψει την τιμή 1 αλλιώς θα επιστρέψει την τιμή 0
//    Οπότε και το javascript θα αλλάξει ή όχι το εικονίδιο


