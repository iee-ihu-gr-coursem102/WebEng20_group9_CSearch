<?php

//session_start();
//error_reporting(E_ALL);

$main_page = $_SESSION['root_url'];
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:$main_page");
}

include_once ($_SESSION ['base_path'] . "/functions/php/functions.php");


$api_key = $_SESSION['api_key'];
$page = 1;
$per_page = 10;

$city = "athens";
$city = "thessaloniki";
$city = "london";
$city = "athens";
$city = "athens";

//print_r($_SESSION);

$city_id = get_city_id($city, $api_key);



$url = 'https://api.songkick.com/api/3.0/metro_areas/' . $city_id . '/calendar.json?'
        . 'apikey=' . $api_key
        . '&page=' . $page
        . '&per_page=' . $per_page;

//echo "$url<hr>";


$json = file_get_contents($url);


/* για να εκτυπωθεί το json έτσι ώστε να γίνεται αντιληπτή η δομή του */
//header('Content-type: text/javascript');
$json_data = json_decode($json, true, JSON_PRETTY_PRINT);
//print_r($json_data);


$number_of_events = count($json_data["resultsPage"]["results"]["event"]);
$upcoming_event_id = $json_data["resultsPage"]["results"]["event"][0]["id"];

/*
 * *Θέλω να εμφανίσω τον καλλιτέχνη, το πότε, τον χώρο, το status, και τη δημοφιλία
 * Καλλιτέχνης:
 * Πότε:
 * Χώρος:
 * Κατάσταση:
 * Δημοφιλία:
 * 
 * Πέρα από αυτά χρειάζομαι
 * upcoming_event_id
 * artist_uri
 * venue_uri
 * 
 *  */

$data = array();
$data_columns = array('A/A', 'ARTIST', 'EVENT', 'PLACE', 'DATE', 'event', 'popularity');

for ($i = 0; $i < $number_of_events; $i++) {

    $row = array();
    $upcoming_event_id = $json_data["resultsPage"]["results"]["event"][$i]["id"];
    $event_name = $json_data["resultsPage"]["results"]["event"][$i]["displayName"];
    $event_name = str_replace(" (CANCELLED)", "", $event_name);
    $a=strrpos  ($event_name,"(",1);        
    $event_name= trim(substr($event_name, 0, $a));
    $event_uri = $json_data["resultsPage"]["results"]["event"][$i]["uri"];
    $event_name = '<a href="' . $event_uri . '">' . $event_name . '</a>';

//    echo $event_name;
    $event_type = $json_data["resultsPage"]["results"]["event"][$i]["type"];
    $event_status = $json_data["resultsPage"]["results"]["event"][$i]["status"];
    $start_date = $json_data["resultsPage"]["results"]["event"][$i]["start"]["date"];

    convert_date_format($start_date);
//    echo $start_date;

    $start_time = $json_data["resultsPage"]["results"]["event"][$i]["start"]["time"];
    $start_time = substr($start_time, 0, -3);

    $artist_id = $json_data["resultsPage"]["results"]["event"][$i]["performance"]["0"]["artist"]["id"];
    $artist = $json_data["resultsPage"]["results"]["event"][$i] ["performance"]["0"]["artist"]["displayName"];
    $artist_uri = $json_data["resultsPage"]["results"]["event"][$i] ["performance"]["0"]["artist"]["uri"];
    $artist = '<a href="' . $artist_uri . '">' . $artist . '</a>';



    $place = $json_data["resultsPage"]["results"]["event"][$i] ["venue"]["displayName"];
    $place_uri = $json_data["resultsPage"]["results"]["event"][$i] ["venue"]["uri"];
    $place = '<a href="' . $place_uri . '">' . $place . '</a>';


    $event_popularity = $json_data["resultsPage"]["results"]["event"][$i]["popularity"];
//    sprintf("%.2f%%", $x * 100)
    $event_popularity = round((float) $event_popularity * 100, 3) . '%';
    array_push($row, ($i + 1), $artist, $event_name, $place, $start_date, $event_status, $event_popularity);
    array_push($data, $row);
//    echo "<br>";
}

//print_r($data);
$str = populate_table($data, $data_columns);
echo $str;

function populate_table($json_data, $data_columns) {
//    $commited = $_SESSION['commited'];
    /* creates Datatable content */
    $str = "\n";
    $str = $str . "<table id=\"example\" "
            . "class=\"table table-striped table-bordered \" "
            . "style=\"width:100%\">" . "\n";

    $str = $str . "<thead>" . "\n";

    $str = $str . "<tr>" . "\n";
    $th = array("Προτίμηση", "Σχολική Μονάδα", "", "", "");
    $th = array('A/A', 'ARTIST', 'EVENT', 'PLACE', 'DATE', 'STATUS', 'POPULARITY');

    for ($i = 0; $i < count($th); $i++) {
//        $str .= "<th id=\"$i\"  >$th[$i]</th>" . "\n";
        $str .= "<th id=\"$i\" style=\"min-width:40px\" >$th[$i]</th>" . "\n";
    }

    $str .= "</tr>";
    $str .= "</thead>" . "\n";
    $str = $str . "<tbody id=\"datatable_body\" >" . "\n";


    for ($i = 0; $i < count($json_data); $i++) {
        $str = $str . "<tr id=\"row_" . $i . "\">" . "\n";

        for ($k = 0; $k < count($data_columns); $k++) {
            $str = $str . "<td  style=\"font-size: 0.9em;\">" .
//                    $json_data[$i][$data_columns[$k]] . "</td>" . "\n";
                    $json_data[$i][$k] . "</td>" . "\n";
        }

        /* Εάν δεν έχει κάνει commit τότε εμφανίζονται τα εικονίδια αλλιώς */
//        if ($commited == 0) {
//            /* Εικονίδιο διαγραφής σχολείου */
//            $str = $str . ' <td style="text-align:center;">   '
//                    . '    <img src="/teacher_services/images/remove_delete/Trash-icon_24.png"  '
////                    . ' class="img-fluid"  '
//                    . ' class="img-fluid"  '
//                    . '  id="school_del' . ($i + 1) . '"'
//                    . '  name="school_del' . ($i + 1) . '"'
//                    . ' title="Διαγραφή"  '
//                    . ' > </td>' . "\n";
//
//            /* Εικονίδιο Μετακινησης επάνω */
//            $str = $str . ' <td style="text-align:center;">   '
//                    . '    <img src="/teacher_services/images/move_up/arrow-circle-top-3x.webp"  '
//                    . ' class="img-fluid"  '
//                    . '  id="school_up' . ($i + 1) . '"'
//                    . '  name="school_up' . ($i + 1) . '"'
//                    . ' title="Μετακίνηση επάνω"  '
//                    . ' > </td>' . "\n";
//
//            /* Εικονίδιο Μετακίνησης κάτω */
//            $str = $str . ' <td style="text-align:center;">   '
//                    . '    <img src="/teacher_services/images/move_down/arrow-circle-bottom-3x.webp"  '
//                    . ' class="img-fluid"  '
//                    . '  id="school_down' . ($i + 1) . '"'
//                    . '  name="school_down' . ($i + 1) . '"'
//                    . ' title="Μετακίνηση κάτω"  '
//                    . ' > </td>' . "\n";
//        } else {
//            $str = $str . ' <td style="text-align:center;">    </td>' . "\n";
//            $str = $str . ' <td style="text-align:center;">    </td>' . "\n";
//            $str = $str . ' <td style="text-align:center;">    </td>' . "\n";
//        }
    }


    $str .= "</tbody>" . "\n";
    $str .= "</table>" . "\n";
    return $str;
}
