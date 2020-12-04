<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** Συνάρτηση για να συνδεθεί με τη βάση με τη χρήση του λογαριασμού "simple_user" */
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

function get_data_from_query_ASSOC($link, &$data, $query) {
//echo $query."<br>";
    try {
        /* Select queries return a resultset */
        if ($result = $link->query($query)) {
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

function get_data_from_query_NUM($link, &$data, $query) {
//echo $query."<br>";
    try {
        /* Select queries return a resultset */
        if ($result = $link->query($query)) {
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

function get_result($result, &$colNames, &$data) {
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        //get the column names into an array $colNames
        if ($i == 0) {
            foreach ($row as $colname => $val) {
                $colNames[] = $colname;
            }
        }
        //get raw data into array $data
        $data[] = $row;
        $i++;
    }
    return;
}

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



function get_city_id($city, $api_key) {
    //Παίρνω το id της πόλης
    $url = 'https://api.songkick.com/api/3.0/search/locations.json?query=' . $city . '&apikey=' . $api_key;
    $data = file_get_contents($url);
    $obj = json_decode($data, true);
    return $obj['resultsPage']['results']['location'][0]['metroArea']['id'];
}



// Generates a strong password of N length containing at least one lower case letter,
// one uppercase letter, one digit, and one special character. The remaining characters
// in the password are chosen at random from those four sets.
//
// The available characters in each set are user friendly - there are no ambiguous
// characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
// makes it much easier for users to manually type or speak their passwords.
//
// Note: the $add_dashes option will increase the length of the password by
// floor(sqrt(N)) characters.

function generate_password($length = 9, $add_dashes = false, $available_sets = 'luds') {
    $sets = array();
    if (strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if (strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if (strpos($available_sets, 'd') !== false)
        $sets[] = '1234567890';
    if (strpos($available_sets, 's') !== false)
        $sets[] = '!@#$%?';

    $all = '';
    $password = '';
    foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
    }

    $all = str_split($all);
    for ($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];

    $password = str_shuffle($password);

    if (!$add_dashes)
        return $password;

    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while (strlen($password) > $dash_len) {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}

