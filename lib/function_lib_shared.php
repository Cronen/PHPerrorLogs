<?php

function print_r_pre($array, $return = false)
{
	$str = '<pre>'.print_r($array, true).'</pre>';
	
	if($return == false)
	{
				echo $str;
	}
	else
	{
		return $str;
	}
		
}

function makeError($txt)
{
	return '<div style="background-color: red; color:white; padding: 10px; border-color: black; margin:2px,0px;">'.$txt.'</div>';
}

function makeWarning($txt)
{
	return '<div style="background-color: yellow; color:white; padding: 10px; border-color: black; margin:2px,0px;">'.$txt.'</div>';
}

function makeHighlight($txt)
{
	return '<div style="background-color: blue; color:white; padding: 10px; border-color: black; margin:2px,0px;">'.$txt.'</div>';
}

?>