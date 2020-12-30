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


    /**
     * Διαχειρίζεται την έξοδο από την εφαρμογή
     */
    $('#exit_button').on('click', function () {
        window.location.href = "login/logout.php";
    });


    /**
     * Χειρίζεται το click στο nav-link
     */

    $('.nav-link').on('click', function () {
        var link = $(this).prop('href')
        var action = link.substring(link.lastIndexOf('#') + 1);
        navigator(action);
    });


    /**
     * Για περιήγηση μεταξύ των επιλογών του Navbar
     * @param {type} action : Η επιλογή που κάναμε κλικ
     * @returns {undefined}
     */
    function navigator(action) {
        switch (action) {
            case "gotohome":
                $("#carouselExampleCaptions").show()
                $("#main_div").empty();
                break;

            case "about":
                $("#alert_modal_title").text('About Us')
                var msg = "We are postgraduate students of the <br><b>International University of Greece</b>"
                msg = msg + "<br> of the"
                msg = msg + "<br><b>Department of Computer and Electronic Systems Engineering</b>"
                msg = msg + "<br>In the context of the work in the lesson"
                msg = msg + "<br><b>Software Engineering for Web Applications</b>"
                msg = msg + "<br>we created this site, which provides us with information"
                msg = msg + "<br>about musical events that will take place in"
                msg = msg + "<br><b>Thessaloniki</b>"
                $('#alert_modal_modal').text('');
                $('#alert_modal_modal').html(msg);
                $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
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
        /* Αρχικοποίηση modal*/
        initialize_modal();

        /*Έλεγχος εμφάνισης πεδίων*/
        $('#confirm_password').show();
        $("#accept_div").show()

        /*Ανάθεση τιμών στα πεδία*/
        $('#modal_title').text('Sign Up')
        $('#modal_button').text('Sign Up')
        $("#password").attr("placeholder", "Set your password");

        $("#checkbox_accept").prop("checked", false);
        $('#my_modal').modal({backdrop: 'static', keyboard: false})
        action = 'signup'
    });



    /**
     * Ενέργειες που εκτελούνται όταν κάνω κλικ στο Login
     * για εισαγωγή χρήστη στο σύστημα*/
    $('#login_button').on('click', function () {
        /* Αρχικοποίηση modal*/
        initialize_modal()
        $('#forgot_password_div').show()

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
        initialize_modal();

        /*Ανάθεση τιμών στα πεδία του modal*/
        $('#modal_title').text('Change Password');
        $('#modal_button').text('Change Password');
        $("#password").attr("placeholder", "Set your new password");
        $('#confirm_password').show()
        $('#my_modal').modal({backdrop: 'static', keyboard: false})
        action = 'change_password';

//        window.location.replace('change_password/reset_pass.php')
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
                status_fill("Please fill in all the fields");
            } else if (!email.match(emailformat)) {
                status_fill("Email is not acceptable");

            } else {
                check = true
            }

        } else if (action == 'signup') {
            if (email == "" || password == "" || confirm_password == "") {
                status_fill("Please fill in all the fields");
            } else if (password != confirm_password) {
                status_fill("Passwords don't match")

                $('#password').val('');
                $('#confirm_password').val('');
            } else if (!email.match(emailformat)) {
                status_fill("Email is not acceptable");

            } else if ($("#checkbox_accept").prop('checked') != true) {
                status_fill("Please read and accept the terms of use");
            } else {
                check = true
            }
        } else if (action == 'change_password') {
            if (email == "" || password == "" || confirm_password == "") {
                status_fill("Please fill in all the fields");

            } else if (password != confirm_password) {
                status_fill("Passwords don't match")
                $('#password').val('');
                $('#confirm_password').val('');
            } else if (!email.match(emailformat)) {
                status_fill("Email is not acceptable")
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
            } else if (action == 'change_password') {

                post_data(email, password, 'change_password/send_message.php')
//                post_user_data($('#email').val(), $('#password').val(), '../change_password/send_message.php')

            }
        }

    });



    /**
     * Εμφανίζει το πεδίο status με το προειδοποιητικό μήνυμα message
     * @param {type} message
     * @returns {undefined}
     */
    function status_fill(message) {
        $("#status").text(message).css("color", "red").css("fontSize", "2rem");

    }

    /**
     * Ajax κλήση για login ή signup
     * @param {type} email
     * @param {type} password
     * @param {type} my_url: το αντίστοιχο script που θα τρέξει
     * @returns {undefined}
     */
    function post_data(email, password, my_url) {
        var dataString = '&email=' + email + '&password=' + password;
        $.ajax({
            type: "POST",
            dataType: "text",
            url: my_url,
            data: dataString,
            beforeSend: function () {
                $("div#divLoading").addClass('show');
            },
            success: function (response) {
                $("div#divLoading").removeClass('show');
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
                } else if (my_url == 'change_password/send_message.php') {
                    change_password_handle(return_message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
//                $('#alert_modal_modal').text(xhr.status + " " + thrownError);
                $('#alert_modal_modal').text("ajax problem");
                $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});

            }


        });

    }


    /**
     * Αρχικοποιεί το modal
     * @returns {undefined}
     */
    function initialize_modal() {
        /* Αρχικοποιώ τις τιμές στο modal*/
        $('#confirm_password').hide()
        $('#accept_div').hide()
        $('#forgot_password_div').hide()

        $('.form-control').val('')
        $("#modal_title").text("");
        $("#modal_button").text("");
        $("#checkbox_accept").prop("checked", false);
        $('#password').val('')
        $('#confirm_password').val('')
        $('#forgot_password_div').val('')

        $('#status').text('')
        check = false;

    }



    /**
     * Κλείνει το alert modal
     */
    $('#alert_modal_ok').on('click', function () {
        $('#alert_modal_modal').text('');
        $("#alert_modal_title").text('Alert Message')
        $('#alert_modal_modal').html("<p></p>");
        $("#alert_modal").modal('hide');
    });


    /**
     * Χειρίζεται το αποτέλεσμα της κλήσης login
     * @param {type} return_message: το επιστρεφόμενο μήνυμα
     * @returns {undefined}
     */
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


    /**
     * Χειρίζεται το αποτέλεσμα της κλήσης signup
     * @param {type} message: το επιστρεφόμενο μήνυμα
     * @returns {undefined}
     */
    function signup_handle(message) {
        $('#alert_modal_modal').text(message);
        $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
    }



    function  change_password_handle(return_message) {
        console.log(return_message)
        $("#my_modal").hide()
        $('#alert_modal_modal').text(return_message);
        $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
    }



});
