/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function check_modal_data(action) {
    var check = false;
    var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    email = $('#email').val().toLowerCase();
    password = $('#password').val()
    confirm_password = $('#confirm_password').val()


    /*Χειρισμός Σφαλμάτων Δεδομένων που καταχωρούνται στις φόρμες Login/SignUP*/
    if (action == 'login') {
        if (email == "" || password == "") {
            document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
        } else if (!email.match(emailformat)) {
            document.getElementById("status").innerHTML = "Το mail δεν είναι αποδεκτό";
        } else {
            check = true
        }

    } else if (action == 'sign_up') {
        if (email == "" || password == "" || confirm_password == "") {
            document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
        } else if (password != confirm_password) {
            document.getElementById("status").innerHTML = "Τα πεδία κωδικού  πρόσβασης δεν ταιριάζουν";
        } else if (!email.match(emailformat)) {
            document.getElementById("status").innerHTML = "Το mail δεν είναι αποδεκτό";
        } else if ($("#checkbox_accept").prop('checked') != true) {
            document.getElementById("status").innerHTML = "Παρακαλώ διαβάστε και αποδεχτείτε τους όρους";
        } else {
            check = true
        }
    } else if (action == 'change_password') {
        if (email == "" || password == "" || confirm_password == "") {
            document.getElementById("status").innerHTML = "Συμπληρώστε όλα τα πεδία της φόρμας";
        } else if (password != confirm_password) {
            document.getElementById("status").innerHTML = "Τα πεδία κωδικού  πρόσβασης δεν ταιριάζουν";
        } else if (!email.match(emailformat)) {
            document.getElementById("status").innerHTML = "Το mail δεν είναι αποδεκτό";
        }
        {
            check = true
        }
    }

    return check;
}

function handle_post_user_data(return_message) {
    $("#my_modal").hide()
    $('#alert_modal_modal').text(return_message);
    $("#alert_modal").modal({"backdrop": "static", "keyboard": true, "show": true});
}





