<?php
session_start();
error_reporting(E_ALL);

/* Αποθηκεύω σε μεταβλητή SESSION το path για τη ρίζα */

$_SESSION['base_path'] = __DIR__;


require $_SESSION['base_path'] . "/configuration.php";


$cities = array(
    0 => "thessaloniki",
    1 => "athens");


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


        <script type="text/javascript">

            /* BEGIN OF DOCUMENT READY FUNCTION*/

            $(document).ready(function () {

                var password = ""
                var email = ""
                var confirm_password = ''
                var check = false
                var action = ""

                var data = <?php echo json_encode($login); ?>;
                console.log(data)
                if (data == 0) {
                    before_login()
                } else if (data == 1) {
                    during_login()

                }

                /* Γεγονότα που πυροδοτούνται όταν κάνω κλικ σε κάποιο αντικείμενο'*/
                jQuery(function ($) {
//
                    $('#exit_button').on('click', function () {
                        post_data("email", "password", 'login/logout.php')

                    });


                    $('#sign_up_button').on('click', function () {
                        $('#confirm_password').show()
                        $('#modal_title').text('Sign Up')
                        $('#modal_button').text('Sign Up')
                        action = 'signup'
                        $('#my_modal').modal({backdrop: 'static', keyboard: false})
                    });



                    $('#login_button').on('click', function () {
                        $('#modal_title').text('Login')
                        $('#modal_button').text('Login')
                        $('#confirm_password').hide()
                        action = 'login'

                        $('#my_modal').modal({backdrop: 'static', keyboard: false})
                    });



                    $('#modal_button').on('click', function () {
                        email = $('#email').val()
                        password = $('#password').val()
                        confirm_password = $('#confirm_password').val()

                        $('#email').val("")
                        $('#password').val("")
                        $('#confirm_password').val("")
//                        console.log("email=" + email + " password=" + password + " confirm_password=" + confirm_password)

                        /*Εδώ πρέπει να γίνει διαχείριση των δεδομένων που διαβάστηκαν (πχ είναι κενά ή τα password 
                         δεν είναι ίδια και αν όλα είναι καλά η μεταβλητή check να πάρει την τιμή true*/

                        check = true;
                        if (check) {

                            $('#my_modal').modal('hide');
                            if (action == 'login') {
                                post_data(email, password, 'login/login.php')
                            } else {
                                post_data(email, password, 'login/signup.php')
                            }

                        } else {
                            //Πρέπει να βγάλει error σε popup - το αφήνω να το προσπαθήσετε
                            a = 1
                            console.log(check)
                        }

                    });


                    function post_data(email, password, my_url) {
                        var dataString = '&email=' + email + '&password=' + password;
//                        console.log (dataString)
                        $.ajax({

                            type: "POST",
                            dataType: "text",
                            url: my_url,
                            data: dataString,
                            success: function (response) {
                                if (response === "TIMEOUT") {
                                    alert("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά.");
                                }
                                var return_message = $("<p/>").html(response).text().trim();
//                                console.log("return_message:"+return_message)
                                if (my_url == 'login/login.php') {
                                    login_handle(return_message);
                                } else if (my_url == 'login/signup.php') {
                                    signup_handle(return_message);
                                } else if (my_url == 'login/logout.php') {
//                                     console.log("return_message:"+return_message)
                                    logout_handle(return_message);
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + " " + thrownError);
                            }


                        });

                    }


                    function login_handle(return_message) {
//                        console.log(return_message)
//                        console.log(return_message.substring(0, 5))
                        if (return_message == 'This user does not exist') {
                            alert("This user does not exist")
                        } else if (return_message.substring(0, 5) == 'Hello') {

                            $('#sign_up_button').hide();
                            $('#exit_button').show();
                            $('#login_button').hide();
                            alert(return_message)

                        } else {
                            alert(return_message)
                        }

                    }



                    function signup_handle(message) {
                        alert(message);
                        console.log(message)
                    }

                    function logout_handle(return_message) {
                        alert(return_message)

                        console.log(return_message)
                        window.location.replace("index.php");
                    }


                });
                /* END OF DOCUMENT READY FUNCTION*/
            });




        </script>  




    </head>
    <body>


        <!--<nav class="navbar navbar-expand-lg navbar-light bg-light">-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <a class="navbar-brand" href="#">Navbar</a>

            <button class="navbar-toggler" type="button" 
                    data-toggle="collapse" data-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="2nd_page_just_php.php">Events <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">About <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>


                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <button class="btn btn-outline-success my-2 my-sm-0 " 
                                id="login_button" 
                                data-target="#my_modal"
                                >Login</button>
                    </li>
                    <li>
                        <button class="btn btn-outline-success my-2 my-sm-0"
                                id="sign_up_button" 
                                type="submit"
                                >SignUp</button>
                    </li>
                    <li>
                        <button class="btn btn-outline-success my-2 my-sm-0"
                                id="exit_button" 
                                type="submit"
                                >Exit</button>
                    </li>

                </ul>

            </div>
        </nav>





<!--        <script src="jquery/jquery-3.4.1/jquery-3.4.1.slim.min.js"></script>-->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="bootstrap-4.5.3/js/bootstrap.min.js"></script>
        <script type="text/javascript">


            function before_login() {
                $('#exit_button').hide();
                $('#sign_up_button').show();
                $('#login_button').show();
            }
            function during_login() {
                $('#exit_button').show();
                $('#sign_up_button').hide();
                $('#login_button').hide();
            }

        </script>


    </body>
</html>
