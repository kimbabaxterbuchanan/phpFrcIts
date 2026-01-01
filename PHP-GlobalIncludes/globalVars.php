<?php
//Start session
global $homeURL, $homeLoc, $hdrLocation, $paramString, $login, $loginId, $isLogedIn, $isAwarded;
global $webAdmin, $teamManager, $userCompanyId, $createTeamDirAry, $createWorkDirAry, $key;
global $curDir, $companyDir, $awardDir, $awardWorkDir, $libDir, $libWorkDir, $directoryWorkHome, $directoryHome;
global $isHTML,$smtpAuth ,$smtpUsername,$smtpPassword ,$port,$wordWrap,$defaultMailHost,$toMail;
global $toName,$fromMail,$fromName,$replyMail,$replyName,$subject,$body,$altBody;
global $notifyUserId, $notifyCareerId, $notifyEmailId, $notifyTeamName, $table, $extra;

$homeURL = "www.gmail.com";
$homeLoc = "TeamFRC.php";

$hdrLocation = "";
$paramString = "";

$companyLogo = "itssb_banner9.jpg";

$section = "";

$sub_section = "";

$isLogedIn = "no";
$isAwarded="no";

$awardDir="proposal";
$libDir = "library";

$salt = "0123456789abcdef";
$awardWorkDir="proposal";

$libWorkDir = "library";
$proposalWorkDir="proposal";
$teamWorkDir="teamFRC";
$finalWorkDir="Final";

$createTeamDirAry = Array();
$createTeamDirAry[] = $libWorkDir;
//$createTeamDirAry[] = $proposalWorkDir;
$createTeamDirAry[] = $teamWorkDir;
$createTeamDirAry[] = $finalWorkDir;

$proposalDir="proposal";
$toAwardDir = "toAward";
$toRFQsDir = "toRFQ";
$teamDir="teamFRC";

$createWorkDirAry = Array();
$createWorkDirAry[] = $proposalDir;
$createWorkDirAry[] = $toAwardDir;
$createWorkDirAry[] = $toRFQsDir;

$teamManager="no";
$webAdmin="no";
$loginId = 0;
$curDir = $_GET['section'];
$login = "";
$teamName = $_POST['teamName'];
$userId = 0;
$directoryHome = "C:/files/";
$directoryWorkHome = "C:/files/";
$userCompanyId = "";
$companyDir = "";

$teamMemberName = "";

$smtpAuth = true;     // turn on SMTP authentication
$smtpUsername = "kbuchanan@gmail.com";  // SMTP username
$smtpPassword = "1web123"; // SMTP password
$smtpPort=587;
$isHTML = true;
//$smtpAuth = true;     // turn on SMTP authentication
//$smtpUsername = "kbuchanan@gmail.com";  // SMTP username
//$smtpPassword = "1web123"; // SMTP password
$wordWrap = "50";
$defaultMailHost = "kbuchanan.gmail.com";
$mailHost = "kbuchanan.gmail.com";
$toMail = "";
$toName = "";
$fromMail = "kbuchanan@gmail.com";
$fromName = "WebMaster";
$replyMail = "kbuchanan@gmail.com";
$replyName = "WebMaster";
$ccMail = "";
$ccName = "";
$subject = "Forgotten Password Notification";
$body = "You have identified that the password you have for your account has been forgotten and requested us to send you a new password.  Please login with the Password below and change you password immediately.";
$altBody = "You have identified that the password you have for your account has been forgotten and requested us to send you a new password.  Please login with the Password below and change you password immediately.";

$notifyUserId = "0";
$notifyCareerId = "0";
$notifyEmailId = "0";
$notifyTeamName = "";
$table = "";
$extra = "";
?>