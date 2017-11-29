<?php
class dashboardfunc {

function loadscriptdatetime(){
$data = new db_md();
$scriptinfo = 'select run_date from log ORDER BY log_entry_id DESC LIMIT 1';
  
$table_data = $data->makeArray($scriptinfo);

$scriptinfodatetime = '<span>
        <b>Scriptet er sidst kørt: </b>
        <span>'.$table_data[0]['run_date'].'
        </span>
        <button onclick="loadscriptdatetime()" class="btn-xs btn-info" id="refreshbtn"><span class="glyphicon glyphicon-refresh"></span></button>
        </span>';

echo $scriptinfodatetime;

}
}

