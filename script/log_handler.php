<?php

include_once"C:\wamp64\www\PHPerrorLogs\lib\phperror_class.php";
include_once"C:\wamp64\www\PHPerrorLogs\lib\stack_trace_class.php";
//This should be handled as a script. This will be implementet later
echo "This is to test reading log file</br>";
$file = fopen('php_error.log', 'r');

$counter = 0;
$errorArray = array();
$currentError = new phperror();

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
    if (!empty($ERROR))
        {
        $errrorstring = $ERROR[0];
        if ($errrorstring == 'Stack trace')
            {
            continue;
            }
        }
    else
    {
        continue;
    }
     
    preg_match('/(?<=\[).+?(?=\])/', $line_in_file, $DATO);
    preg_match('/(?<=\:  ).+?(?=\ in )/', $line_in_file, $MSG);
    preg_match('/\s.:\\\\.*\\\\/', $line_in_file, $filepath);
    preg_match('/[a-zA-Z0-9\_\-]*.php /',$line_in_file,$filename);
    preg_match('/(?<= on line )[0-9]*/', $line_in_file, $errorline);
    
    //echo "ISNUMERIC index 2: ".$errrorstring[2]." BOOLEAN: ".is_numeric($errrorstring[2])."</br>CurrentERROR:</br> ".$currentError. "</br>";
    if((is_numeric($errrorstring[2]) && (!empty($currentError))))
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
        $currentError->add_stack_trace($stackTrace);
    }
//        echo 'currentError_dateVALUE: '.$currentError->error_date."</br>";
//        echo 'currentError_levelVALUE: '.$currentError->error_level."</br>";
//        echo 'currentError_msgVALUE: '.$currentError->error_msg."</br>";
//        echo 'currentError_locationVALUE: '.$currentError->error_location."</br>";
//        echo 'currentError_fileVALUE: '.$currentError->error_file."</br>";
//        echo 'currentError_lineVALUE: '.$currentError->error_line."</br>";
//        
//        foreach ($currentError->stack_trace_array as $stackvalue)
//          {
//           echo 'currentErrorStackNummerVALUE: '.$stackvalue->trace_number."</br>";
//           echo 'currentErrorStackMSGVALUE: '.$stackvalue->trace_msg."</br>"; 
//           echo 'currentErrorStackPATHVALUE: '.$stackvalue->trace_location."</br>"; 
//           echo 'currentErrorStackFILEVALUE: '.$stackvalue->trace_file."</br>"; 
//           echo 'currentErrorStackLINEVALUE: '.$stackvalue->trace_line."</br>"; 
//           }
//    
    
        if (empty($MSG)) {
            //array_push($MSG,"");
            continue;
        }
        if (empty($filepath)) {
           // array_push($filepath,"");
            continue;
        }
        if (empty($filename)) {
            //array_push($filename,"");
            continue;
        }
        if (empty($errorline)) {
           // array_push($errorline,"");
            continue;
        }
//        if (empty($DATO) || empty($ERROR) || empty($MSG) || empty($filepath) || empty($filename) || empty($errorline))
//        {
//        //Kontrol af tomme arrays fra reqex. Disse skal der ses nærmere på, derfor printet. 
//        //echo "Fejl på \n";
//        //print_r($line_in_file);
//        continue;
//        }
        $currentError =  new phperror();
        
        $currentError->error_date = date("Y-m-d H:i:s", strtotime($DATO[0]));
        //$currentError->error_date =$DATO[0];
        $currentError->error_level =$ERROR[0];
        $currentError->error_msg =$MSG[0];
        $currentError->error_location =$filepath[0];
        $currentError->error_file =$filename[0];
        $currentError->error_line =$errorline[0];
    
        array_push($errorArray, $currentError);
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
        $errorobject->add_to_DB();      
        }
?>