<?php
session_start();
error_reporting(E_ALL);
/* Αν δεν έχει ανοίξει το συγκεκριμένο SESSION με τον browser του, τότε τον πηγαίνει στην αρχική σελίδα */
if (session_status() == 2 && count($_SESSION) == 0) {
    header("location:index.php");
}

//print_r($_SESSION);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>


        <link rel="stylesheet" href="../bootstrap-4.5.3/css/bootstrap.min.css" >
        <script src="../jquery/3.5.1/jquery-3.5.1.js"></script>
        <script src="../functions/js/functions.js"></script>


        <script type="text/javascript">

            $(document).ready(function () {

                var password = ""
                var email = ""
                var confirm_password = ''
                var check = false
                var action = ""

                var data = <?php echo json_encode($_SESSION['login']); ?>;
                if (data == 0) {
                    before_login();
                } else if (data == 1) {
                    during_login();

                }

                $('#modal_title').text('Change Password')
                $('#modal_button').text('Change Password')
//                $('#password').hide()
//                $('#confirm_password').hide()
//                $('#show').hide()
//
//                action = 'login'
//
//// Εκκαθάριση πεδίων φόρμας login
//                $('.form-control').val('')
//
//
                $('#my_modal').modal({backdrop: 'static', keyboard: false})


            });





        </script>  
        <script>



        </script>
    </head>
    <body>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/navbar.php"); ?>
        <?php include_once( $_SESSION['base_path'] . "/functions/php/modal.php"); ?>


<!--        <script src="jquery/jquery-3.4.1/jquery-3.4.1.slim.min.js"></script>-->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="../bootstrap-4.5.3/js/bootstrap.min.js"></script>


        <main id="main">
            <div class="modal fade" id="my_modal" tabindex="-1" role="dialog" 
                 aria-labelledby="modal_label" aria-hidden="true">
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
                                <form id="my_form">
                                    <div class="form-group">
                                        <input type="email" class="form-control" autocomplete="on"
                                               id="email" placeholder="Your email address...">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" autocomplete="on"
                                               id="email" placeholder="Your email address...">
                                    </div>

                                    <span id="status"></span>
                                    <button type="button" class="btn btn-info btn-block btn-round"  
                                            id="modal_button"></button>
                                </form>
                                <div class="row">         
                                    <div class="col-12 text-center">

                                        <form id="forget_password-form" method="post"  role="form">
                                            <div class="form-group">    
                                                <div class="help-block with-errors"></div>
                                                <div class="text-center">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </main>

        <?php include_once( $_SESSION['base_path'] . "/functions/php/footer.php"); ?>
        <script src="../functions/js/actions.js"></script>
    </body>
</html>
