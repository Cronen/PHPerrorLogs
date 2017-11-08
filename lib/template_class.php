<?php

/*
 * * VERSION 2.28
 * * LAST CHANGED: February 22nd 2017
 * ***
 * * MAIN FUNCTIONS: 
 * * -> setFields($array)
 * * -> setFieldFromFile($field, $file)
 * * -> useTemplate()
 * * -> useWrapper()
 * * -> useRegularTemplate()
 * ***
 * * PRIVATE FUNCTIONS
 * * getTemplate()
 * * ob_get_file($url)
 * * getParentNames($id)
 * ***
 * * HELPER FUNCTIONS: 
 * * -> getFields()
 * * -> addToCss()
 * ***
 * * CHANGES:
 * ***
 * ***************************** INFO*********************************************************************************************************************************
 * *
 * * This class automatically looks in folder 'pages/' for its content. If file exists it will be appended to the content (if any) stored in the table 'pages'.
 * * Fields not starting with 'bg_': if a corresponding file exists in folder 'includes/' it will be appended to any existing content for that field.
 * * Fields starting with 'bg_': if a corresponding image file exists in folder 'billeder/bg/' it will be changed to a style background property.
 * *
 * *******************************************************************************************************************************************************************
 */

class Template {

    //paths - they all have default values assigned
    public $tpl_path, $bg_path, $pages_path, $includes_path, $folder_structure, $parents;
    //grab page_array & MYVARS for easy access
    private $page_array, $user_array, $pages, $MYVARS, $rp_self;
    //optional - add data array which will then be looped through (so far only in useRegularTemplate() )
    public $data, $allow_css;
    //properties
    public $tpl, $fields, $headline;
    public $js, $css, $footer, $head;

    /* constructor */

    public function __construct($tpl = "tpl_basic") {
        if (!defined("rp_self"))
            define("rp_self", rp);
        $this->tpl_path = rp_self . "templates/";
        $this->bg_path = rp . "billeder/bg/";
        $this->pages_path = rp_self . "pages/";
        $this->tbl_pages = "pages";
        $this->includes_path = rp_self . "includes/";
        $this->page_array = isset($GLOBALS["page_array"]) ? $GLOBALS["page_array"] : array();
        $this->tpl = (preg_match("/\.[a-z]{3}$/", $tpl) === 0) ? $tpl . ".php" : $tpl;
        $this->fields = NULL;
        $this->headline = true;
        $this->folder_structure = false;
        $this->parents = array();
        $this->js = "";
        $this->css = "";
        $this->footer = "";
        $this->head = "";
        $this->basepath = BASEPATH;
        $this->allow_css = false;
    }

    /* param is array - key = field name, value = value. */

    public function setFields($array) {
        if (!is_array($array)) {
            echo "setFields failed: wrong type (=" . gettype($array) . "). Only array allowed" . x;
            return;
        }

        $fields = $this->getFields();
        foreach ($array as $field => $value) {
            if (!in_array($field, $fields)) {
                echo "invalid field in setFields: $field" . x;
                return;
            }
            $this->$field = $value;
        }
    }

    /* sets a field value by grabbing file content using ob_get_file() to allow parsing. */

    public function setFieldFromFile($field, $file) {
        $errors = array();
        if (!is_string($field))
            $errors[] = "setFieldsFromFile failed: wrong type for fieldname (=" . gettype($field) . "). Only string allowed";
        if (!is_string($file))
            $errors[] = "setFieldsFromFile failed: wrong type for file (=" . gettype($file) . "). Only string allowed";
        if (!is_file($file))
            $errors[] = "setFieldsFromFile failed: file isn't a file";

        if (count($errors) > 0) {
            echo implode(x, $errors);
            return;
        }

        $fields = $this->getFields();
        if (!in_array($field, $fields)) {
            echo "invalid field in setFieldsFromFile: $field" . x;
            return;
        }

        $this->$field = $this->ob_get_file($file);
    }

    /* returns array with fields. If not generated before it will analyze the template to get the fields list */

    public function getFields() {
        $fields = $this->fields;
        if (is_null($fields)) {
            //get tpl
            $tpl = $this->getTemplate();
            if ($tpl[0] === false)
                return $tpl[1];
            else
                $tpl = $tpl[1];

            //analyze tpl
            preg_match_all("/\[%([\w\.]+)%\]/", $tpl, $match);
            $fields = $match[1];
            if (!is_array($fields))
                return "Error in analyzing template: should return array but returned '" . $fields . "'";

            $this->fields = $fields;
        }
        return $fields;
    }

    /* returns array: [0] = true|false, [1]= tpl|error msg */

    private function getTemplate() {
        $file = $this->tpl_path . $this->tpl;
        if (!is_file($file))
            return array(false, "Error getTemplate: file not found at " . $file);
        $tpl = file_get_contents($file);
        return array(true, trim($tpl));
    }

    public function useWrapper() {
        //get fields or generate if not
        $fields = $this->fields;
        if (is_null($fields))
            $fields = $this->getFields();
        if (!is_array($fields))
            return $fields; //returns error message generated by getFields()

            
//initialize fields if not already done
        foreach ($fields as $field)
            if (!isset($this->$field))
                $this->$field = "";

        //check for manually added data array
        $data = $this->data;
        if (!is_null($data)) {
            foreach ($fields as $field)
                if (isset($data[$field]))
                    $this->$field = $data[$field];
        }

        //fetch template
        $tpl = $this->getTemplate();
        if ($tpl[0] === false)
            return $tpl[1];
        else
            $tpl = $tpl[1];

        //replace fields
        $tpl = $this->replaceFields($fields, $tpl);

        return trim($tpl);
    }

    //used in all template functions
    private function replaceFields($fields, $tpl) {
        //replace fields
        foreach ($fields as $field) {
            //check for constant
            if (defined($field))
                eval('$this->$field = ' . $field . ';');

            $tpl = str_replace("[%" . $field . "%]", $this->$field, $tpl);
        }

        return $tpl;
    }

    public function useRegularTemplate() {
        //get fields or generate if not
        $fields = $this->fields;
        if (is_null($fields))
            $fields = $this->getFields();
        if (!is_array($fields))
            return $fields; //returns error message generated by getFields()

            
//check for manually added data array
        $data = $this->data;
        if (!is_null($data)) {
            foreach ($fields as $field)
                if (isset($data[$field]))
                    $this->$field = $data[$field];
        }

        //initialize fields if not already done
        foreach ($fields as $field)
            if (!isset($this->$field))
                $this->$field = "";

        //fetch template
        $tpl = $this->getTemplate();
        if ($tpl[0] === false)
            return $tpl[1];
        else
            $tpl = $tpl[1];

        //replace fields
        $tpl = $this->replaceFields($fields, $tpl);


        return trim($tpl);
    }

    //used to parse a file/url and store it in a variable using output buffering. Adds any js and css to the corresponding class properties
    private function ob_get_file($url) {
        //localize so available
        $page_array = $this->page_array;
        $user_array = $this->user_array;
        $MYVARS = $this->MYVARS;
        $js = $this->js;
        $css = $this->css;
        $footer = $this->footer;
        $head = $this->head;
        $content = "";

        ob_start();
        include($url);
        $content = ob_get_contents();
        ob_end_clean();

        $this->js = $js;
        $this->css = $css;
        $this->footer = $footer;
        $this->head = trimLines($head);
        return trim($content);
    }

    private function addToCss($css) {
        $this->css = addToCSS($this->css, $css);
    }

    private function getParentNames($id) {
        $pages = $this->pages;
        if (is_null($pages)) {
            $myAuto = new Autotekst($this->tbl_pages);
            $tmp = $myAuto->makeArray();
            if (!is_array($tmp)) {
                echo "ERROR! getParentNames() kan ikke finde nogen sider i tbl=" . $this->tbl_pages . x;
                exit();
            }
            $pages = array();
            foreach ($tmp as $array)
                $pages[$array["page_id"]] = $array;
            $this->pages = $pages;
        }
        $parent_id = $pages[$id]["parent_id"];
        if ($parent_id != '-1') {
            $this->parents[] = $pages[$parent_id]["pagename"];
            $this->getParentNames($parent_id);
        } else
            return;
    }

}

?>