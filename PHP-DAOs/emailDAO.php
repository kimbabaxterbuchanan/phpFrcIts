<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class emailDAO {

    function emailDAO() {
    }

    function getNextSeqNum() {
        $qry = "select seq_num from email order by seq_num DESC limit 1";
        $result = @mysql_query($qry);
        if ( $result ) {
            $res = mysql_fetch_assoc($result);
            if ( is_numeric($res['seq_num']))
                $seq_num = $res['seq_num']+1;
        }
        return $seq_num;
    }

    function getAllEmails() {
        $qry = "select * from email order by teamName";
        return @mysql_query($qry);
    }
    function getEmailByID($id) {
        $qry = "select * from email where id = '$id'";
        return @mysql_query($qry);
    }

    function getEmailByTeamName($tmName) {
        $qry = "select * from email where teamName = '$tmName'";
        return @mysql_query($qry);
    }

    function saveUpdateEmailById ($id,$seq_num, $smtpPort, $mailHost, $isHTML, $smtpAuth, $smtpUsername,
        $smtpPassword, $toMail, $toName, $fromMail,$fromName,$replyMail,
        $replyName,$ccMail, $ccName, $subject, $body, $altBody,
        $tmName,$create_dt ) {
        if ( $id == "" ) {

            $create_dt = date("Y/m/d H:i:s");
            $qry = "INSERT INTO email(seq_num, smtpPort, mailHost, isHTML, smtpAuth, smtpUsername,
                                    smtpPassword, toMail, toName, fromMail,fromName,replyMail,
                                    replyName, ccMail, ccName, subject, body, altBody,
                                    teamName, create_dt) VALUES
                                    ('$seq_num', '$smtpPort', '$mailHost', '$isHTML', '$smtpAuth', '$smtpUsername',
                                    '$smtpPassword', '$toMail', '$toName', '$fromMail','$fromName','$replyMail',
                                    '$replyName','$ccMail', '$ccName', '$subject', '$body', '$altBody',
                                    '$tmName','$create_dt')";
            $result = @mysql_query($qry);
            return $result;
        } else {
            $qry = "update email set  seq_num = '".$seq_num."',
                                smtpPort = '".$smtpPort."',
                                mailHost = '".$mailHost."',
                                isHTML =  '".$isHTML."',
                                smtpAuth =  '".$smtpAuth."',
                                smtpUsername =  '".$smtpUsername."',
                                smtpPassword =  '".$smtpPassword."',
                                toMail =  '".$toMail."',
                                toName =  '".$toName."',
                                fromMail ='".$fromMail."',
                                fromName = '".$fromName."',
                                replyMail = '".$replyMail."',
                                replyName = '".$replyName."',
                                ccMail =  '".$ccMail."',
                                ccName =  '".$ccName."',
                                subject =  '".$subject."',
                                body =  '".$body."',
                                altBody = '".$altBody."',
                                teamName = '".$tmName."' where id = '".$id."'";
            $stat = @mysql_query($qry);
            if ( ! $stat ) {
                echo $qry."<BR>";
                echo '<ul class="err">';
                echo  "Save or Update on ".$table." failed with Error: ".mysql_error();
                echo '</ul>';
                $errmsg_arr[] = "Delete on ".$table." failed with Error: ".mysql_error();
            }
            return $stat;

        }
    }

    function deleteEmailsById ($id) {
        $qry = "delete from email where id = '".$id."'";
        return @mysql_query($qry);
    }
}?>