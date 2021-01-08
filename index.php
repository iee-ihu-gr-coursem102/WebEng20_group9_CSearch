<?php

session_start();
//error_reporting(E_ALL);


/* Αποθηκεύω σε μεταβλητή SESSION το path για τη ρίζα */
$_SESSION['base_path'] = __DIR__;

/* Ανάλογα με το ποια είναι η διαδρομή στον υπολογιστή που φιλοξενείται ορίζω το $_SESSION['root_url'] */
if ($_SESSION['base_path'] == "/var/www/html/WebEng20_group9_CSearch") {
    $_SESSION['root_url'] = '/WebEng20_group9_CSearch';
} elseif ($_SESSION['base_path'] == "/var/www/html/webeng20g9") {
    $_SESSION['root_url'] = '/webeng20g9';
}
//echo $_SESSION['base_path'];

$json_data = file_get_contents($_SESSION['base_path'] . "/config.json");
$json_object = json_decode($json_data, true);



$_SESSION['server'] = $json_object['server'];
$_SESSION['db'] = $json_object['db'];
$_SESSION['username'] = $json_object['username'];
$_SESSION['password'] = $json_object['password'];
$_SESSION['sender'] = $json_object['sender'];
$_SESSION['smtp'] = $json_object['smtp'];
$_SESSION['port'] = $json_object['port'];
$_SESSION['api_key'] = $json_object['api_key'];

if (!isset($_SESSION['login']) ) {
    $_SESSION['login'] = 0;
}


header('location:home.php');
