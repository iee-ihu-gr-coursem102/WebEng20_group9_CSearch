<?php

session_start();
error_reporting(E_ALL);

$main_page = $_SESSION['root_url'];
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:$main_page");
}

include_once ($_SESSION ['base_path'] . "/functions/php/functions.php");
$api_key = $_SESSION['api_key'];
//$email = $_SESSION['email'];
$page = 1;
$per_page = 10;
$city_id = 28999;



/* Παίρνω τα events για αυτήν την πόλη */
$url = 'https://api.songkick.com/api/3.0/metro_areas/' . $city_id . '/calendar.json?'
        . 'apikey=' . $api_key
        . '&page=' . $page
        . '&per_page=' . $per_page;
$json = file_get_contents($url);
$json_data = json_decode($json, true, JSON_PRETTY_PRINT);



/*Αρχικοποιώ μεταβλητές σε σχέση με τα δεδομένα που έλαβα*/
$returned_events = count($json_data["resultsPage"]["results"]["event"]);
$event_page = $json_data["resultsPage"]["page"];
$total_events = $json_data["resultsPage"]["totalEntries"];


/* Αν είναι συνδεδεμένος παίρνω τον πίνακα με τα favorite του */
$favorite_events = array();
if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
    $email = $_SESSION['email'];

    try {
        $cxn = get_connection();
        /* Ελέγχουμε για τα favorite του χρήστη */
        $sql_query = "SELECT `event_id` FROM  `favorites`  WHERE `email`='" . $email . "' AND `event_id` IN (";
        for ($i = 0; $i < $returned_events - 1; $i++) {
            $sql_query = $sql_query . $json_data["resultsPage"]["results"]["event"][$i]["id"] . ",";
        }
        $sql_query = $sql_query . $json_data["resultsPage"]["results"]["event"][$i]["id"] . " ) ";
        get_data_from_query_ASSOC($cxn, $favorite_events, $sql_query);
        mysqli_close($cxn);
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}



/* Δημιουργώ το json που θα επιστρέψω */
$events = array();

$row = array();
for ($i = 0; $i < $returned_events; $i++) {

    $upcoming_event_id = $json_data["resultsPage"]["results"]["event"][$i]["id"];


    $event_name = $json_data["resultsPage"]["results"]["event"][$i]["displayName"];
    $event_name = str_replace(" (CANCELLED)", "", $event_name);
    $a = strrpos($event_name, "(", 1);
    $event_name = trim(substr($event_name, 0, $a));

    $start_date = $json_data["resultsPage"]["results"]["event"][$i]["start"]["date"];
    convert_date_format($start_date);
    $start_time = $json_data["resultsPage"]["results"]["event"][$i]["start"]["time"];
    $start_time = substr($start_time, 0, -3);

    $artist = $json_data["resultsPage"]["results"]["event"][$i] ["performance"]["0"]["artist"]["displayName"];
    $artist_uri = $json_data["resultsPage"]["results"]["event"][$i] ["performance"]["0"]["artist"]["uri"];

    $event_type = $json_data["resultsPage"]["results"]["event"][$i]["type"];
    $event_status = strtoupper($json_data["resultsPage"]["results"]["event"][$i]["status"]);

    $event_popularity = $json_data["resultsPage"]["results"]["event"][$i]["popularity"];
    $event_popularity = round((float) $event_popularity * 100, 3) . '%';

    /*Αν είναι συνδεδεμένος τότε ενημερώνω τα επιστρεφόμενα αποτελέσματα με τις προτιμήσεις του*/
    if ($_SESSION['login'] == 1) {
        $favorite = check_if_event_is_favorite($upcoming_event_id, $favorite_events);
    } else {
        $favorite = -1;
    }

    /* Προσθέτω τα στοιχεία του event */
   array_push($row, array(
        "event_id" => $upcoming_event_id,
        "event_name" => $event_name,
        "artist" => $artist,
        "artist_uri" => $artist_uri,
        "event_date" => $start_date,
        "event_time" => $start_time,
        "event_place" => $json_data["resultsPage"]["results"]["event"][$i] ["venue"]["displayName"],
        "event_uri" => $json_data["resultsPage"]["results"]["event"][$i] ["venue"]["uri"],
        "event_type" => $json_data["resultsPage"]["results"]["event"][$i]["type"],
        "event_status" => strtoupper($json_data["resultsPage"]["results"]["event"][$i]["status"]),
        "event_popularity" => $event_popularity,
        "favorite" => $favorite
            )
    );
    
}

/*Δημιουργώ το τελικό προς επιστροφή αντικείμενο*/
$events = array(
    "city_id" => $city_id,
    "returned_events" => $returned_events,
    "totalEntries" => $total_events,
    "page" => $event_page,
    "event_list" => $row);

/*Επιστρέφω τα αποτελέσματα*/
echo json_encode($events, JSON_PRETTY_PRINT);


/*Επιστρέφει τιμή σε σχέση με το αν ένα event είναι favourite*/
function check_if_event_is_favorite($event_id, $event_pool) {
    $pool_length = count($event_pool);
    if ($pool_length == 0)
        return 0;
    for ($i = 0; $i < $pool_length; $i++) {
        if ($event_pool[$i]['event_id'] == $event_id)
            return 1;
    }
    return 0;
}
