/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(function ($) {
    var password = ""
    var email = ""
    var confirm_password = ''
    var check = false
//    var action = ""


    //Φόρμα: όροι χρησης εφαρμογής  
    $("#terms").hide();
    $("#show").click(function () {
        $("#terms").slideToggle()();
    });




    $('#exit_button').on('click', function () {
        logout_handle();
//        post_data("email", "password", 'login/logout.php')

    });


    $('.nav-link').on('click', function () {
        var link = $(this).prop('href')
        var action = link.substring(link.lastIndexOf('#') + 1);
        navigator(action);
    });


    /*Για περιήγηση μεταξύ των επιλογών του Navbar*/
    function navigator(action) {
        switch (action) {
            case "gotohome":
                $("#carouselExampleCaptions").show()
                $("#main_div").empty();
                break;

            case "about":
                $("#carouselExampleCaptions").hide()
                $("#main_div").empty();
                $("#main_div").append("<p>About us</p>")
                break;

            case "getevents":
                $("#carouselExampleCaptions").hide()
                $("#my_table").empty();
                $("#my_table").remove();
                $("#main_div").empty();
                $("#main_div").append('<div id="my_table"></div>');
                get_events_from_city_json(city_id, page_number, results_per_page);

                break;

            default:
                console.log("what choise is that action:" + action);
        }

    }


    /**
     * Ενέργειες που εκτελούνται όταν κάνω κλικ στο Sign_Up
     * για εγγραφή νέου χρήστη
     */
    $('#sign_up_button').on('click', function () {
        /* Εκκαθάριση πεδίων φόρμας modal*/
        $('.form-control').val('')
        /*Έλεγχος εμφάνισης πεδίων*/
        $('#password').show()
        $('#confirm_password').show()
        $("#accept_div").show()
        $('#forgot_password_div').hide()
        $("#terms").hide();

        /*Ανάθεση τιμών στα πεδία*/
        $('#modal_title').text('Sign Up')
        $('#modal_button').text('Sign Up')
        $("#checkbox_accept").prop("checked", false);
        $('#my_modal').modal({backdrop: 'static', keyboard: false})
        action = 'signup'
    });



    /**
     * Ενέργειες που εκτελούνται όταν κάνω κλικ στο Login
     * για εισαγωγή χρήστη στο σύστημα*/
    $('#login_button').on('click', function () {
        /* Εκκαθάριση πεδίων φόρμας modal*/
        $('.form-control').val('')

        /*Έλεγχος εμφάνισης πεδίων*/
        $('#password').show()
        $('#confirm_password').hide()
        $('#forgot_password_div').show()
        $("#accept_div").hide()

        /*Ανάθεση τιμών στα πεδία*/
        $('#modal_title').text('Login')
        $('#modal_button').text('Login')
        $('#my_modal').modal({backdrop: 'static', keyboard: false})
        action = 'login'
    });



    /**
     * Όταν πατάω το ξέχασα τον κωδικό μου με ανακατευθύνει στο change_password/reset_pass.php
     * */
    $('#forgot_password').on('click', function () {
        window.location.replace('change_password/reset_pass.php')
    });


    /**
     * Ενέργειες που εκτελούνται όταν κάνω κλικ στο κουμπί του modal
     * */
    $('#modal_button').on('click', function () {
        email = $('#email').val().toLowerCase()
        password = $('#password').val()
        confirm_password = $('#confirm_password').val()
        var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;


        /*Χειρισμός Σφαλμάτων Δεδομένων που καταχωρούνται στις φόρμες Login/Sign_UP*/
        if (action == 'login') {
            if (email == "" || password == "") {
                document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
            } else if (!email.match(emailformat)) {
                document.getElementById("status").innerHTML = "Το mail δεν είναι αποδεκτό";
            } else {
                check = true
            }

        } else if (action == 'signup') {
            if (email == "" || password == "" || confirm_password == "") {
                document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
            } else if (password != confirm_password) {
                document.getElementById("status").innerHTML = "Τα πεδία κωδικού  πρόσβασης δεν ταιριάζουν";
            } else if (!email.match(emailformat)) {
                document.getElementById("status").innerHTML = "Το mail δεν είναι αποδεκτό";
            } else if ($("#checkbox_accept").prop('checked') != true) {
                document.getElementById("status").innerHTML = "Παρακαλώ διαβάστε και αποδεχτείτε τους όρους";
                $("#status").css('color', 'red');
            } else {
                check = true
            }
        }


        /*Ανάλογα με την ενέργεια που έχω επιλέξει (login/signup/Αλλαγή κωδικού εκτελεί την αντίστοιχη ενέργεια*/
        if (check) {
            $('#my_modal').modal('hide');
            if (action == 'login') {
                post_data(email, password, 'login/login.php')
            } else if (action == 'signup') {
                post_data(email, password, 'login/signup.php')
            }
        }

    });



    function post_data(email, password, my_url) {
        var dataString = '&email=' + email + '&password=' + password;
        $.ajax({
            type: "POST",
            dataType: "text",
            url: my_url,
            data: dataString,
            success: function (response) {
                if (response === "TIMEOUT") {
                    $('#alert_modal_modal').text("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά");
                    $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
                }
                var return_message = $("<p/>").html(response).text().trim();
                initialize_modal();
                if (my_url == 'login/login.php') {
                    login_handle(return_message);
                } else if (my_url == 'login/signup.php') {
                    signup_handle(return_message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
//                $('#alert_modal_modal').text(xhr.status + " " + thrownError);
                $('#alert_modal_modal').text("ajax problem");
                $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});

            }


        });

    }


    function initialize_modal() {
        /* Αρχικοποιώ τις τιμές στο modal*/
        $("#checkbox_accept").prop("checked", false);
        $('#password').val('')
        $('#confirm_password').val('')
        $('#forgot_password_div').val('')
        $('#status').text('')
        check = false;

    }

    /*Κλείνει το alert modal*/
    $('#alert_modal_ok').on('click', function () {
        $("#alert_modal").modal('hide');
    });



    function login_handle(return_message) {
        if (return_message == 'This user does not exist') {
            $('#alert_modal_modal').text("This user does not exist");
            $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});



        } else if (return_message.substring(0, 5) == 'Hello') {
            $('#sign_up_button').hide();
            $('#exit_button').show();
            $('#login_button').hide();

            $(location).attr('href', 'index.php')

        } else {
            $('#alert_modal_modal').text(return_message);
            $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
        }

    }



    function signup_handle(message) {
        $('#alert_modal_modal').text(message);
        $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
    }

    function logout_handle(return_message) {
        window.location.href = "login/logout.php";
    }





});


