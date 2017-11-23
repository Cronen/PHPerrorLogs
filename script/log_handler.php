<?php

set_time_limit(900);
$time_pre = microtime(true);
include_once ($_SERVER['DOCUMENT_ROOT'] . '\lib\phperror_class.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '\lib\stack_trace_class.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . "/protected/configuration.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/db_class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/lib/function_lib_shared.php");

$file = fopen('php_error.log', 'r');
//Skip first line - its a line to create error log file
fgets($file);

$counter = 0;
$errorArray = array();
$lines_not_handled = array();
while (true) {
    $counter++;
    $line_in_file = fgets($file);
    if (!$line_in_file) {
        //reached end of file
        break;
    }
    $errrorstring = null;
    $DATO = array();
    $ERROR = array();
    $MSG = array();
    $filepath = array();
    $filename = array();
    $errorline = array();

    preg_match('/(?<=PHP\s).*?(?=\:)/', $line_in_file, $ERROR);
    if (!empty($ERROR)) {
        $errrorstring = $ERROR[0];
        if ($errrorstring == 'Stack trace') {
            continue;
        }
    } else {
        array_push($lines_not_handled, $line_in_file);
        continue;
    }

    preg_match('/(?<=\[).+?(?=\])/sU', $line_in_file, $DATO);
    preg_match('/(?<=\:  ).+?(?=\ in )/sU', $line_in_file, $MSG);
    preg_match('/\s.:\\\\.*\\\\/', $line_in_file, $filepath);
    preg_match('/[a-zA-Z0-9\_\-]*.php/s', $line_in_file, $filename);
    preg_match('/(?<= on line )[0-9]*/s', $line_in_file, $errorline);

    if ((is_numeric($errrorstring[2]) && $currentError != NULL)) {

        $stacktracesearch = array();

        preg_match('/\[(.+?)\]\sPHP\s+(\d+?)\.\s(.+?\))\s(.+.*\\\\)(.+?):(\d+)/', $line_in_file, $stacktracesearch);
        //bind trace til object
        $stacktracesearch[3] = reverse_backslash($stacktracesearch[3]);
        $stacktracesearch[4] = reverse_backslash($stacktracesearch[4]);
        $stackTrace = new stack_trace($stacktracesearch[2], $stacktracesearch[3], $stacktracesearch[4], $stacktracesearch[5], $stacktracesearch[6]);

        $currentError->add_stack_trace($stackTrace);
        continue;
    }

    if (empty($DATO) || empty($ERROR) || empty($MSG) || empty($filepath) || empty($filename) || empty($errorline)) {
        // Tjekker om en fejl, kan være spredt over 2 linjer, hvis ikke alle arrays er udfyldt

        $testline = NULL;
        $teststacktracesearch = array();
        $testline = $line_in_file . fgets($file);
        echo "TESTLINE: " . $testline . "</br>";

        preg_match('/(?<=\[).+?(?=\])/sU', $testline, $DATO);
        preg_match('/(?<=\:  ).+?(?=\ in )/sU', $testline, $MSG);
        preg_match('/\s.:\\\\.*\\\\/', $testline, $filepath);
        preg_match('/[a-zA-Z0-9\_\-]*.php/s', $testline, $filename);
        preg_match('/(?<= on line )[0-9]*/s', $testline, $errorline);

        // og tjekker derefter igen og udfylder de samme arrays.
        // Hvis arrays'ne stadig ikke er udfyldt, bliver det en erklæret en ukendt fejl.
        if (empty($DATO) || empty($ERROR) || empty($MSG) || empty($filepath) || empty($filename) || empty($errorline)) {

            array_push($lines_not_handled, $line_in_file, $testline);
            $currentError = NULL;
            continue;
        }
    }
    $filepath[0] = reverse_backslash($filepath[0]);
    $MSG[0] = reverse_backslash($MSG[0]);
    $currentError = new phperror($DATO[0], $ERROR[0], str_replace("'", "''", $MSG[0]), $filepath[0], $filename[0], $errorline[0]);

    array_push($errorArray, $currentError);
}
$db = new db_md();
$number_of_inserts = save_to_database($errorArray, $db);
$time_post = microtime(true);
$exec_time = $time_post - $time_pre;

write_log_to_db($db, $number_of_inserts, $exec_time, $counter, $lines_not_handled);

echo date('Y-m-d H:i:s') . " - Script done!\n";
exit;

function save_to_database($php_error_array, $db) {
    $inserts = 0;
    foreach ($php_error_array as $errorobject) {
        $inserts += $errorobject->add_to_db($db);
    }
    return $inserts;
}

function write_log_to_db($db, $insert_number, $exec_time, $counter, $unhandled_errors) {
    $sql_insert_string = "INSERT INTO log (`run_time`, `number_of_lines`, `number_of_inserts`) VALUES ('$exec_time', '$counter', '$insert_number')";
    $db->addData($sql_insert_string);

    if (!empty($unhandled_errors)) {
        foreach ($unhandled_errors as $line) {
            //echo "$line";
            //skal skrives til db
        }
    }
}

function reverse_backslash($instring) {
    return str_replace(chr(92), chr(47), $instring);
}
