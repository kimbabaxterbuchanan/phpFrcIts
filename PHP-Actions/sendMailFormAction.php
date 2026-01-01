<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/encdec.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/emailDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';

require_once dirname(__FILE__) .'/../PHP-Mail/mailNotification.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/AES128.php';
$decode = new AES128();

$userDAO = new userDAO();

$awardedDAO = new awardedDAO();

$emailDAO = new emailDAO();
$table = "email";
foreach ( $_POST as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_GET as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_REQUEST as $key => $val ) {
    $$key = clean($val);
}
if ( isset($postForm) && $postForm != "" ) {
    //Sanitize the POST values
    if ( $cancel != ""){
        doUrl($hdrLocation, $paramString, $section, $sub_section);
    }
    if ( $toType == "member") {
        if ( $sendType == "password") {
            $qry = "select u.id, p.email, u.login, u.passwd from user u, userprofile p where u.id = p.userId and p.email = '".$toMail."'";
            $user = $userDAO->executeQry($qry);
            $res = mysql_fetch_assoc($user);
            $salt=$decode->makeKey($salt);
            if ( $resetPassword != "" ) {
                $pPassword = generatePassword($length=10, $strength=4);
                $gPassword = $decode->blockEncrypt($pPassword, $salt);
                $result = $userDAO->updatePassword ($gPassword, $res['id']);
            } else {
                $pPassword = $decode->blockDecrypt($res['passwd'],$salt);
            }
            $body = $body."<br><br>".$pPassword;
            $altBody = $altBody."%0D%0A%0D%0A".$pPassword;
        }
        $mailNote = new mailNotification($replyMail,$replyName,$mailHost,$fromMail,$fromName,$smtpAuth,$smtpUsername,$smtpPassword,$toMail,$toName,$ccMail,$ccName,$subject,$body,$altBody,$isHTML,$smtpPort);
    } else if ( $toType != "member" and $sendType == "password") {
        $qry = "select u.id, p.email, u.lastname, u.firstname, u.login, u.passwd from user u, companies c, userprofile p where p.userId = u.id and p.companyId = c.id and c.id = '".$companyId."'";
        if ( $toType == "all") {
            if ( $tmName != "" ) {
                $qry = "select u.id, p.email, u.lastname, u.firstname, u.login, u.passwd from user u, companies c, userprofile p where p.userId = u.id and p.companyId = c.id and c.teamName = '".$tmName."' and u.login like '%@%'";
            } else {
                $qry = "select u.id, p.email, u.lastname, u.firstname, u.login, u.passwd from user u, companies c, userprofile p where p.userId = u.id and p.companyId = c.id and u.login like '%@%'";
            }
        }
        $mailList = $userDAO->executeQry($qry);
        $salt=$decode->makeKey($salt);
        $bdy = $body;
        $abdy = $altBody;
        while($res = mysql_fetch_array($mailList))
        {
            if ( $resetPassword != "" ) {
                $pPassword = generatePassword($length=10, $strength=4);
                $gPassword = $decode->blockEncrypt($pPassword, $salt);
                $result = $userDAO->updatePassword ($gPassword, $res['id']);
            } else {
                $pPassword = $decode->blockDecrypt($res['passwd'],$salt);
            }
            $body = $bdy."<br><br>".$pPassword;
            $altBody = $abdy."%0D%0A%0D%0A".$pPassword;
            $toMail .= $res['email'];
            $toName .= $res['lastname'].", ".$res['firstname'];
            $mailNote = new mailNotification($replyMail,$replyName,$mailHost,$fromMail,$fromName,$smtpAuth,$smtpUsername,$smtpPassword,$toMail,$toName,$ccMail,$ccName,$subject,$body,$altBody,$isHTML,$smtpPort);
        }
    } else if ( $toType == "teammember" ) {
        $qry = "select u.id, p.email, u.lastname, u.firstname, u.login, u.passwd from user u, companies c, userprofile p where p.userId = u.id and p.companyId = c.id and c.id = '".$companyId."'";
        $mailList = $userDAO->executeQry($qry);
        $salt=$decode->makeKey($salt);
        $bdy = $body;
        $abdy = $altBody;
        $scomma="";
        while($res = mysql_fetch_array($mailList))
        {
            if ( $sendType == "password") {
                if ( $resetPassword != "" ) {
                    $pPassword = generatePassword($length=10, $strength=4);
                    $gPassword = $decode->blockEncrypt($pPassword, $salt);
                    $result = $userDAO->updatePassword ($gPassword, $res['id']);
                } else {
                    $pPassword = $decode->blockDecrypt($res['passwd'],$salt);
                }
                $body = $bdy."<br><br>".$pPassword;
                $altBody = $abdy."%0D%0A%0D%0A".$pPassword;
            }
            $toMail .= $scomma.$res['email'];
            $toName .= $scomma.$res['lastname'].", ".$res['firstname'];
            $scomma ="; ";
        }
        $mailNote = new mailNotification($replyMail,$replyName,$mailHost,$fromMail,$fromName,$smtpAuth,$smtpUsername,$smtpPassword,$toMail,$toName,$ccMail,$ccName,$subject,$body,$altBody,$isHTML,$smtpPort);
    } else {
        if ( $tmName != "" ) {
            $qry = "select id from companies where teamName = '".$tmName."' order by id";
        } else {
            $qry = "select id from companies order by id";
        }
        $companyIds = $userDAO->executeQry($qry);
        $scomma="";
        $bdy = $body;
        $abdy = $altBody;
        $salt=$decode->makeKey($salt);
        while($company = mysql_fetch_array($companyIds))
        {
            $qry = "select u.id, p.email, u.lastname, u.firstname, u.login, u.passwd from user u, companies c, userprofile p where p.userId = u.id and p.companyId = c.id and c.id = '".$company['id']."'";
            $mailList = $userDAO->executeQry($qry);
            while($res = mysql_fetch_array($mailList))
            {
                if ( $sendType == "password") {
                    if ( $resetPassword != "" ) {
                        $pPassword = generatePassword($length=10, $strength=4);
                        $gPassword = $decode->blockEncrypt($pPassword, $salt);
                        $result = $userDAO->updatePassword ($gPassword, $res['id']);
                    } else {
                        $pPassword = $decode->blockDecrypt($res['passwd'],$salt);
                    }
                    $body = $bdy."<br><br>".$pPassword;
                    $altBody = $abdy."%0D%0A%0D%0A".$pPassword;
                }
                $toMail .= $scomma.$res['email'];
                $toName .= $scomma.$res['lastname'].", ".$res['firstname'];
                $scomma ="; ";
            }
        }
        $mailNote = new mailNotification($replyMail,$replyName,$mailHost,$fromMail,$fromName,$smtpAuth,$smtpUsername,$smtpPassword,$toMail,$toName,$ccMail,$ccName,$subject,$body,$altBody,$isHTML,$smtpPort);
    }
} 
//Create INSERT query
if ( $sub_section == "listToAwards" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
        $result = $emailDAO->getAllEmails();
    } else {
        $result = $emailDAO->getToEmailByTeamName($teamName);
    }
} else {
    $result = $emailDAO->getEmailByID($id);
}

$teamResult = $awardedDAO->getAllAwards();

//Check whether the query was successful or not
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    $_SESSION['ERRFLAG'] = "set";
    session_write_close();
}

if(!$result) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}
$emailListRequired="xXxccMail";
?> 
