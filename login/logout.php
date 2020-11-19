<?php
session_start();
error_reporting(E_ALL);
$main_page = $_SESSION['root_url'];
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:$main_page");
}



echo "Bye Bye";
//echo "<hr>";
////print_r($_SESSION);
//echo "<hr>";

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
//echo "<hr>";
//print_r($_SESSION);
//echo "<hr>";
?>
