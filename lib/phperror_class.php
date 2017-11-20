<?php

class phperror {

    var $error_date;
    var $error_level;
    var $error_msg;
    var $error_location;
    var $error_file;
    var $error_line;
    var $stack_trace_array = array();

    function __construct($err_date, $err_lvl, $err_msg, $err_loca, $err_file, $err_line) {
        $this->error_date = date("Y-m-d H:i:s", strtotime($err_date));
        $this->error_level = $err_lvl;
        $this->error_msg = $err_msg;
        $this->error_location = $err_loca;
        $this->error_file = $err_file;
        $this->error_line = $err_line;
    }

    function add_stack_trace($stack_trace) {
        array_push($this->stack_trace_array, $stack_trace);
    }

    function add_to_DB($db) {
        $number__of_inserts = 0;
        //finder error level i tal
        $level = "SELECT level_ID FROM error_levels WHERE level = '$this->error_level'";
        $extend_date = "";
        $sql_select_string = "SELECT error_ID FROM php_error "
                . "WHERE $extend_date php_error_level = ($level) AND error_msg = '$this->error_msg' AND "
                . "error_location = '$this->error_location ' AND error_file = '$this->error_file' AND error_line = '$this->error_line';";
        $error_id = $db->makeArray($sql_select_string);
        //echo $sql_select_string;
        if (empty($error_id[0])) {
            $sql_insert_string = "INSERT INTO `php_error` (`error_date`, `php_error_level`, `error_msg`, `error_location`, `error_file`, `error_line`) "
                    . "VALUES ('$this->error_date', ($level), '$this->error_msg', '$this->error_location ','$this->error_file', '$this->error_line')";
            $success = $db->addData($sql_insert_string);
            $extend_date = "error_date = '$this->error_date' AND";
            $error_id = $db->makeArray($sql_select_string);
            //adds stack trace
            if ($success == true) {
                $number__of_inserts++;
                //foreach trace inserts into DB
                foreach ($this->stack_trace_array as $stack_trace) {
                    $number__of_inserts++;
                    $stack_trace->add_to_db($db, $error_id[0]['error_ID']);
                }
            }
        }
        return $number__of_inserts;
    }

    function __toString() {

        $returnString = "Date: " . $this->error_date . "</br>Level: " . $this->error_level . "</br>Message: " . $this->error_msg . "</br>Path: " . $this->error_location . "</br>File: " . $this->error_file . "</br>Line: " . $this->error_line . "</br>";
        if (!empty($this->stack_trace_array)) {
            foreach ($this->stack_trace_array as $stackvalue) {
                $returnString .= 'StackNummer: ' . $stackvalue->trace_number . "</br>";
                $returnString .= 'StackMessage: ' . $stackvalue->trace_msg . "</br>";
                $returnString .= 'StackPath: ' . $stackvalue->trace_location . "</br>";
                $returnString .= 'StackFile: ' . $stackvalue->trace_file . "</br>";
                $returnString .= 'StackLine: ' . $stackvalue->trace_line . "</br>";
            }
        }
        return $returnString;
    }

}
