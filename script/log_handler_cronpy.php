<?php

$time_pre = microtime(true);
include( $_SERVER['DOCUMENT_ROOT'] . "/lib/phperror_class.php");
include( $_SERVER['DOCUMENT_ROOT'] . "/lib/stack_trace_class.php");
//This should be handled as a script. This will be implementet later
$file = fopen('php_error.log', 'r');

$counter = 0;
$errorArray = array();
$php_error = NULL;
while (true) {
    $counter++;
    $line_in_file = fgets($file);
    if (!$line_in_file) {
        //reached end of file
        break;
    }
    $errrorstring = NULL;

    $DATO = array();
    $ERROR = array();
    $MSG = array();
    $filepath = array();
    $filename = array();
    $errorline = array();

    preg_match('/(?<=PHP\s).*?(?=\:)/', $line_in_file, $ERROR);
    //tjekker her, for at spare ressourcer på regex
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
    if (is_numeric($ERROR[0][2]) && $php_error != NULL) {
        //formodet at være en stack trace, som tilhøre den nuværende $php_error objekt i memory
        $stack_trace_array = array();
        preg_match('/\[(.+?)\]\sPHP\s+(\d+?)\.\s(.+?\))\s(.+.*\\\\)(.+?):(\d+)/', $line_in_file, $stack_trace_array);
        $tmp_stack_trace = new stack_trace($stack_trace_array[2], $stack_trace_array[3], $stack_trace_array[4], $stack_trace_array[5], $stack_trace_array[6]);
        $php_error->add_stack_trace($tmp_stack_trace);
        continue;
    }

    if (empty($DATO) || empty($ERROR) || empty($MSG) || empty($filepath) || empty($filename) || empty($errorline)) {
        //Kontrol af tomme arrays fra reqex. Disse skal der ses nærmere på, derfor printet. 
        //Disse skal skrives til log filen
        //echo "Fejl på linje ".$counter*2 ."\n";
        //print_r($line_in_file);
        $php_error = NULL;
        continue;
    }
    //formodet at være en error
    $date_to_convert = substr($DATO[0], 0, 20);
    $date_object = DateTime::createFromFormat('j-M-Y H:i:s', $date_to_convert);
    $php_error = new phperror($date_object->format('Y-m-d H:i:s'), $ERROR[0], $MSG[0], $filepath[0], $filename[0], $errorline[0]);
    array_push($errorArray, $php_error);
}
$time_post = microtime(true);
$exec_time = $time_post - $time_pre;

echo "executiontime $exec_time ms for $counter lines\n\n";

add_to_DB($errorArray);

exit;

function add_to_DB($php_error_array) {
    include( $_SERVER['DOCUMENT_ROOT'] . "/protected/configuration.php");
    include( $_SERVER['DOCUMENT_ROOT'] . "/lib/db_class.php");
    include( $_SERVER['DOCUMENT_ROOT'] . "/lib/function_lib_shared.php");
    $db = new db_md();
    foreach ($php_error_array as $errorobject) {
        $errorobject->add_to_db($db);
        break;
    }
}

?>