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
    var action = ""
    var nav_pas = 0



    window.onhashchange = function () {

        var action = location.hash;

        if (!(nav_pas == 1 && action == "#getevents")) {
            navigator(action.substring(1))
        }
        nav_pas = 0
    }

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


    function navigator(action) {
        nav_pas = 1

        if (location.hash.length == 0) {
            action = "gotohome"
        }

        if (action == "about") {
        }

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
                $("#main_div").empty();


                if (document.getElementById("my_table") == null) {
                    $("#main_div").append('<div id="my_table"></div>');
                    get_events_from_city_json(city_id, page_number, results_per_page);
                }



                break;


            default:
                console.log("what choise is that action:" + action);
//                console.log("line 96 in navigator hash:" + location.hash + " size:" + location.hash.length)
//                console.log("line 96 in navigator href:" + location.href)
        }
    }









    /*Ενέργειες που εκτελούνται όταν κάνω κλικ στο SignUp*/
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


    /*Ενέργειες που εκτελούνται όταν κάνω κλικ στο Login*/
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


    /*Ενέργειες που εκτελούνται όταν κάνω κλικ στο Ξέχασα τον κωδικό μου*/
    $('#forgot_password').on('click', function () {
        /* Εκκαθάριση πεδίων φόρμας modal*/
        $('.form-control').val('')

        /*Έλεγχος εμφάνισης πεδίων*/
        $('#password').hide()
        $('#confirm_password').hide()
        $("#accept_div").hide()
        $('#forgot_password_div').hide()

        /*Ανάθεση τιμών στα πεδία*/
        $('#modal_title').text('Change Password')
        $('#modal_button').text('Change Password')
        $('#confirm_password').hide()
        action = 'change_password'
    });




    /*Ενέργειες που εκτελούνται όταν κάνω κλικ στο κουμπί του modal*/
    $('#modal_button').on('click', function () {
        email = $('#email').val()
        password = $('#password').val()
        confirm_password = $('#confirm_password').val()
        var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        //Χειρισμός Σφαλμάτων Δεδομένων

        if (action == 'login') {
            if (email == "" || password == "") {
                document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
            } else if (!email.match(emailformat)) {
                document.getElementById("status").innerHTML = "Το mail δεν είναι σωστό";
            } else {
                check = true
            }


        } else {
            if (email == "" || password == "" || confirm_password == "") {
                document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
            } else if (password != confirm_password) {
                document.getElementById("status").innerHTML = "Τα πεδία κωδικού  πρόσβασης δεν ταιριάζουν";
            } else if (!email.match(emailformat)) {
                document.getElementById("status").innerHTML = "Το mail δεν είναι σωστό";
            } else if ($("#checkbox_accept").prop('checked') != true) {
                document.getElementById("status").innerHTML = "Παρακαλώ διαβάστε και αποδεχτείτε τους όρους";
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
                console.log("change password")
//                post_data(email, password, 'login/signup.php')
            }

        } else {
            //Πρέπει να βγάλει error σε popup - το αφήνω να το προσπαθήσετε
            a = 1
            console.log(check)
        }

    });


    function post_data(email, password, my_url) {
        var dataString = '&email=' + email + '&password=' + password;
console.log("line 222 my_url:"+my_url)
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
//                                console.log("return_message:"+return_message)
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
            $('#alert_modal_modal').text(return_message);
            $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});

            $(location).attr('href', 'index.php')

        } else {
            $('#alert_modal_modal').text(return_message);
            $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
        }

    }



    function signup_handle(message) {
        $('#alert_modal_modal').text(message);
        $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
        console.log(message)
    }

    function logout_handle(return_message) {
        $('#alert_modal_modal').text('Good Bye');
        $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});

        window.location.href = "login/logout.php";
    }





});


