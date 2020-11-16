<?php

session_start();
error_reporting(E_ALL);
//    Αποθηκεύω σε μεταβλητή SESSION το path για τη ρίζα
$_SESSION['base_path'] = __DIR__;
//echo $_SESSION['base_path'] . "<br>";

//if ($_SESSION['base_path'] == '/var/www/html/WebEng20_group9_CSearch') {
//    $_SESSION['root_url'] = '/WebEng20_group9_CSearch';
//} elseif ($_SESSION['base_path'] == '/home/student/ait/2020/ait292020/public_html/CSearch') {
//    $_SESSION['root_url'] = '/';
//} else {
//    echo "cannot find base_path" . "<br>";
//}



$_SESSION['api_key'] = 'RmJpmyc8RlY74n1I';

//include( $_SESSION['base_path'] . "/useful_functions/useful_functions.php");
$api_key=$_SESSION['api_key'];
$page=1;

//Παίρνω το id της πόλης
$city = "london";
$city = "thessaloniki";
$city = "athens";
$url = 'https://api.songkick.com/api/3.0/search/locations.json?query=' . $city . '&apikey=RmJpmyc8RlY74n1I';
$data = file_get_contents($url);
$obj = json_decode($data, true);
$metroAreaId = $obj['resultsPage']['results']['location'][0]['metroArea']['id'];
//echo $metroAreaId;
//echo json_encode($data);
$url='https://api.songkick.com/api/3.0/metro_areas/'.$metroAreaId
        .'/calendar.json?'.'apikey='.$api_key.'&page='.$page;
//echo gettype($url)."<hr>";
//echo "$url<hr>";

//$url='https://api.songkick.com/api/3.0/metro_areas/28999/calendar.json?apikey=RmJpmyc8RlY74n1I';


$json = file_get_contents($url);

 header('Content-type: text/javascript');//για να εκτυπωθεί το json έτσι ώστε να γίνεται αντιληπτή η δομή του
$json_data = json_decode($json, true,JSON_PRETTY_PRINT);//Json_data is array

$status=$json_data["resultsPage"]["status"];
echo "status: ". $json_data["resultsPage"]["status"];
echo "<hr>\n";
$number_of_events=count($json_data["resultsPage"]["results"]["event"]);
echo "events:$number_of_events<hr>\n";
$upcoming_event_id=$json_data["resultsPage"]["results"]["event"][0]["id"];
echo "upcoming_event_id:$upcoming_event_id ";
for ($i=0;$i<$number_of_events;$i++){
    $upcoming_event_id=$json_data["resultsPage"]["results"]["event"][$i]["id"];
    $display_name=$json_data["resultsPage"]["results"]["event"][$i]["displayName"];
    $type=$json_data["resultsPage"]["results"]["event"][$i]["type"];
    $status=$json_data["resultsPage"]["results"]["event"][$i]["status"];
    $start_date=$json_data["resultsPage"]["results"]["event"][$i]["start"]["date"];
    $start_time=$json_data["resultsPage"]["results"]["event"][$i]["start"]["time"];
    
    
        $artist=$json_data["resultsPage"]["results"]["event"][$i]["performance"]["0"]["displayName"];
        $no_of_artists=count($json_data["resultsPage"]["results"]["event"][$i]["performance"]);


    echo "$i\t$upcoming_event_id";
    echo "$i\t$display_name";
    echo "$i\t $type";
    echo "$i\t $status";
    echo "$i\t $start_date";
    echo "$i\t $start_time";
    echo "$i\t $artist";
    echo "$i\t $no_of_artists";
    echo "\n";
}
//echo "upcoming_event_id: ". $json_data["resultsPage"]["results"]["event"][0]["id"];

//echo "<hr>";
//echo "<hr>";
//echo "<hr>";


//print_r($json_data);
//
//
//$ch = curl_init();
//// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
//// in most cases, you should set it to true
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_URL, $url);
//$result = curl_exec($ch);
//curl_close($ch);
//
//$obj = json_decode($result);
//echo $obj->results['status'];
echo "<hr>";

print_r($json_data);
//echo "xx: ". $json_data["status"];


//$data = file_get_contents($url);
//echo gettype($data);
//echo json_encode($data);
//echo $data;
//echo json_decode($data);
