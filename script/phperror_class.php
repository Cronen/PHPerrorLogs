<?php

class phperror {

    var $error_date;
    var $error_level;
    var $error_msg;
    var $error_location;
    var $error_file;
    var $error_line;
    var $stack_trace = array();
    
    function _construct(){
        //Dette er en constructor
    }

    function add_to_DB(){
        //this should call a DB function in DB class that will handle the DB inserts
    }
}

class stack_trace {

    var $trace_number;
    var $trace_msg;
    var $trace_location;
    var $trace_file;
    var $trace_line;
    
    function _construct(){
        //Dette er en constructor
    }

}