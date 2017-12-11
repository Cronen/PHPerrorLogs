
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
        $('#' + tbl_id).fadeOut(700, function () {
            $('#' + tbl_id).remove();
        });

    })
            .fail(function (obj) {
                alert(obj.responseText);
            });
}

/*
 * pro_approve
 */
function pro_approve(obj, tbl, tbl_id)
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
    var salt = 'dankdankdadddasnk';
    $.post('/includes/ajax/pro_handling_ajax.php?salt=' + salt, {'action': action, 'tbl': tbl, 'tbl_id': tbl_id}, function (data) {

        alert(data);

        //all is well
        obj.attr('data-state', 'ready');

        //restore button
        obj.removeClass('btn-danger btn-info btn-warning btn -succes')
                .addClass('btn-success');

        //fade after delete
        $('#' + tbl_id).fadeOut(700, function () {
            $('#' + tbl_id).remove();
        });

    })
            .fail(function (obj) {
                alert(obj.responseText);
            });
}

function pro_expand(obj, tbl_id)
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
    
    //tjek for om pågældende row er synlig, hvis der klikkes på knappen igen, så hides den
    if($(".stack-trace-row" +tbl_id).is(":visible"))
    {
       $(".closed").fadeOut("slow");
       return;
    }
    //Klik på stack-trace-tabel lukker 
    $(".stack-trace-row").click(function() {
       $(".closed").fadeOut("slow");
    });
    
    //1: sætter alle rows med class 'closed' til hide
    $(".closed").fadeOut("slow");
    
    //2: fremvis kun stack-trace-row + tbl_id
   $(".stack-trace-row" + tbl_id).fadeIn("slow",function () {
       $(".stack-trace-row" + tbl_id).show();
       setTimeout(obj.attr('data-state', 'ready'), 200);
     });
    //3 load ajax content ind
    $(".stack-trace-row" + tbl_id).load('/includes/ajax/stack_trace_handling_ajax.php?', {'error_id': tbl_id});
}

function pro_modal(obj, tbl_id)
{
    var action = "pro_modal";
    $("#postponeModal").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'tbl_id': tbl_id});

    //all is well
    obj.attr('data-state', 'ready');
}

function pro_postpone(obj, tbl_id, postdays)
{
    var action = "pro_postpone";
    $.post('/includes/ajax/pro_handling_ajax.php', {'action': action, 'tbl_id': tbl_id, 'postpone_days':postdays}, function(data){
        
        if(data = true)
        {
            //all is well
            obj.attr('data-state', 'ready');

            $('#postponeModal').modal('hide');

            alert("Error udskudt med "+postdays +" dag(e)");
            //fade after delete
            $('#' + tbl_id).fadeOut(700, function () {
                $('#' + tbl_id).remove();
            }); 
        }
        else
        {
            alert("Fejlmedelelse: SQL-kald kunne ikkeudføres.");
        }
    })
        .fail(function (obj) {
            alert(obj.responseText);
        });  
}

/*
 * pro_scriptlog
 */
function pro_scriptlog(obj)
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
    
    var action = obj.attr('data-action');
    
    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action});
    
     //all is well
     obj.attr('data-state', 'ready');
}

/*
 ** FUNKTIONER TIL SORTERING AF TABLE DATA
 */
function pro_sort_level(obj, page_changer = null)
{
    var state = obj.attr('data-action');
    var action = "pro_sort";
    var sort_by = "php_error_level";

    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'sort': sort_by, 'order': state, 'page_changer': page_changer});

    button_color_change(obj);

    asc_to_desc(obj);

}
function pro_sort_date(obj, page_changer = null)
{
    var state = obj.attr('data-action');
    var action = "pro_sort";
    var sort_by = "error_date";

    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'sort': sort_by, 'order': state, 'page_changer': page_changer});

    button_color_change(obj, page_changer = null);

    asc_to_desc(obj);
}

function pro_sort_location(obj, page_changer = null)
{
    var state = obj.attr('data-action');
    var action = "pro_sort";
    var sort_by = "error_location";

    $(".content-wrapper").load('/includes/ajax/pro_handling_ajax.php?', {'action': action, 'sort': sort_by, 'order': state, 'page_changer': page_changer});

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
    if (obj.attr('data-action') == "asc")
    {
        obj.attr('data-action', 'desc');
    } else
    {
        obj.attr('data-action', 'asc');
    }
}

function obj_states(obj)
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
}
/*
 * pro_run_script
 */
function run_script() {

    $(document).ajaxStart(function () {
        $("#refreshbtn span").addClass('gly-spin');
    });
    $.get("script/log_handler.php", function (data, status) {
        alert("Data: " + data + "\nStatus: " + status);
        $('#script-time').html(data);
    });
    $(document).ajaxComplete(function () {
        $("#refreshbtn span").removeClass('gly-spin');
    });
    $(document).ajaxError(function (e, xhr) {
        alert("Update failed - Error message:\n" + xhr.status + " " + xhr.statusText);
    });
}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});


