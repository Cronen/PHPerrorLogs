<?php
use PHPMailer\PHPMailer\PHPMailer;//,PHPMailer\PHPMailer\SMTP, PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] ."/PHPMailer/src/PHPMailer.php";
require_once $_SERVER['DOCUMENT_ROOT'] ."/PHPMailer/src/SMTP.php";
require_once $_SERVER['DOCUMENT_ROOT'] ."/protected/configuration.php";
require_once $_SERVER['DOCUMENT_ROOT'] ."/lib/db_class.php";




define('GUSER', 'mail@newnamtab.dk'); // Mail username
define('GPWD', 'killkenny100'); // Mail password

$mailerrorlines = getTableData();// SQLKALD for TOP 50 samtlige errorlines OR whatever


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
foreach($mailerrorlines as $mailerrorline)
{
 $htmltable .='<tr>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="error'.$mailerrorline["error_ID"].'">'.$mailerrorline["error_ID"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="date'.$mailerrorline["error_ID"].'">'.$mailerrorline["error_date"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="level'.$mailerrorline["error_ID"].'">'.$mailerrorline["php_error_level"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="errormsg'.$mailerrorline["error_ID"].'">'.$mailerrorline["error_msg"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="url'.$mailerrorline["error_ID"].'">'.$mailerrorline["error_location"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="fil'.$mailerrorline["error_ID"].'">'.$mailerrorline["error_file"].'</td>
<td style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width: 180px;" id="line'.$mailerrorline["error_ID"].'">'.$mailerrorline["error_line"].'</td>
</tr>';  
}
$htmltable .="</tbody>
</table>
</body>
</html>";

$mailto = "mathiascvdieu@hotmail.com";
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
}else
{
   echo "ERROR SENDING EMAIL"; 
}

exit;
function getTableData()
{
    $data = new db_md;
//    $table_sql = "
//        SELECT `error_ID`,`error_date`,`php_error_level`,`error_msg`,`error_location`,`error_file`,`error_line`
//        FROM `php_error`
//        WHERE `error_date` > NOW() - INTERVAL 1 HOUR
//        order by `error_date`
//        LIMIT 50;";
    $table_sql = "
        SELECT `error_ID`,`error_date`,`php_error_level`,`error_msg`,`error_location`,`error_file`,`error_line`
        FROM `php_error`
        order by `error_date`
        LIMIT 50;";

    $table_data = $data->makeArray($table_sql);
    return $table_data;
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
