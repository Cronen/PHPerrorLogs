<?php

//Indsætter db-info i variabler
$dbServername = "mysql45.unoeuro.com:3306";
$dbUsername = "codivision_dk";
$dbPassword = "killkenny100";
$dbName = "codivision_dk_db2";

//insæt db-info i session
$_SESSION['connection_info'] = array(
    'host' => $dbServername,
    'user' => $dbUsername,
    'pw' => $dbPassword,
    'db' => $dbName,
);

require_once('constants.php');
?>