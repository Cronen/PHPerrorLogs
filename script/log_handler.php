<?php

include_once"phperror_class.php";
include_once"stack_trace_class.php";
//This should be handled as a script. This will be implementet later
echo "This is to test reading log file</br>";
$file = fopen('php_error.log', 'r');

$counter = 0;
$errorArray = array();
array_push($errorArray, new phperror());

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
     $filename = array();
     $errorline = array();
    
    preg_match('/(?<=\[).+?(?=\])/', $line_in_file, $DATO);
    preg_match('/(?<=PHP\s).*?(?=\:)/', $line_in_file, $ERROR);
    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
    preg_match('/\s.:\\\\.*\\\\/', $line_in_file, $filepath);
    //preg_match('/\\\\.*.php/', $line_in_file, $filename);
    preg_match('/[a-zA-Z0-9\_\-]*.php /',$line_in_file,$filename);
    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);
    
//    preg_match('/PHP(.*?):/', $line_in_file, $ERROR);
//    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
//    preg_match('/(.:\\).+?(.php))/', $line_in_file, $filepath);
//    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);
    
    
    if(!empty($ERROR))
    {
    $errrorstring = $ERROR[0];
    
    if ($errrorstring == 'Stack trace')
    {
      current($errorArray)->stack_trace_array = array();
      
    }
    echo "ISNUMERIC index 2: ".$errrorstring[2]." BOOLEAN: ".is_numeric($errrorstring[2])."</br>";
    if(is_numeric($errrorstring[2])) 
    {
        
       
        $stacktracesearch = array();
        $stackTrace = new stack_trace();
        preg_match('/(\[)(.+?)(\])(\sPHP\s.\s)(\d+?)(\.)(\s.+?\))(\s)(.+.*\\\\)(.+?)(:)(\d+)/', $line_in_file, $stacktracesearch);
        //bind trace til object
        $stackTrace->trace_number = $stacktracesearch[5];
        $stackTrace->trace_msg = $stacktracesearch[7];
        $stackTrace->trace_location =$stacktracesearch[9] ;
        $stackTrace->trace_file =$stacktracesearch[10] ;
        $stackTrace->trace_line = $stacktracesearch[12];
        
        //array_push(current($errorArray)->stack_trace_array,$stackTrace);
        current($errorArray)->add_stack_trace($stackTrace);
    }
    else
    {
        if (empty($MSG)) {
            array_push($MSG,"");
        }
        if (empty($filepath)) {
            array_push($filepath,"");
        }
        if (empty($filename)) {
            array_push($filename,"");
        }
        if (empty($errorline)) {
            array_push($errorline,"");
        }
        
        current($errorArray)->error_date =$DATO[0];
        current($errorArray)->error_level =$ERROR[0];
        current($errorArray)->error_msg =$MSG[0];
        current($errorArray)->error_location =$filepath[0];
        current($errorArray)->error_file =$filename[0];
        current($errorArray)->error_line =$errorline[0];
    
        array_push($errorArray, new phperror());
    }
  }
        
//        foreach ($DATO as $value)
//        {
//        echo "Dato: ".$value. "</br>";
//        
//        }
//        foreach ($ERROR as $value)
//        {
//        echo "Error: ".$value. "</br>";
//        
//        }
//        foreach ($MSG as $value)
//        {
//        echo "Message: ".$value. "</br>";
//        
//        }
//        foreach ($filepath as $value)
//        {
//        echo "FilePath: ".$value. "</br>";
//        
//        }
//        foreach ($filename as $value)
//        {
//        echo "Filename: ".$value. "</br>";
//        
//        }
//        foreach ($errorline as $value)
//        {
//        echo "Errorline: ".$value. "</br>";
//        
//        }
    
}
        foreach ($errorArray as $errorobject)
        {
        echo 'error_dateVALUE: '.$errorobject->error_date."</br>";
        echo 'error_levelVALUE: '.$errorobject->error_level."</br>";
        echo 'error_msgVALUE: '.$errorobject->error_msg."</br>";
        echo 'error_locationVALUE: '.$errorobject->error_location."</br>";
        echo 'error_fileVALUE: '.$errorobject->error_file."</br>";
        echo 'error_lineVALUE: '.$errorobject->error_line."</br>";
                
        echo 'isEMpty_stack_trace_arrayVALUE: '.count($errorobject->stack_trace_array)."</br>";
                
        if (!empty($errorobject->stack_trace_array))
        {
          foreach ($errorobject->stack_trace_array as $stackvalue)
          {
           echo 'StackNummerVALUE: '.$stackvalue->trace_number."</br>";
           echo 'StackMSGVALUE: '.$stackvalue->trace_msg."</br>"; 
           echo 'StackPATHVALUE: '.$stackvalue->trace_location."</br>"; 
           echo 'StackFILEVALUE: '.$stackvalue->trace_file."</br>"; 
           echo 'StackLINEVALUE: '.$stackvalue->trace_line."</br>"; 
           }
        }
               
        }
?>