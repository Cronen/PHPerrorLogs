<?php

//Indsætter db-info i variabler
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "test_db";

//insæt db-info i session
$_SESSION['connection_info'] = array(
    'host' => $dbServername,
    'user' => $dbUsername,
    'pw' => $dbPassword,
    'db' => $dbName,
);

require_once('constants.php');
?>