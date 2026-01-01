<?php
//Start session
session_start();

require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userProfileDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/AES128.php';

$encode = new AES128();

$userProfileDAO = new userProfileDAO();
$userDAO = new userDAO();
$companyDAO = new companyDAO();
$awardedDAO = new awardedDAO();

//Sanitize the POST values
$login = clean($_POST['login']);
$password = clean($_POST['password']);

//Input Validations
if($login == '') {
    $errmsg_arr[] = 'Login ID missing';
    $errflag = true;
}
if($password == '') {
    $errmsg_arr[] = 'Password missing';
    $errflag = true;
}

//If there are input validations, redirect back to the login form
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: /TeamFRC.php");
    exit();
}

//Create query
$salt=$encode->makeKey($salt);
$gPassword = $encode->blockEncrypt($password,$salt);
$result = $userDAO->getUserByLoginPassword($login,$gPassword);

//Check whether the query was successful or not
if($result) {
    if(mysql_num_rows($result) == 1) {
        //Login Successful
            session_regenerate_id();
            $member = mysql_fetch_assoc($result);
            $_SESSION['SESS_MEMBER_ID'] = $member['id'];
            $_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
            $_SESSION['SESS_LAST_NAME'] = $member['lastname'];
            $_SESSION['TEAMMANAGER'] = $member['teamManager'];
            $_SESSION['WEBADMIN'] = $member['webAdmin'];
            $_SESSION['WORKDIR'] = $member['login'];
            $_SESSION['LOGIN'] = $member['login'];
            if ( $member['teamName'] != "web" ) {
                    $_SESSION['TEAMNAME'] = $member['teamName'];
                    $result_profile = $userProfileDAO->getUserProfileByUserId($member['id']);
                    $profile = mysql_fetch_assoc($result_profile);
                    $_SESSION['COMPANYID'] = $profile['companyId'];
                
                    $result_company = $companyDAO->getCompanyById($profile['companyId']);
                    $company = mysql_fetch_assoc($result_company);
                    $_SESSION['COMPANYDIR'] = $company['name'];
                    $result_award = $awardedDAO->getAwardByTeamName($member['teamName']);
                    $award = mysql_fetch_assoc($result_award);
                    $_SESSION['ISAWARDED'] = $award['awarded'];
                }
            session_write_close();
            $hdrLocation = "";
            $paramString = "";
            doUrl($hdrLocation, $paramString, "homePage", "");
            exit();
        }else {
        //Login failed
            $errmsg_arr[]="Login Failed... Try again...";
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            $_SESSION['ERRFLAG'] = "set";
            session_write_close();
            $hdrLocation = "";
            $paramString = "";
            doUrl($hdrLocation, $paramString, $section, $sub_section);
            exit();
        }
}else {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}
?>