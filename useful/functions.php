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
