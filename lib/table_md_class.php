<?php

class table_md_class {

    public $id, $use_index_as_id;

    function __construct($id = NULL) {
        $this->id = $id;
        $this->use_index_as_id = true; //uses index as postfix id on row
    }

    function makeTable($arrays) {

        if (is_null($arrays))
            return false;

        $rows = array();

        //make header row
        foreach ($arrays as $array) {
            $header = array_keys($array);
            break;
        }

        $rows[] = $this->makeTableHead($header);

        //make rows
        foreach ($arrays as $index => $array)
            $rows[] = $this->makeRow($array, $index);

        $html = '<table class="table table-responsive">' . implode('', $rows) . '</table>';

        return $html;
    }

    function makeRow($array, $index) {
        $cells = array();
        foreach ($array as $key => $value) {
            $cells[] = '<td >' . $value . '</td>';
        }
        $id = ($this->use_index_as_id) ? $this->id . '' . $index : NULL;
        $row = '<tr id="' . $id . '">' . implode('', $cells) . '</tr>';

        return $row;
    }

    function makeTableHead($array) {
        $cells = array();
        foreach ($array as $value) {
            $cells[] = '<th >' . $value . '</th>';
        }

        $row = '<tr>' . implode('', $cells) . '</tr>';

        return $row;
    }

}

?>