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

