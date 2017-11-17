<?php

session_start();

//set timezone
date_default_timezone_set("Europe/Copenhagen");

//required files
require_once('../../protected/configuration.php');
require_once(rp_self.'lib/header_class.php');

//load bibliotek
Header_md::loadFiles();

//Validate user exists in session
//if (!$_SESSION['logged_in'] == true) {
//    echo "Der en fejl. Log ind igen";
//}

/*
 * * pro_delete
 */

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "pro_delete") {
    
    $sqlDelete = "DELETE FROM php_error WHERE error_ID = '" . $_REQUEST['tbl_id'] . "'";
    $delete = new db_md();
    $delRes = $delete->addData($sqlDelete);

    if ($delRes == true)
    {
        echo "Postering er slettet";
    }
    else
    {
        echo "Handlingen fejlede. Prøv igen";
    }       
}
?>
