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
    
    //Tjekker fra REQUEST['sort'] hvad der skal sorteres efter. 
    $sort_by = $_REQUEST['sort'];
    $order_by = $_REQUEST['order'];
    
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
        ORDER BY ".$sort_by." ".$order_by."
        LIMIT 5;";

    echo $table_sql;

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
        $tools[] = '<button data-state="ready" data-action="pro_delete" onclick="pro_delete($(this), \'php_error\',  ' . $error_id . ')" class="btn-danger">Slet</button>';
        //"Udskyd postering" trigger
        $tools[] = '<button data-state="ready" data-action="" onclick="pro_postpone($(this), \'php_error\',  ' . $error_id . ')" class="btn-warning">Udskyd</button>';
        //"Godkend postering" trigger
        $tools[] = '<button data-state="ready" data-action=""onclick="pro_approve($(this), \'php_error\',  ' . $error_id . ')" class="btn-success">Godkend</button>';

        $row['Handling'] = implode(' ', $tools);

        $finished[$error_id] = $row;
    }

    $html[] = $tablemkr->makeTable($finished);

    //render
    echo implode('', $html);
}
?>
