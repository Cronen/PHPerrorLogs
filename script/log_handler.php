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
    
    
    foreach ($DATO as $value)
        {
        echo "Dato: ".$value. "</br>";
        
        }
    foreach ($ERROR as $value)
        {
        echo "Error: ".$value. "</br>";
        
        }
        foreach ($MSG as $value)
        {
        echo "Message: ".$value. "</br>";
        
        }
        foreach ($filepath as $value)
        {
        echo "FilePath: ".$value. "</br>";
        
        }
        foreach ($errorline as $value)
        {
        echo "Errorline: ".$value. "</br>";
        
        }
    
}