<?php

//This should be handled as a script. This will be implementet later
echo "This is to test reading log file\n";
$file = fopen('php_error.log', 'r');

$counter = 0;
$is_trace = false;
while (true) {
    $counter++;
    $line_in_file = fgets($file);
    if (!$line_in_file) {
        //reached end of file
        break;
    }
    //echo $line_in_file . "</br>";
//    $date_maybe = substr($line_in_file, 1, 20);
//    $date = DateTime::createFromFormat('j-M-Y H:i:s', $date_maybe);
//    echo $date_maybe . " -- becomes -- ". date_format($date, 'Y-m-d H:i:s') . "</br>";
    // ANOTHER WAY OG FINDING IT USING REGEX
    //$matches = array();
    $DATO = array();
    $ERROR = array();
    $MSG = array();
    $filepath = array();
    $errorline = array();

    preg_match('/(?<=\[).+?(?=\])/', $line_in_file, $DATO);
    preg_match('/(?<=PHP).*?(?=\:)/', $line_in_file, $ERROR);
    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
    preg_match('/ .:\\\\.*.php /', $line_in_file, $filepath);
    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);

//    preg_match('/PHP(.*?):/', $line_in_file, $ERROR);
//    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
//    preg_match('/(.:\\).+?(.php))/', $line_in_file, $filepath);
//    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);

    $error_msg = '     ';
    foreach ($DATO as $value) {
        //echo "Dato: " . $value . "\n";
    }
    foreach ($ERROR as $value) {
        //echo "Error: " . $value . "\n";
        $error_msg = $value;
        //echo $error_msg . " and " .$error_msg[3] . "\n";
    }
    foreach ($MSG as $value) {
        //echo "Message: " . $value . "\n";
    }
    foreach ($filepath as $value) {
        //echo "FilePath: " . $value . "\n";
    }
    foreach ($errorline as $value) {
        //echo "Errorline: " . $value . "\n";
    }
    if (is_numeric($error_msg[3])) {
        //echo $error_msg . " and " . $error_msg[1] . "\n";
        $is_trace = TRUE;
    } else {
        $is_trace = false;
    }
    if ($is_trace) {
        $trace_array = array();
        preg_match('/(\[)(.+?)(\])(\sPHP\s.\s)(\d+?)(\.)(\s.+?\))(\s.+:\\\\)(.+.*\\\\)(.+?)(:)(\d+)/', $line_in_file, $trace_array);
        print_r($trace_array);
        echo "_______ \n";
    }


}