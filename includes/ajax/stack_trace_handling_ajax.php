<?php

session_start();

$error_id = $_REQUEST['error_id'];

//set timezone
date_default_timezone_set("Europe/Copenhagen");

//required files
require_once('../../protected/configuration.php');
require_once(rp_self . 'lib/header_class.php');

//load bibliotek
Header_md::loadFiles();

//Validate user exists in session
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] == true) {
    echo "Der en fejl. Log ind igen\n";
    exit;
}

if ($error_id == NULL) {
    echo "Fejlmeddelelse: error_id kan ikke findes";
    exit;
}

//SELECT stacktrace for given error
$data = new db_md();
 $tablemkr = new table_md_class();
$table_sql = "
            SELECT 
            trace_number, 
            trace_msg, 
            trace_location, 
            trace_file, 
            trace_line 
            FROM stack_trace 
            WHERE error_ref_ID = $error_id";

$table_data = $data->makeArray($table_sql);

if(empty($table_data)) 
{
    echo makeHighlight("Ingen stack trace fundet");
    exit();
}

foreach ($table_data as $array) {
    
    
    $finished[]= $array;

}

$html[] = $tablemkr->makeTable($finished);

//render
$test  =  implode('', $html);

echo $test;




