/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(function ($) {

    var check = false;
    var email = '';
    var password = '';


    /*Αν κλείσει το modal με επιστρέφει στην αρχική σελίδα*/
    $("#my_modal").on('hide.bs.modal', function () {
        window.location.href = "../index.php";
    });
    

    $("#alert_modal").on('hide.bs.modal', function () {
        window.location.href = "../index.php";
    });

    $('#alert_modal_ok').on('click', function ()
    {
        window.location.href = "../index.php";
    });


    /*Ανάθεση τιμών στα πεδία του modal*/
    $('#modal_title').text('Change Password')
    $('#modal_button').text('Change Password')
    $('#my_modal').modal({backdrop: 'static', keyboard: false})


    /*Ενέργεια που γίνεται όταν κάνω κλικ στο κουμπί του modal*/
    $('#modal_button').on('click', function ()
    {
        /*Ελέγχω αν τα δεδομένα είναι έγκυρα*/
        check = check_modal_data('change_password');

        /*Αν τα δεδομένα είναι έγκυρα τα στέλνω στη βάση*/
        if (check) {          
            post_user_data($('#email').val(), $('#password').val(), '../change_password/send_message.php')
        }

    });


    function post_user_data(email, password) {
        var dataString = '&email=' + email + '&password=' + password;
        $.ajax({
            type: "POST",
            dataType: "text",
            url: '../change_password/send_message.php',
            data: dataString,
            success: function (response) {
                if (response === "TIMEOUT") {
                    $('#alert_modal_modal').text("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά");
                    $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
                }
                var return_message = $("<p/>").html(response).text().trim();
                handle_post_user_data(return_message)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#alert_modal_modal').text("ajax problem");
                $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
            }

        });

    }




});

