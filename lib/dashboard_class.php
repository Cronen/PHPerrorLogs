<?php

class dashboard_func {

function pro_current_runtime()
{
    $data = new db_md();
    $sql = "SELECT * FROM log ORDER BY log_entry_id DESC LIMIT 1 "; 
    $result = $data->makeArray($sql);
   
    echo ($result[0]["run_date"]);
    
}

}

