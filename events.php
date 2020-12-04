<?php
session_start();
error_reporting(E_ALL);
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:index.php");
}

$cities = array(
    0 => "thessaloniki",
    1 => "athens",
    2 => "london"
);

//    echo $_SESSION['login'];
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
            var results_per_page = 10;
            var is_loged_in = <?php echo json_encode($_SESSION['login']); ?>;

            /* BEGIN OF DOCUMENT READY FUNCTION*/

            $(document).ready(function () {

                var password = ""
                var email = ""
                var confirm_password = ''
                var check = false
                var action = ""


                if (is_loged_in == 0) {
                    before_login()
                } else if (is_loged_in == 1) {
                    during_login()

                }


                get_events_from_city_json(city_id, page_number, results_per_page);


            });
            
            
      
      



        </script>  
        <script>

//            function handle_like(id) {
//                if ($("#" + id).attr('src') === 'img/accept/checked.png') {
//                    $("#" + id).attr("src", "img/accept/to_check.png");
//
//                } else {
//                    $("#" + id).attr("src", "img/accept/checked.png");
//                }
//
//            }

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