<?php
session_start();
error_reporting(E_ALL);
//require $_SESSION['base_path'] . "/configuration.php";


$cities = array(
    0 => "thessaloniki",
    1 => "athens",
    2 => "london"
);
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>


        <link rel="stylesheet" href="bootstrap-4.5.3/css/bootstrap.min.css" >
        <script src="jquery/3.5.1/jquery-3.5.1.js"></script>
        <script src="functions/js/functions.js"></script>





        <style type="text/css" class="init">
            body {background-color: activecaption;}
            body {background-color:scrollbar;}

            .img-fluid {
                max-width: 100%;
                height: auto;
            }

            th {
                text-align: center;
                vertical-align: middle;
                font-size: 95%;
            }

            td{
                text-align: center;
                vertical-align: middle;
                font-size: 90%;
            }


            a:link {
                color: black;
                /*background-color: transparent;*/
                text-decoration: underline;
            }

            a:visited {
                color: gray;
                /*background-color: transparent;*/
                text-decoration: underline;
            }

            a:hover {
                color: blue;
                background-color: transparent;
                text-decoration: underline;
            }

            a:active {
                color: graytext;
                background-color: transparent;
                text-decoration: underline;
            }
            
        </style>


        <script type="text/javascript">
            /* thessaloniki id = 28999*/
            var city_id = 28999;
            var page_number = 1;
            var results_per_page = 50;

            /* BEGIN OF DOCUMENT READY FUNCTION*/

            $(document).ready(function () {

                var password = ""
                var email = ""
                var confirm_password = ''
                var check = false
                var action = ""

                var data = <?php echo json_encode($_SESSION['login']); ?>;
//                console.log(data)
                if (data == 0) {
                    before_login()
                } else if (data == 1) {
                    during_login()

                }

                get_events_from_city_json(city_id, page_number, results_per_page);



            });

            function get_events_from_city_string_debug(city_id, page_number, results_per_page) {
//                    console.log("city_id=" + city_id)

                var dataString = '&city_id=' + city_id;
//                        console.log (dataString)

                $.ajax({

                    type: "POST",
                    dataType: "text",
                    url: "get_results.php",
                    data: dataString,
                    success: function (response) {
                        if (response === "TIMEOUT") {
                            alert("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά.");
                        }
                        create_table(response);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }


                });

            }


            function get_events_from_city_json(city_id, page_number, results_per_page) {
//                    console.log("city_id=" + city_id)

                var dataString = '&city_id=' + city_id;
//                        console.log (dataString)

                $.ajax({

                    type: "POST",
                    contentType: "application/json",
                    dataType: "JSON",
                    url: "get_results.php",
                    data: dataString,
                    success: function (response) {
                        if (response === "TIMEOUT") {
                            alert("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά.");
                        }
                        create_table(response);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + " " + thrownError);
                    }


                });

            }




            function create_table(response) {
                var city_id = response.city_id
                var city_name = response.city_name
                var total_events = response.totalEntries
                var current_page = response.page
                var returned_events = response.returned_events
                var event = response.event_list

                /* create table*/
                var $table = $('<table>');
//                $table.addClass("table table-striped table-bordered table-hover table-info table-responsive{-sm}");
                $table.addClass("table table-striped table-bordered table-hover  ");

                /*Προσθέτω Επικεφαλίδα και γραμμή τίτλων*/
                var headings = ["#", "EVENT", "ARTIST",
                    "PLACE", "DATE", "TIME", "STATUS", "TYPE", "POPULARITY"];

                var a = ''
                for (i = 0; i < headings.length; i++) {
                    a = a.concat('<th scope="col class="text-center">'.concat(headings[i]).concat('</th>'))

                }
                $table.append(a)




                /* create tbody*/
                var $tbody = $table.append('<tbody/>').children('tbody');
                for (i = 0; i < returned_events; i++) {
//                    var row = $('<tr>');
                    /*Event Data*/
                    var event_id = event[i].event_id
                    var event_name = create_href(event[i].event_name, event[i].event_uri)

                    /*Artist Data*/
                    var artist = create_href(event[i].artist, event[i].artist_uri);

                    /*Venue Data*/
                    var venue_name = '';
                    console.log(event[i].event_uri)
                    if (event[i].event_uri == null) {
                        // some_variable is either null or undefined
                    }
                    
                    if ($.trim(event[i].event_place) == "Unknown venue") {
                        var venue_name = '-'
                    } else {
                        var venue_name = create_href(event[i].event_place, event[i].event_uri);
                    }


                    var event_date = event[i].event_date
                    var event_time = event[i].event_time
                    if (event_time == false) {
                        event_time = '-'
                    }



                    var event_status = event[i].event_status
                    var event_type = event[i].event_type
                    var event_popularity = event[i].event_popularity


                    var row = '<tr/><th scope="row">'
                            .concat((i + 1))
                            .concat('</th><td>')
                            .concat(event_name)
                            .concat('</td><td>')
                            .concat(artist)
                            .concat('</td><td>')
                            .concat(venue_name)
                            .concat('</td><td>')
                            .concat(event_date)
                            .concat('</td><td>')
                            .concat(event_time)
                            .concat('</td><td>')
                            .concat(event_status)
                            .concat('</td><td>')
                            .concat(event_type)
                            .concat('</td><td>')
                            .concat(event_popularity)
                            .concat('</td></tr>')

                    $tbody.append(row)


                }
                $table.appendTo('#my_table');
//                $('#my_table').addClass("table table-striped table-bordered");


            }




        </script>  
    </head>
    <body>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/navbar.php"); ?>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/modal.php"); ?>


<!--        <script src="jquery/jquery-3.4.1/jquery-3.4.1.slim.min.js"></script>-->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="bootstrap-4.5.3/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        </script>

        <main id="main">


            <div class="jumbotron text-center">     
                <h1>UPCOMING EVENTS</h1> 

            </div>
            <div class="container" id="table_div">
            </div>

            <div id="my_table"></div>

        </main>

        <?php include_once( $_SESSION['base_path'] . "/functions/php/footer.php"); ?>
        <script src="functions/js/actions.js"></script>
    </body>
</html>