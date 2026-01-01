<?php
require_once dirname(__FILE__) .'/../PHP-models/BaseModel.php';


class EmailModel  extends BaseModel
{
  var $seq_num = "";
  var $role = "";
  var $mailHost = "kbuchanan.gmail.com";
  var $isHTML = "yes";
  var $smtpAuth = "no";
  var $Username = "";
  var $Password = "";
  var $toMail = "";
  var $toName = "";
  var $fromMail = "careers@gmail.com";
  var $fromName = "FRC Careers";
  var $replyMail = "careers@gmail.com";
  var $replyName = "FRC Careers";
  var $ccMail = "";
  var $ccName = "";
  var $subject = "";
  var $body = "";
  var $altBody = "";
}
?>
