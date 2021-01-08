<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$base_path = __DIR__;

/* Ανάλογα με το ποια είναι η διαδρομή στον υπολογιστή που φιλοξενείται ορίζω το $_SESSION['root_url'] */
if ($base_path == "/var/www/html/WebEng20_group9_CSearch") {
    $roo_url = '/WebEng20_group9_CSearch';
} elseif ($base_path == "/var/www/html/webeng20g9") {
    $roo_url = '/webeng20g9';
}

include_once ( $base_path . "/functions/php/functions.php");

$json_data = file_get_contents($base_path . "/config.json");
$json_object = json_decode($json_data, true);


$_SESSION = array();

$_SESSION['server'] = $json_object['server'];
$_SESSION['db'] = $json_object['db'];
$_SESSION['username'] = $json_object['username'];
$_SESSION['password'] = $json_object['password'];


/* Προχωράω στην ενημέρωση της Βάσης */
try {
    $interval = 30;
    $cxn = get_connection();
    $cxn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

    $sql_query = "DELETE FROM `users` WHERE `timestamp`> DATE_SUB(NOW(), INTERVAL $interval MINUTE) AND `active`=0;";
    $cxn->query($sql_query);

    $sql_query = "UPDATE `users` SET `temp_passwd`='', `hash`='' WHERE `timestamp`> DATE_SUB(NOW(), INTERVAL $interval MINUTE) AND `active`=1 AND LENGTH(`temp_passwd`) >0";
    $cxn->query($sql_query);



    $cxn->commit();



    $cxn->close();

//        send_mail($password, $email);
} catch (Exception $exc) {
    $cxn->rollback();
    echo "Something wrong happened. Transaction did not completed. Please try again";
//        echo $exc->getTraceAsString();
}


session_destroy();
?>