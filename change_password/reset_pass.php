<?php
session_start();
error_reporting(E_ALL);

/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:../index.php");
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
        <title>Αλλαγή Κωδικού</title>

        <link rel="stylesheet" href="../bootstrap-4.5.3/css/bootstrap.min.css" >
        <script src="../jquery/3.5.1/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="../bootstrap-4.5.3/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div class="modal fade" id="my_modal" tabindex="-1" role="dialog" aria-labelledby="modal_label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-title text-center">
                            <h4 id="modal_title"></h4>
                        </div>
                        <div class="d-flex flex-column text-center">
                            <form id="my_form" autocomplete="off">
                                <div class="form-group">
                                    <input type="email" class="form-control" autocomplete="off"
                                           id="email" placeholder="Your email address...">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" autocomplete="off"
                                           id="password" placeholder="Your password...">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" 
                                           id="confirm_password" placeholder="Confirm password...">
                                </div>

                                <span id="status"></span>
                                <button type="button" class="btn btn-info btn-block btn-round" id="modal_button"></button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script src="../functions/js/reset_pass.js"></script>
        <script src="../functions/js/check_modal_data.js"></script>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/alert_modal.php"); ?>

    </body>
</html>