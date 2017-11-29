<?php

session_start();

$error_id = 10;//$_REQUEST['error_id'];
$postpone_days = 1;//$_REQUEST['postpone'];

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
}

if ($error_id == NULL || $postpone_days == NULL) {
    echo "Fejlmeddelelse: mangler variable";
    exit;
}
$postpone_date = date('Y-m-d', strtotime("+$postpone_days days"));
//SELECT stacktrace for given error
$data = new db_md();
$update_sql = "
    UPDATE php_error 
    SET postpone = '$postpone_date' 
    WHERE php_error.error_ID = $error_id;";

$success = $data->addData($update_sql);

if ($success) {
    echo "It works!"; 
}else{
    echo "It failed";
}




