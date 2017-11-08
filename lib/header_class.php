<?php
/*
 * Version 0.1
 */
class Header_md {
	
    //get database values
    public $page_array;
    private $data;
	
	
    function __construct()
    {
	$this->data = array();
    }
	
    /*
    ** returns js tags (if any)
    */
    static function loadFiles()
    {
        //required files	
	//load bibliotek
	$paths = array(rp_self."lib/");
	$to_header = array();
	foreach($paths as $path)
	{
            $scan = scandir($path);
            foreach($scan as $file)
            {
		if(is_file($path.$file))
            {
            	$ext = explode(".", $file);
		$ext = $ext[1];
		if($ext == "php")
                require_once($path.$file);
					
		//tilføj script tag til header hvis det er javascript
		if($ext == 'js')
                    $to_header[] = '<script type="text/javascript" src="'.$path.$file.'"></script>';						
            }
            }
	}
		
        return implode('', $to_header);
    }
	
	/* returns string on error, true on succes */
    public function initialize()
    {
	require_once('protected/configuration.php');

	//load biblioteksfiler
	$this->data['header'] = Header_md::loadFiles();
		
	//hent alle sider for den aktuelle mappe
	$myDb = new db_md();
	$myDb->sql = "SELECT * FROM pages WHERE folder='".FOLDER."' ORDER BY ranking ASC";
	$pages = $myDb->makeArray();

	//determine page_id
	if(isset($_REQUEST['p']) && is_numeric($_REQUEST['p']))
	{
            $page_id = $_REQUEST['p'];
	    $test = (int)$page_id;
            if($test != $page_id)
		return makeError('Fejl i header.php: GET value p er ugyldig');
	}
	else
	{
            //bestem page med ranking = 0
            $page_id = false;
            foreach($pages as $page)
		if($page['ranking'] == '0')
                    $page_id = $page['page_id'];
	}

	//check for missing page_id
	if($page_id === false)
            return makeError('Fejl i header.php: page_id kan ikke bestemmes');

	//determine page
	$page_array = false;
	foreach($pages as $page)
            if($page['page_id'] == $page_id)
		$page_array = $page;
		
	//check for missing page
	if($page_array === false)
            return makeError('Fejl i header.php: page kan ikke bestemmes');
		
		
	//store to session
	$this->page_array = $page_array;
	$_SESSION['page_array'] = $page_array;
    }
        
    public function getPage()
    {
	//prepare data array
	$DATA = $this->data;
	$DATA = array_merge($DATA, $this->page_array);
		
	ob_start();
	require_once('includes/menu.php');
	$DATA['menu'] = ob_get_contents();
	ob_end_clean();
	require_once('pages/'.$this->page_array['filename']);
	$DATA['content'] = ob_get_contents();
	ob_end_clean();
	$myTpl = new Template($this->page_array['template']);
	$myTpl->data = $DATA;
		
	$html = $myTpl->useRegularTemplate();
		
	//render
	echo $html;
    }
}
?>