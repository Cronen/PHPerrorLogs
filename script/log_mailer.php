<?php
use PHPMailer\PHPMailer\PHPMailer;//,PHPMailer\PHPMailer\SMTP, PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] ."/PHPMailer/src/PHPMailer.php";
require_once $_SERVER['DOCUMENT_ROOT'] ."/PHPMailer/src/SMTP.php";
require_once $_SERVER['DOCUMENT_ROOT'] ."/protected/configuration.php";
require_once $_SERVER['DOCUMENT_ROOT'] ."/lib/db_class.php";

define('GUSER', 'mail@newnamtab.dk'); // Mail username
define('GPWD', 'killkenny100'); // Mail password
$data = new db_md();
$mailscriptrunning = true;
//$hourlymaillastrun = date("Y-m-d H:i:s");
//$dailymaillastrun = date("Y-m-d H:i:s");
$hourlymaillastrun = date("Y-m-d H:i", strtotime("-1 hour"));
$dailymaillastrun =  date("Y-m-d H:i", strtotime("-1 day 1 hour"));
$mailerrorlines = array();

while ($mailscriptrunning) {
    
    echo "HourlyRUN: ".$hourlymaillastrun." -> ".date("Y-m-d H:i", time() - 3600)."<br>";
    echo "DailyRUN: ".$dailymaillastrun." -> ".date("Y-m-d H:i",strtotime('-1 day'))."<br>";
    
    if ($hourlymaillastrun < date("Y-m-d H:i", time() - 3600)) {
       $mailerrorlines = getHourlyMailTableData($data); // SQLKALD for TOP 50 CRITICAL errorlines
       $hourlymaillastrun = date("Y-m-d H:i"); 
    }
    else if ($dailymaillastrun < date("Y-m-d H:i",strtotime('-1 day'))) {
       $mailerrorlines = getDailyMailTableData($data);// SQLKALD for TOP 50 samtlige errorlines
       $dailymaillastrun = date("Y-m-d H:i");
    }
    
    if (empty($mailerrorlines)) {
        continue;
    }



$htmltable = "<!DOCTYPE html>
<html>
<head>
</head>
<body>
<table style='text-align: center;' class='table table-responsive'>
<tbody>
<tr>
<th style='background-color: black; color:white; text-align: center;'>ID</th>
<th style='background-color: black; color:white; text-align: center;'>Dato</th>
<th style='background-color: black; color:white; text-align: center;'>Level</th>
<th style='background-color: black; color:white; text-align: center;'>Fejlmeddelelse</th>
<th style='background-color: black; color:white; text-align: center;'>URL</th>
<th style='background-color: black; color:white; text-align: center;'>Fil</th>
<th style='background-color: black; color:white; text-align: center;'>Linje</th>
</tr>";

//foreach($mailerrorlines as $mailerrorline)
for ($x = 0; $x <= 49; $x++)
{    
 $htmltable .='<tr>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="error'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["error_ID"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="date'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["error_date"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="level'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["php_error_level"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="errormsg'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["error_msg"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="url'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["error_location"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="fil'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["error_file"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="line'.$mailerrorlines[$x]["error_ID"].'">'.$mailerrorlines[$x]["error_line"].'</td>
</tr>';  
}

$htmltable .="</tbody></table>";

if(count($mailerrorlines)>=50)
{
  $htmltable .= "<p>Der findes yderligere ".(errorRowCount($mailerrorlines,$data)-50)." errors</p>";
}

$htmltable .="</body>
</html>";

//$mailto = "mathiascvdieu@hotmail.com";
$mailto = "newnamtab@yahoo.dk";
$mailfrom = "mail@newnamtab.dk";
$mailsubject = "Errorlog opsummering";
// Nok lidt overflødigt, men jeg havde en større plan med 2 variabelnavne;
$mailmessage = $htmltable;

// LEFTOVER COMMENTS.. PLEASE DONT DELETE JUST YET.
// Always set content-type when sending HTML email
//$mailheaders = "MIME-Version: 1.0" . "\r\n";
//$mailheaders .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//$mailheaders .= 'From: <dankwebmaster@dankbois.com>' . "\r\n";
if (smtpmailer($mailto, $mailfrom, "Dank Bois Inc.", $mailsubject, $mailmessage)) {
    echo "EMAIL SENT!";
    
    // REMOVE BEFORE USE
    $mailscriptrunning= false;
}else
{
   echo "ERROR SENDING EMAIL"; 
   
   // REMOVE BEFORE USE
   $mailscriptrunning= false;
}
}
exit;

function getDailyMailTableData($db)
{

    $table_sql = "
        SELECT `error_ID`,`error_date`,`php_error_level`,`error_msg`,`error_location`,`error_file`,`error_line`
        FROM `php_error`
        WHERE NOT `mailsent` = 1
        order by `error_date`;";
        //LIMIT 50;";

    $table_data = $db->makeArray($table_sql);
    
    return $table_data;
}
function getHourlyMailTableData($db)
{       
//        $table_sql = "
//        SELECT `error_ID`,`error_date`,`php_error_level`,`error_msg`,`error_location`,`error_file`,`error_line`
//        FROM `php_error`
//        WHERE `error_date` > NOW() - INTERVAL 1 HOUR
//        AND (`php_error_level` = 3 OR `php_error_level` = 4 OR `php_error_level` = 11)
//        order by `error_date`
//        LIMIT 50;";
        $table_sql = "
        SELECT `error_ID`,`error_date`,`php_error_level`,`error_msg`,`error_location`,`error_file`,`error_line`
        FROM `php_error`
        WHERE (`php_error_level` = 3 OR `php_error_level` = 4 OR `php_error_level` = 11)
        order by `error_date`;";
        //LIMIT 50;";
        
        $table_data = $db->makeArray($table_sql);

        return $table_data;
    
}

function errorRowCount ($errorArray,$db)
{  
    
    $rownum = count($errorArray);
    foreach ($errorArray as $errorId) {
    $set_mail_sent_sql = "UPDATE `php_error` set `mailsent` = 1 WHERE `error_ID` =".$errorId['error_ID'];
    $db->addData($set_mail_sent_sql);
    }
    return $rownum;
}

function smtpmailer($to, $from, $from_name, $subject, $body) { 
	global $error;
	$mail = new PHPMailer;   // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->IsHTML(true);
        $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = 'send.one.com';
	$mail->Port = 465; 
	$mail->Username = GUSER;  
	$mail->Password = GPWD;           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Message sent!';
		return true;
	}
}
//?>
