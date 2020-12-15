<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




/**
 * Συνάρτηση για να συνδεθεί με τη βάση με τη χρήση του λογαριασμού "simple_user"
 * @return type
 * @throws Exception
 */
function get_connection() {
    $server = $_SESSION['server'];
    $db = $_SESSION['db'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];

    try {
        $cxn = mysqli_connect($server, $username, $password, $db) or die("cannot connect to database");
        $cxn->query("SET NAMES 'utf8'");
        if (!$cxn) {
            throw new Exception(mysql_error());
        }
    } catch (Exception $e) {
        echo '<script language="javascript">alert("' . $e->getMessage() . '")</script>';
    }
    return $cxn;
}



/**
 * Επιστροφή δεδομένων σαν Associative Array 
 * @param type $cxn    
 * @param type $data
 * @param type $query
 * @return type
 */
function get_data_from_query_ASSOC($cxn, &$data, $query) {
//echo $query."<br>";
    try {
        /* Select queries return a resultset */
        if ($result = $cxn->query($query)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            /* free result set */
            $result->close();
        }
    } catch (Exception $e) {
        echo $e->getMessage(), "<br>", $sql_query, "<br>", "<br>";
    }
    return;
}


/**
 *  * Επιστροφή δεδομένων σαν Numeric Array 
 * @param type $cxn
 * @param type $data
 * @param type $query
 * @return type
 */
function get_data_from_query_NUM($cxn, &$data, $query) {
//echo $query."<br>";
    try {
        /* Select queries return a resultset */
        if ($result = $cxn->query($query)) {
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $data[] = $row;
            }
            /* free result set */
            $result->close();
        }
    } catch (Exception $e) {
        echo $e->getMessage(), "<br>", $sql_query, "<br>", "<br>";
    }
    return;
}



/**
 * Αλλάζει τη μορφή ενός string date
 * @param string $date
 * @return type
 */
function convert_date_format(&$date) {
    $a = strpos($date, '-');
    $length = $a - 0;
    $dd = substr($date, 0, $length);

    $b = strpos($date, '-', $a + 1);
    $length = $b - ($a + 1);
    $mm = substr($date, $a + 1, $length);

    $length = strlen($date) - ($b + 1);
    $yy = substr($date, $b + 1, $length);
    $date = $yy . "/" . $mm . "/" . $dd;
    return;
}


/**
 * Παίρνω το city_id 
 * @param type $city
 * @param type $api_key
 * @return type
 */
function get_city_id($city, $api_key) {
    //Παίρνω το id της πόλης
    $url = 'https://api.songkick.com/api/3.0/search/locations.json?query=' . $city . '&apikey=' . $api_key;
    $data = file_get_contents($url);
    $obj = json_decode($data, true);
    return $obj['resultsPage']['results']['location'][0]['metroArea']['id'];
}



/**
 * Ελέγχει την ύπαρξη του χρήστη
 * @param type $cxn
 * @param type $email
 * @return int
 */
function check_user_existance($cxn,  $email) {
    $sql_data = array();
    /* Ελέγχουμε αν το user_name ανταποκρίνεται σε χρήστη */
    $sql_query = "SELECT `email` FROM `users` WHERE `email`='$email' AND `active`=1 ;";
    get_data_from_query_ASSOC($cxn, $sql_data, $sql_query);
    /* Αν δεν υπάρχει ο χρήστης πρέπει επιστρέψει πίσω κωδικό λάθους */
    if (count($sql_data) == 0) {
        return 0;
    } else {
        return 1;
    }
}
