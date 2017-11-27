<?php

session_start();
$error_id = 16; //$_REQUEST['error_id'];
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

if ($error_id != NULL) {
    $data = new db_md();
    $sql = "SELECT trace_number, trace_msg, trace_location, trace_file, trace_line FROM stack_trace WHERE error_ref_ID = $error_id";
    $stack_trace_array = $data->makeArray($sql);

    print_r($stack_trace_array);
} else {
   //Der er sket en fejl, do something about it. 
}

