<?php
session_start();
error_reporting(E_ALL);


if (!isset($_SESSION['login'])) {
    $login = 0;
} else if ($_SESSION['login'] == 1) {
    $login = 1;
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


        <script type="text/javascript">

            /* BEGIN OF DOCUMENT READY FUNCTION*/

            $(document).ready(function () {

                var password = ""
                var email = ""
                var confirm_password = ''
                var check = false
                var action = ""

                var data = <?php echo json_encode($login); ?>;
                if (data == 0) {
                    before_login();
                } else if (data == 1) {
                    during_login();

                }



            });


        </script>  

    </head>
    <body>

            <?php include_once( $_SESSION['base_path'] . "/functions/php/navbar.php");?>
            <?php include_once( $_SESSION['base_path'] . "/functions/php/modal.php");?>


        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="bootstrap-4.5.3/js/bootstrap.min.js"></script>

        <main id="main">
            <?php include_once( $_SESSION['base_path'] . "/functions/php/carousel.php");?>
        </main>

        <?php include_once( $_SESSION['base_path'] . "/functions/php/footer.php"); ?>
        <script src="functions/js/actions.js"></script>

    </body>
</html>