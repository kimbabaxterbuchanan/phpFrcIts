<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class notificationDAO {

    function notificationDAO() {
    }

    function getAllNotifications() {
        $qry = "select * from notification order by teamName";
        return @mysql_query($qry);
    }
    function getNotificationByID($id) {
        $qry = "select * from notification where id = '$id'";
        return @mysql_query($qry);
    }

    function getNotificationByTeamName($tmName) {
        $qry = "select * from notification where teamName = '$tmName'";
        return @mysql_query($qry);
    }

    function saveUpdateNotificationById ($id,$userId,$careerId,$emailId,$status,$toMail,$replyMail,
        $ccMail,$message,$tmName,$create_dt ) {

        if ( $id == "" ) {

            $create_dt = date("Y/m/d H:i:s");
            $qry = "INSERT INTO notification(userId,careerId,emailId,status,
                                    toMail,replyMail,ccMail,message,
                                    teamName, create_dt) VALUES
                                    ('$userId','$careerId','$emailId','$status',
                                    '$toMail','$replyMail','$ccMail','$message',
                                    '$tmName','$create_dt')";
        } else {
            $qry = "update notification set userId = '".$userId."',
                                careerId = '".$careerId."',
                                emailId = '".$emailId."',
                                status = '".$status."',
                                toMail = '".$toMail."',
                                replyMail = '".$replyMail."',
                                ccMail = '".$ccMail."';,
                                message = '".$message."',
                                teamName = '".$tmName."' where id = '".$id."'";
        }
        $stat = @mysql_query($qry);
        if ( ! $stat ) {
            echo $qry."<BR>";
            echo '<ul class="err">';
            echo  "Save or Update on ".$table." failed with Error: ".mysql_error();
            echo '</ul>';
            $errmsg_arr[] = "Delete on ".$table." failed with Error: ".mysql_error();
            exit();
        }
        return $stat;

    }

    function deleteNotificationsById ($id) {
        $qry = "delete from notification where id = '".$id."'";
        return @mysql_query($qry);
    }
}?>