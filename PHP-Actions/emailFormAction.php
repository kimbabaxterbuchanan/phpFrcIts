<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/emailDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';

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

    switch ($formAction){
        case "delete": 
            $stat = $emailDAO->deleteEmailsById($id);
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
                }
            break;
        Default:
            $stat = $emailDAO->saveUpdateEmailById ($id,$seq_num, $smtpPort, $mailHost, $isHTML, $smtpAuth, $smtpUsername,
                                    $smtpPassword, $toMail, $toName, $fromMail,$fromName,$replyMail,
                                    $replyName,$ccMail, $ccName, $subject, $body, $altBody,
                                    $tmName,$create_dt );
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
                }
            break;
        }
} 
//Create INSERT query
if ( $sub_section == "listEmail" && ! isset($formAction) ) {
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
