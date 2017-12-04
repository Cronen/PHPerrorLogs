<?php

session_start();

//set timezone
date_default_timezone_set("Europe/Copenhagen");

//required files
require_once('../../protected/configuration.php');
require_once(rp_self . 'lib/header_class.php');

//load bibliotek
Header_md::loadFiles();

//Validate user exists in session
if (!$_SESSION['logged_in'] == true) {
    echo "Der en fejl. Log ind igen";
}

/*
 * * pro_delete
 */
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "pro_delete") {

    $sqlDelete = "DELETE FROM php_error WHERE error_ID = '" . $_REQUEST['tbl_id'] . "'";
    $delete = new db_md();
    $delRes = $delete->addData($sqlDelete);

    if ($delRes == true) {
        echo "Postering er slettet";
    } else {
        echo "Handlingen fejlede. Prøv igen";
    }
}

/*
 * pro_sort 
 */
if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'pro_sort')) {

    //Instantiering af klasser
    $data = new db_md();
    $tablemkr = new table_md_class();

    // Hent alle php_errors og tilføj til array
    $sql = "SELECT * FROM php_error";
    $arrays = $data->makeArray($sql);

    //gruppér på error_id
    $erlvs = array();
    foreach ($arrays as $array) {
        $erlvs[$array['error_ID']][] = $array;
    }

    //Tjekker REQUEST['sort'] hvad der skal sorteres efter. 
    $sort_by = $_REQUEST['sort'];
    //Tjekker REQUEST['order'] om der sorteres efter asc eller desc
    $order_by = $_REQUEST['order'];
    //Tjekker REQUEST['load'] om limit
    //$load = $_REQUEST['pages'];
    //variable for dagsdato
    $today = date('Y-m-d');
    //indhent data ud fra sorteringsvalg
    $table_sql = "
        SELECT 
        '' as 'Handling', 
        php_error.error_ID AS ID, 
        php_error.error_date AS Dato, 
        error_levels.level AS Level, 
        php_error.error_msg AS Fejlmeddelelse, 
        php_error.error_location AS URL, 
        php_error.error_file AS Fil, 
        php_error.error_line AS Linje
        FROM php_error 
        INNER JOIN error_levels ON php_error.php_error_level = error_levels.level_ID
        WHERE postpone IS NULL OR  postpone  <='$today' 
        ORDER BY " . $sort_by . " " . $order_by . "
        LIMIT 10;";

    //Lav tabel med indhentet data
    $table_data = $data->makeArray($table_sql);

    $finished = array();
    foreach ($table_data as $array) {

        //Har navngivet error_id til ID i mit sqlkald, derfor bruger jeg her 'ID'
        $error_id = $array['ID'];
        $row = $array;

        //Array med handlinger/tools/triggers
        $tools = array();

        //"slet postering" trigger
        $tools[] = '<button data-state="ready" data-action="pro_delete" onclick="pro_delete($(this), \'php_error\',  ' . $error_id . ')"  data-placement="bottom" title="Slet postering" class="handling-btn btn-danger glyphicon glyphicon-trash toolsbtn"></button>';
        //"Udskyd postering" trigger
        $tools[] = '<button type="button" data-state="ready" onclick="pro_modal($(this), ' . $error_id . ')" data-target="#postponeModal" data-toggle="modal" data-placement="bottom" title="Udskyd error" class="handling-btn btn-warning glyphicon glyphicon-time toolsbtn"></button>';
        //"Se stack trace" trigger
        $tools[] = '<button data-state="ready" data-action="" onclick="pro_expand($(this), ' . $error_id . ')" data-placement="bottom" title="Se stack trace" class="handling-btn btn-info glyphicon glyphicon-info-sign toolsbtn"></button>';
        //"Godkend postering" trigger
        $tools[] = '<button data-state="ready" data-action=""onclick="pro_approve($(this), \'php_error\',  ' . $error_id . ')" data-placement="bottom" title="Godkend error" class="handling-btn btn-success glyphicon glyphicon-ok" toolsbtn></button>';

        $row['Handling'] = implode(' ', $tools);

        $finished[$error_id] = $row;
    }
        $html[] = "<div>Hello you</div>";
        $html[] = $tablemkr->makeTable($finished);

    //render
    echo implode('', $html);
}

if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'pro_modal')) {

    $postponeModal = '
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hvor mange dage vil du udskyde error med?</h4>
      </div>
      <div class="modal-body">
          <center>
          <button class="btn-primary" onclick="pro_postpone($(this), ' . $_REQUEST['tbl_id'] . ', 1)">1 dag</button>
          <button class="btn-primary" onclick="pro_postpone($(this), ' . $_REQUEST['tbl_id'] . ', 7)">7 dage</button>
          <button class="btn-primary" onclick="pro_postpone($(this), ' . $_REQUEST['tbl_id'] . ', 30)">30 dage</button>
          </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
      </div>
    </div>

  </div>';

    echo $postponeModal;
}

if ((isset($_REQUEST['action'])) && ($_REQUEST['action'] == 'pro_postpone')) {

    $tbl_id = $_REQUEST['tbl_id'];
    $postpone_days = $_REQUEST['postpone_days'];

    //Check if variables are empty
    if ($tbl_id == NULL || $postpone_days == NULL) {
        echo "Fejlmeddelelse: Manglende variabler";
        exit;
    }
    
    $date = date('Y-m-d');
    $postpone_date = date('Y-m-d', strtotime("+$postpone_days days"));
    $username = $_SESSION['user_name'];
    //UPDATE dato
    $data = new db_md();
    $update_sql = "
    UPDATE php_error 
    SET postpone = '$postpone_date',
        last_change = '$date',
        user = '$username'
    WHERE php_error.error_ID = '$tbl_id'";
    
    $success = $data->addData($update_sql);

    if ($success) 
        echo true;
    else 
        echo false;
   
}
?>
