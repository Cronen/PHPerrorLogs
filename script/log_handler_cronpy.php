<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '\lib\phperror_class.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . '\lib\stack_trace_class.php');

//This should be handled as a script. This will be implementet later
$file = fopen('php_error.log', 'r');

$counter = 0;
$errorArray = array();
$currentError = null; //new phperror();

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
        continue;
    }

    preg_match('/(?<=\[).+?(?=\])/', $line_in_file, $DATO);
    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
    preg_match('/\s.:\\\\.*\\\\/', $line_in_file, $filepath);
    preg_match('/[a-zA-Z0-9\_\-]*.php /', $line_in_file, $filename);
    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);

    if ((is_numeric($errrorstring[2]) && (!empty($currentError)))) {

        $stacktracesearch = array();

        preg_match('/\[(.+?)\]\sPHP\s+(\d+?)\.\s(.+?\))\s(.+.*\\\\)(.+?):(\d+)/', $line_in_file, $stacktracesearch);
        //bind trace til object
        $stacktracesearch[3] = reverse_backslash($stacktracesearch[3]);
        $stacktracesearch[4] = reverse_backslash($stacktracesearch[4]);
        $stackTrace = new stack_trace($stacktracesearch[2], $stacktracesearch[3], $stacktracesearch[4], $stacktracesearch[5], $stacktracesearch[6]);

        $currentError->add_stack_trace($stackTrace);
    }

    if (empty($DATO) || empty($ERROR) || empty($MSG) || empty($filepath) || empty($filename) || empty($errorline)) {
        //Kontrol af tomme arrays fra reqex. Disse skal der ses nærmere på, derfor printet. 
        //echo "Fejl på \n";
        //print_r($line_in_file);
        continue;
    }
    $filepath[0] = reverse_backslash($filepath[0]);
    $MSG[0] = reverse_backslash($MSG[0]);
    $currentError = new phperror($DATO[0], $ERROR[0], str_replace("'","''",$MSG[0]), $filepath[0], $filename[0], $errorline[0]);

    array_push($errorArray, $currentError);
}

add_to_DB($errorArray);

exit;

function add_to_DB($php_error_array) {
    include( $_SERVER['DOCUMENT_ROOT'] . "/protected/configuration.php");
    include( $_SERVER['DOCUMENT_ROOT'] . "/lib/db_class.php");
    include( $_SERVER['DOCUMENT_ROOT'] . "/lib/function_lib_shared.php");
    $db = new db_md();
    $cunt = 0;
    foreach ($php_error_array as $errorobject) {
        $cunt++;
        $errorobject->add_to_db($db);
        if ($cunt >= 10) {
            break;
        }
    }
}

function reverse_backslash($instring) {
    return str_replace(chr(92), chr(47), $instring);
}

?>