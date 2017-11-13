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
    echo $line_in_file . "\n";
    $date_maybe = substr($line_in_file, 1, 20);
    $date = DateTime::createFromFormat('j-M-Y H:i:s', $date_maybe);
    echo $date_maybe . " -- becomes -- ". date_format($date, 'Y-m-d H:i:s') . "\n";
}