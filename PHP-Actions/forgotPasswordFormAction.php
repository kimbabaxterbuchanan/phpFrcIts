<?php
//Start session
session_start();

require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

//Include mail details
require_once dirname(__FILE__) .'/../PHP-Mail/mailNotification.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userProfileDAO.php';

require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/AES128.php';
$encode = new AES128();

$postForm = clean($_POST['postForm']);
$userDAO = new userDAO();
$userProfileDAO = new userProfileDAO();

if ( isset($postForm) && $postForm != "" ) {

//Sanitize the POST values
    $login = clean($_POST['login']);
    $email = clean($_POST['email']);
    $id = "0";

    if ( $webAdmin == "no" && $login == '') {
            $errmsg_arr[] = 'Login ID missing';
            $errflag = true;
        }
    if ( $webAdmin == "no" && $email == '') {
            $errmsg_arr[] = 'Email Address missing';
            $errflag = true;
        } else if ( ! checkEmailFormat($email) ) {
            $errmsg_arr[] = 'Email address has wrong Format';
            $errflag = true;            
        }
    $result=$userDAO->getUserByLogin($login);
    if(mysql_num_rows($result) == 1 && !$errflag ) {
            $user = mysql_fetch_assoc($result);
            $id = $user['id'];
            $notifyTeamName = $user['teamName'];
            $result=$userProfileDAO->getUserProfileByUserId($id);
            $profile = mysql_fetch_assoc($result);
            if ( $profile['email'] != $email ) {
                    $errmsg_arr[] = 'Email Address does not match login';
                    $errflag = true;
                }
        }
        //Check whether the query was successful or not
    if($id != "0" && !$errflag) {
            $notifyUserId = $id;
            $notifyEmailId = 0;
            $toMail = $email;
            $toName = $user['lastname'].", ".$user['firstname'];
            $pPassword = generatePassword($length=6, $strength=0);
            $body .= "<br><br>".$pPassword;
            $altBody .= "%0D%0A%0D%0A".$pPassword;
            $mailNote = new mailNotification($replyMail,$replyName,$mailHost,$fromMail,$fromName,$smtpAuth,$smtpUsername,$smtpPassword,$toMail,$toName,$ccMail,$ccName,$subject,$body,$altBody,$isHTML,$smtpPort);

            if ( $mailNote ) {
                    $salt=$encode->makeKey($salt);
                    $gPassword = $encode->blockEncrypt($pPassword,$salt);
                    $result = $userDAO->updatePassword ($gPassword, $id);
                } else {
                    echo "mailnote false<br>";
                
                }
            session_write_close();
            $hdrLocation = "";
            $paramString = "";
            doUrl($hdrLocation, $paramString, "homePage", "");
            exit();
            } else if ( !$errflag ){
                    $errmsg_arr[] = 'Email address not found...';
                    $errflag = true;            
                }
}
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
}


?>