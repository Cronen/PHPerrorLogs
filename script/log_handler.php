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
    if(($line_in_file) >= 1) {
        //continue;
    }
    echo "Line " .$counter . " - " . $line_in_file . "\n";
}