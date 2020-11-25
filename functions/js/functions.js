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

function create_href(name, uri) {
    if(uri==null) return name;
    return '<a  href="'.concat(uri)
            .concat('">')
            .concat(name)
            .concat('<\a>');
}