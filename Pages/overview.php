<?php
$data = new db_md();
$tablemkr = new table_md_class();
?>

<div class="container overview-table">
    <div class="col-md-12">
        <?php
        $sql = "
        SELECT 
            fornavn,
            efternavn, 
            alder, 
            adresse,
            '' AS 'Handling'
	FROM dummydata";
        
        $arrays = $data->makeArray($sql);
        foreach ($arrays as $array) {
            $row = $array;
            
            //Array med handlinger/tools/triggers
            $tools = array();

            //"slet postering" trigger
            $tools[] = '<button class="btn-danger">Slet postering</button>';
            
            //"Udskyd postrings" trigger
            $tools[] = '<button class="btn-warning">udskyd</button>';
            
            //"Gennemset" trigger
            $tools[] = '<button class="trigger-btn btn-success"> Godkend</button>';

            $row['Handling'] = implode(' ', $tools);

            $finished[] = $row;
        }
        $html[] = $tablemkr->makeTable($finished);

        //render
        echo implode('', $html);
        ?>
    </div>
</div>
