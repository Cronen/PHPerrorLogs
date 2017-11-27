
/*
 ** FUNKTIONER TIL TABLETRIGGERS
 ** tbl = database table
 ** tbl_id is value of primary key from tbl, e.g. error_ID = {tbl_id} where tbl = 'php_error'
 */

function pro_delete(obj, tbl, tbl_id)
{
    var state = obj.attr('data-state');

    //check state for working
    if (state == 'working')
    {
        alert('Systemet arbejder, vent venligst');
        return;
    }

    //failsafe
    if (state != 'ready')
    {
        alert('Fejl i state, handling afbrudt');
        return;
    }

    //check state for error
    if (state == 'error')
    {
        alert('Der skete en fejl. Handling afbrudt');
        return;
    }

    //set state to working
    obj.attr('data-state', 'working');
    obj.attr('value', 'Arbejder');

    //skift klasser på obj
    obj.removeClass('btn-danger btn-info btn-success')
            .addClass('btn-warning');

    var action = obj.attr('data-action');

    var salt = 'dankdankdank';
    $.post('/includes/ajax/pro_handling_ajax.php?salt=' + salt, {'action': action, 'tbl': tbl, 'tbl_id': tbl_id}, function (data) {

        alert(data);

        //all is well
        obj.attr('data-state', 'ready');

        //restore button
        obj.removeClass('btn-danger btn-info btn-warning btn -succes')
                .addClass('btn-success');
        
        //fade after delete
        $('#'+tbl_id).fadeOut(700, function() {
            $('#'+tbl_id).remove();
        });


    })

            .fail(function (obj) {
                alert(obj.responseText);
            });

}

/*
 ** FUNKTIONER TIL SORTERING AF TABLE DATA
 */
function pro_sort_level(obj)
{
    var state = obj.attr('data-state');
    var action = "pro_sort";
    var sort_by = "php_error_level";

    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'sort': sort_by, 'order': state});

    button_color_change(obj);

    asc_to_desc(obj);

}
function pro_sort_date(obj)
{
    var state = obj.attr('data-state');
    var action = "pro_sort";
    var sort_by = "error_date";

    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'sort': sort_by, 'order': state});

    button_color_change(obj);

    asc_to_desc(obj);
}

function pro_sort_site(obj)
{
    var state = obj.attr('data-state');
    var action = "pro_sort";
    var sort_by = "error_location";

    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'sort': sort_by, 'order': state});

    button_color_change(obj);

    asc_to_desc(obj);
}

//Funktion til at markere hvilken knap der er aktiv samt skift af farve, når den ikke er aktiv. 
function button_color_change(obj)
{
    $('.sort-button').removeClass('btn-danger btn-info btn-warning btn-primary btn-success').addClass('btn-primary');

    //restore button
    obj.removeClass('btn-danger btn-info btn-warning btn-primary btn-success')
            .addClass('btn-info');
}

function asc_to_desc(obj)
{
    if (obj.attr('data-state') == "asc")
    {
        obj.attr('data-state', 'desc');
    } else
    {
        obj.attr('data-state', 'asc');
    }

}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});


