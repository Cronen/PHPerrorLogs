<?php

class stack_trace {

    var $trace_number;
    var $trace_msg;
    var $trace_location;
    var $trace_file;
    var $trace_line;
    
    function _construct($trc_number, $trc_msg, $trc_loca, $trc_file, $trc_line) {
        $this->trace_number = $trc_number;
        $this->trace_msg = $trc_msg;
        $this->trace_location = $trc_loca;
        $this->trace_file = $trc_file;
        $this->trace_line = $trc_line;
    }

}