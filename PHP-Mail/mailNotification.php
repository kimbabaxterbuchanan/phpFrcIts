<?php
require_once dirname(__FILE__) ."/../PHP-GlobalIncludes/globalVars.php";
require_once dirname(__FILE__) .'/../PHP-DAOs/notificationDAO.php';

require_once dirname(__FILE__)."/class.phpmailer.php";

class mailNotification {

    function mailNotification ($replyMail,$replyName,$mailHost,$fromMail,$fromName,$smtpAuth,$smtpUsername,$smtpPassword,$toMail,$toName,$ccMail,$ccName,$subject,$body,$altBody,$isHTML,$port) {
        global $errflag, $errmsg_arr,$teamName;
        global $notifyUserId, $notifyCareerId, $notifyEmailId, $notifyTeamName;

        $notificationDAO = new NotificationDAO();
        $table = "notification";
        $mail = new PHPMailer();

        $mail->IsSMTP();                                      // set mailer to use SMTP
        $mail->Host = $mailHost;  // specify main and backup server

        $mail->SMTPAuth = $smtpAuth;     // turn on SMTP authentication
        $tpauth = "";
        if ( $smtpAuth ) {
            $mail->Username = $smtpUsername;  // SMTP username
            $mail->Password = $smtpPassword; // SMTP password
            $tpauth="set";
        }

        $mail->From = $fromMail;
        $mail->FromName = $fromName;

        if ( strpos($toMail,";") ) {
            $tmpMailAry = explode(";",$toMail);
            $tmpNameAry = explode(";",$toName);
            for ( $i = 0; $i < count($tmpMailAry); $i++ ) {
                $mail->AddAddress($tmpMailAry[$i], $tmpNameAry[$i]);
            }
        } else {
            if ( $toMail != "")
                $mail->AddAddress($toMail, $toName);
        }

        if ( strpos($replyMail,";") ) {
            $tmpMailAry = explode(";",$replyMail);
            $tmpNameAry = explode(";",$replyName);
            for ( $i = 0; $i < count($tmpMailAry); $i++ ) {
                $mail->AddReplyTo($tmpMailAry[$i], $tmpNameAry[$i]);
            }
        } else {
            if ( $replyMail != "")
                $mail->AddReplyTo($replyMail, $replyName);
        }

        $mail->Port = $port;
        if ( strpos($ccMail,";") ) {
            $tmpMailAry = explode(";",$ccMail);
            $tmpNameAry = explode(";",$ccName);
            for ( $i = 0; $i < count($tmpMailAry); $i++ ) {
                $mail->AddCC($tmpMailAry[$i], $tmpNameAry[$i]);
            }
        } else {
            if ( $ccMail != "")
                $mail->AddCC($ccMail, $ccName);
        }

        $mail->WordWrap = "50";                                 // set word wrap to 50 characters
        $mail->IsHTML($isHTML);                                  // set email format to HTML

        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        //            $mail->Body    = $body;
        $mail->AltBody = $altBody;
        //            $mail->AddAttachment("../PHP-GlobalIncludes/globalVars.php");
//        $mail->PrintHeader();
        $stat = $mail->Send();
        if (! $stat ) {
            //Start session
            session_start();
            $errmsg_arr[] = $mail->ErrorInfo;
            echo $mail->ErrorInfo."<BR>";
            exit();
            $errflag = true;
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            $_SESSION['ERRFLAG'] = "set";
            session_write_close();
        }
        $id = "";
        $create_dt = "";
        $status = ( $stat ? "Message Sent" : "Message Failed");
        $stt = $notificationDAO->saveUpdateNotificationById($id,$notifyUserId,$notifyCareerId,$notifyEmailId,$status,$toMail,$replyMail,
        $ccMail,$subject,$teamName,$create_dt);

 
        return $stat;
        }
}
?>
