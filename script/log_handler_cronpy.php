<?php

//This should be handled as a script. This will be implementet later
echo "This is to test reading log file\n";
$file = fopen('php_error.log', 'r');

$counter = 0;
while (true) {
    $counter++;
    $line_in_file = fgets($file);
    if (!$line_in_file) {
        //reached end of file
        break;
    }

    $DATO = array();
    $ERROR = array();
    $MSG = array();
    $filepath = array();
    $errorline = array();

    preg_match('/(?<=\[).+?(?=\])/', $line_in_file, $DATO);
    preg_match('/(?<=PHP\s).*?(?=\:)/', $line_in_file, $ERROR);
    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
    preg_match('/ .:\\\\.*.php /', $line_in_file, $filepath);
    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);

    if (!empty($ERROR)) {
        $error_msg = $ERROR[0];
        echo $error_msg ."\n";
        if (is_numeric($error_msg[3])) {
            $trace_array = array();
            preg_match('/\[(.+?)\]\sPHP\s+(\d+?)\.\s(.+?\))\s(.+.*\\\\)(.+?):(\d+)/', $line_in_file, $trace_array);
            //bind trace til object
        } else {
            //opret phperror object
        }
    }
}