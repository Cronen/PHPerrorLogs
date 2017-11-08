<?php

/*
 * * define location and relative path - 
 * * use HTTP_REFERER instead of the normal PHP_SELF as this file is used from AJAX scripts. Remove 'http://' from the string beforehand
 */
if (isset($_SERVER["HTTP_REFERER"]))
    $exp = explode("/", preg_replace("#^(http://|https://)#", "", rawurldecode(preg_replace("#/([^/]*)$#", "", strtolower($_SERVER["HTTP_REFERER"])))));
else
    $exp = explode("/", preg_replace("#^(http://|https://)#", "", rawurldecode(preg_replace("#/([^/]*)$#", "", strtolower($_SERVER["PHP_SELF"])))));
$exp_self = explode("/", preg_replace("#^(http://|https://)#", "", rawurldecode(preg_replace("#/([^/]*)$#", "", strtolower($_SERVER["PHP_SELF"]))))); //for use in AJAX scripts - refers to actual position of AJAX script
$basepath = getcwd();

//define USE_VIRTUAL_HOST
if (preg_match('#^C:\\\wamp64\\\#', $basepath) > 0 && $_SERVER['SERVER_NAME'] != '127.0.0.1' && $_SERVER['SERVER_NAME'] != 'localhost')
    define('USE_VIRTUAL_HOST', true);
else
    define('USE_VIRTUAL_HOST', false);

//define BASEPATH - prefer HTTP_HOST over SERVER_NAME as there may be a flaw regarding prefixed www with server name
$base = (isset($_SERVER["HTTP_HOST"])) ? strtolower($_SERVER["HTTP_HOST"]) : strtolower($_SERVER["SERVER_NAME"]);
if ($_SERVER['SERVER_NAME'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'localhost') {
    preg_match("#^(/[^/]+/)#", $_SERVER["PHP_SELF"], $match);
    define('BASEPATH', 'http://' . $base . $match[1]);
} else {
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        define('BASEPATH', 'https://' . $base . '/');
    else
        define('BASEPATH', 'http://' . $base . '/');
}

//continue
if (!USE_VIRTUAL_HOST)
    unset($exp[0], $exp_self[0]);

$c = count($exp);
$c_self = count($exp_self);
if ($basepath{0} == "C") { //når jeg tester lokalt

    define("LOCATION", "local");
    $tmp = array_shift($exp);

    define("FOLDER", implode("/", $exp));
    $c -= 1;
    $c_self -= 1;
} else {
    define("LOCATION", "live");
    define("FOLDER", implode("/", $exp));
    define("FOLDER_SELF", implode("/", $exp_self));
    $c -= 0;
    $c_self -= 0;
}

//define som easy shorthands for linebreaks
define("x", "<br>\n");

define("n", "\n");
define("rn", "\r\n");

$rp = "";
for ($i = 0; $i < $c; $i++)
    $rp .= "../";

$rp_self = "";
for ($i = 0; $i < $c_self; $i++)
    $rp_self .= "../";


define("rp", $rp);
define("rp_self", $rp_self);
define("cwd", getcwd());
define("WORD_PAGEBREAK", '<p clear="all" style="page-break-before:always; font-size: 0pt; margin: 0pt;">&nbsp;</p>');
define("PAGEBREAK", '<span style="page-break-before: always; display: block;">&nbsp;</span>');


//NEW! some "quality of life" constants
define('NOW', time());
define('MINUTE', 60);
define('HOUR', MINUTE * 60);
define('DAY', HOUR * 24);
define('WEEK', 7 * DAY);
?>
