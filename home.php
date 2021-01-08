<?php
session_start();
//error_reporting(E_ALL);

/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:index.php");
}

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
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="bootstrap-4.5.3/js/bootstrap.min.js"></script>

        <style type="text/css" class="init">
            



        </style>



        <script type="text/javascript">
            city_id = 24426;//london
            city_id = 4120;//athens
            var city_id = 28999;//thessaloniki

            var page_number = 1;
            var results_per_page = 20;
            var is_loged_in = <?php echo json_encode($_SESSION['login']); ?>;
            var action = ""


            /* BEGIN OF DOCUMENT READY FUNCTION*/
            $(document).ready(function () {

                var password = ""
                var email = ""
                var confirm_password = ''
                var check = false
//                var action = ""

                if (is_loged_in == 0) {
                    before_login();
                } else if (is_loged_in == 1) {
                    during_login();

                }
            });


        </script>  

    </head>
    <body>

        <?php include_once( $_SESSION['base_path'] . "/functions/php/navbar.php"); ?>

        <!--<div class="jumbotron  jumbotron-fluid text-center"></div>-->



        <div id="divLoading" class="container-fluid"></div>

        <main id="main" class="container-fluid">
            <?php include_once( $_SESSION['base_path'] . "/functions/php/carousel.php"); ?>

            <div id="main_div" class="container-fluid"></div>

        </main>


        <?php include_once( $_SESSION['base_path'] . "/functions/php/modal.php"); ?>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/alert_modal.php"); ?>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/footer.php"); ?>



        <script src="functions/js/actions.js"></script>
        <link href="css/loading.css" rel="stylesheet">
        <link href="css/my_css.css" rel="stylesheet">


    </body>
</html>