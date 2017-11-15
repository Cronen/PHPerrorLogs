<?php

class phperror {

    var $error_date;
    var $error_level;
    var $error_msg;
    var $error_location;
    var $error_file;
    var $error_line;
    var $stack_trace_array = array();

    function _construct($err_date, $err_lvl, $err_msg, $err_loca, $err_file, $err_line) {
        $this->error_date = $err_date;
        $this->error_level = $err_lvl;
        $this->error_msg = $err_msg;
        $this->error_location = $err_loca;
        $this->error_file = $err_file;
        $this->error_line = $err_line;
    }

    function add_strack_trace($stack_trace){
        array_push($stack_trace_array, $stack_trace);
    }
            
    function add_to_DB() {
        //this should call a DB function in DB class that will handle the DB inserts
    }

}
