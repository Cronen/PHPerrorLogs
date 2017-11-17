
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
    })

            .fail(function (obj) {
                alert(obj.responseText);
            });
}

/*
 ** FUNKTIONER TIL SORTERING AF TABLE DATA
 *
 */
function pro_sort_level()
{

}
function pro_sort_date()
{

}

function pro_sort_site()
{

}
function pro_sort_all()
{
    var action = "pro_sort_all";
    $("#test").load('/includes/ajax/pro_handling_ajax.php?', {'action': action});
}

