<?php ?>

<div class="container-fluid">
    <div class="col-md-12">
        <span>Vælg at sortere efter:</span>
        <button>Level</button>
        <button>Dato</button>
        <button>Site</button>
        <button>Vis alt</button>
    </div>
</div>

<div class="container-fluid overview-table">
    <div class="col-md-12">
        <?php
        //Instantiering af klasser
        $data = new db_md();
        $tablemkr = new table_md_class();

        // Hent alle php_errors og tilføj til array
        $sql = "SELECT * FROM php_error";
        $arrays = $data->makeArray($sql);

        //gruppér på error_id
        $erlvs = array();
        foreach($arrays as $array) 
        {
            $erlvs[ $array['error_ID'] ][] = $array;
        }

        //indhent data ud fra sorteringsvalg
        $table_sql = "
                SELECT 
                '' AS 'Handling',
                error_ID AS ID,
                error_date AS Dato,
                php_error_level AS level,
                error_msg AS Fejlmeddelse,
                error_location AS URL,
                error_file AS Fil,
                error_line AS linje
                FROM php_error";

        //Lav tabel med indhentet data
        $table_data = $data->makeArray($table_sql);  
        
        $finished = array();
        foreach($table_data as $array) 
        {
            //Har navngivet error_id til ID, derfor bruger jeg her 'ID'
            $error_id = $array['ID'];
            $row = $array;
            
            //Array med handlinger/tools/triggers
            $tools = array();
            
            //"slet postering" trigger
            $tools[] = '<button data-state="ready" data-action="pro_delete" onclick="pro_delete($(this), \'php_error\',  '.$error_id.')" class="btn-danger">Slet</button>';
            //"Udskyd postering" trigger
            $tools[] = '<button data-state="ready" data-action="" onclick="pro_postpone($(this), \'php_error\',  '.$error_id.')" class="btn-warning">Udskyd</button>';
            //"Godkend postering" trigger
            $tools[] = '<button data-state="ready" data-action=""onclick="pro_approve($(this), \'php_error\',  '.$error_id.')" class="btn-success">Godkend</button>';
            
            $row['Handling'] = implode(' ', $tools);
            
            $finished[ $error_id ] = $row;
        }
		
	$html[] = $tablemkr->makeTable($finished);
		
	//render
	echo implode('', $html);
        
        ?>
    </div>
</div>
