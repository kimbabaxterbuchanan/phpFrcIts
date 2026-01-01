<?php
require_once dirname(__FILE__) .'/../PHP-models/BaseModel.php';


class NotificationModel  extends BaseModel
{
    var $userId = "";
    var $careerId = "";
    var $emailId = "";
    var $status = "";
    var $toMail = "";
    var $replyMail = "";
    var $ccMail = "";
    var $message = "";
}
?>
