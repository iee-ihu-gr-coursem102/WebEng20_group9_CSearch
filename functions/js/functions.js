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
    if (uri == null)
        return name;
    return '<a  href="'.concat(uri)
            .concat('">')
            .concat(name)
            .concat('<\a>');
}


function get_events_from_city_json(city_id, page_number, results_per_page) {
    var dataString = {"city_id": city_id,
        "page_number": page_number,
        "results_per_page": results_per_page};
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "get_results.php",
        data: dataString,
        success: function (response) {
            if (response === "TIMEOUT") {
                alert("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά.");
            }
//                        console.log(typeof (response))
            create_table(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + " " + thrownError);
        }


    });
}



function create_table(response) {

    var total_events = response.totalEntries
    var current_page = response.page
    var returned_events = response.returned_events
    var event = response.event_list

    /* create table*/
    var $table = $('<table></table>').addClass('table table-striped table-bordered table-hover');
    /*Προσθέτω Επικεφαλίδα και γραμμή τίτλων*/
    if (is_loged_in) {
        var headings = ["#", "EVENT", "ARTIST", "PLACE", "DATE", "TIME",  "TYPE", "STATUS", "LIKE"];
    } else {
        var headings = ["#", "EVENT", "ARTIST", "PLACE", "DATE", "TIME",  "TYPE","STATUS"];
    }

    var thead = $table.append('<thead/>').children('thead');
    var row = $('<tr></tr>');
//                for (var i = 0; i < headings.length; i++) {
    for (var i in headings) {
        row.append($('<th></th>').text(headings[i]));
    }
    $table.append(thead.append(row));
    /* create tbody*/
    var $tbody = $table.append('<tbody/>').children('tbody');
    for (i = 0; i < returned_events; i++) {

        var row = $('<tr></tr>')

        row.append($('<td></td>').text((i + 1)));
        /*Event*/
        row.append($('<td>').html('<a href="' + event[i].event_uri + '">'
                + event[i].event_name + '</a>'));
        /*Artist Data*/
        row.append($('<td>').html('<a href="' + event[i].artist_uri + '">'
                + event[i].artist + '</a>'));
        /*Venue Data*/
        if (event[i].event_uri == null) {
            row.append($('<td></td>').text('Unknown venue'));
        }
        if ($.trim(event[i].event_place) == "Unknown venue") {
        } else {
            row.append($('<td>').html('<a href="' + event[i].event_uri + '">'
                    + event[i].event_place + '</a>'));
        }

        /*Date*/
        row.append($('<td></td>').text(event[i].event_date));
        /*Time*/
        if (event[i].event_time == false) {
            row.append($('<td></td>').text('-'));
        } else {
            row.append($('<td></td>').text(event[i].event_time));
        }



        /*Type*/
        row.append($('<td></td>').text(event[i].event_type));
        /*Status*/
        row.append($('<td></td>').text(event[i].event_status));
        /*Popularity*/
//        row.append($('<td></td>').text(event[i].event_popularity));
        /*Event Data*/
        var event_id = event[i].event_id

        /*Check Favorite*/
        if (is_loged_in) {

//                        console.log(i + ")" + event[i].favorite)
            if (event[i].favorite) {
                row.append('<td><img  src=\"img/accept/checked.png\"  onclick=\"handle_like(' + event_id + ')\"  id="' + event_id + '"')

            } else {
                row.append('<td><img  src=\"img/accept/to_check.png\"  onclick=\"handle_like(' + event_id + ')\"  id="' + event_id + '"')

            }


        }


        $tbody.append(row)


    }
    $table.appendTo('#my_table');
}


/*Χειρίζεται τα favorites*/
function handle_like(id) {
    if ($("#" + id).attr('src') === 'img/accept/checked.png') {
        /* Εισάγει στις προτιμήσεις τον κωδικό του event
         και αν η εισαγωγή είναι επιτυχημένη αλλάζει το εικονίδιο*/
        var a = set_favorite(1, id);
        $("#" + id).attr("src", "img/accept/to_check.png");
    } else {
        /* Αφαιρεί από τις προτιμήσεις τον κωδικό του event
         και αν η εισαγωγή είναι επιτυχημένη αλλάζει το εικονίδιο*/
        var a = set_favorite(0, id);
        $("#" + id).attr("src", "img/accept/checked.png");
    }

}

function set_favorite(option, id) {
    $.ajax({
        type: "POST",
        dataType: "text",
        url: 'functions/php/set_favourite.php',
        data: '&action=' + option + '&id=' + id,
        success: function (response) {
            if (response === "TIMEOUT") {
                alert("Το χρονικό όριο σύνδεσης έληξε. Παρακαλώ συνδεθείτε ξανά.");
            }
            var return_message = $("<p/>").html(response).text().trim();
            console.log("return_message:" + return_message)
//                if (my_url == 'login/login.php') {
//        login_handle(return_message);
//        } else if (my_url == 'login/signup.php') {
//        signup_handle(return_message);
//        }

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + " " + thrownError);
        }


    });
}