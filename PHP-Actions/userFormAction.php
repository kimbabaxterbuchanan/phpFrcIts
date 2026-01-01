<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userProfileDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/primeContactsDAO.php';

require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/AES128.php';
$encode = new AES128();

$postForm = clean($_POST['postForm']);
$userProfileDAO = new userProfileDAO();
$userDAO = new userDAO();
$primeContactsDAO = new primeContactsDAO();
$awardedDAO = new awardedDAO();

if ( isset($postForm) && $postForm != "" ) {
//Sanitize the POST values
    $rtnPage = clean($_POST['rtnPage']);
    $section = clean($_POST['section']);
    $sub_section = clean($_POST['sub_section']);
    $formAction = clean($_POST['formAction']);

    $cancel = clean($_POST['cancel']);
    if ( $cancel != ""){
            doUrl($hdrLocation, $paramString, $section, $sub_section);
        }

    $id = clean($_POST['id']);
    if ( $formAction != "delete") {
            $userNum = clean($_POST['userNum']);
            $fname = clean($_POST['fname']);
            $lname = clean($_POST['lname']);
            $login = clean($_POST['login']);
            $password = clean($_POST['password']);
            $cpassword = clean($_POST['cpassword']);
            $orgPassword = clean($_POST['orgPassword']);
            $orgCPassword = clean($_POST['orgCPassword']);
            $tmManager = clean($_POST['tmManager']);
            if ( $tmManager == "")
                    $tmManager = "no";
            $wbAdmin = clean($_POST['wbAdmin']);
            if ( $wbAdmin == "")
                    $wbAdmin = "no";
            $tmName = clean($_POST['tmName']);
            
            if ( $wbAdmin == "yes") {
                    $tmName="web";
                }            
                //Input Validations
            if($fname == '') {
                    $errmsg_arr[] = 'First name missing';
                    $errflag = true;
                }
            if($lname == '') {
                    $errmsg_arr[] = 'Last name missing';
                    $errflag = true;
                }
            if($login == '') {
                    $errmsg_arr[] = 'Login ID missing';
                    $errflag = true;
                }
            if($password != '' && $cpassword != '' ) {
                    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
                    $errflagChk = checkPassword($password);
                
                    if ( !$errflagChk ) {
                            if( strcmp($password, $cpassword) != 0 ) {
                                    $errmsg_arr[] = 'Passwords do not match';
                                    $errflag = true;
                                } else {
                                    $salt=$encode->makeKey($salt);
                                    $password = $encode->blockEncrypt($password,$salt);
                                }
                        } else {
                            $errmsg_arr = $_SESSION['ERRMSG_ARR'];
                            session_write_close();
                            $errflag = true;
                        }
                } else {
                    $password = $orgPassword;
                    if ( $password == '') 
                            $errmsg_arr[] = 'Password is missing';
                    if ( $cpassword == '' && $password == '' ) 
                            $errmsg_arr[] = 'Confirm Password is missing';
                
                    if ( ($cpassword == '' && $password == '') || ( $password == '') ) 
                            $errflag = true;
                
                }
                
                //If there are input validations, redirect back to the registration form
                
                //Create INSERT query
            if ( !$errflag ) {
                    $result = $userDAO->saveUpdateUserById ($id,$userNum,$fname,$lname,$login,$password,$tmManager,$wbAdmin,$tmName);
                        
                        //Check whether the query was successful or not
                    if($result) {
                            if ( $teamManager == "yes" ) {
                                    if ( $id == '') {
                                            $result = $userDAO->getUserByLoginTeamName($login,$tmName);
                                            $res = mysql_fetch_assoc($result);
                                        
                                            $hdrLocation="userProfileForm.php";
                                            $id=$res['id'];
                                            $paramString="id=".$id;
                                        }
                                } else {
                                    $section="homePage";
                                    $sub_section="";
                                }
                            doUrl($hdrLocation, $paramString, $section, $sub_section);
                        } else {
                            $errmsg_arr[] = 'Unable to Save or Update User.';
                            $errflag = true;
                        }
                
                }
        } else {
            $result = $userDAO->deleteUserById ($id);
            if($result) {
                    $url = "http://www.gmail.com/TeamFRC.php?section=".$section;
                    if ( isset($sub_section) && $sub_section != "" ) {
                            $url .= "&sub_section=".$sub_section;
                        }
                    echo "<script language=\"Javascript\">
                        top.location=\"".$url."\";
                        </script>";
                    exit();
                } else {
                    $errmsg_arr[] = 'Unable to delete user.';
                    $errflag = true;
                }
        }

}
//Create INSERT query
if ( $sub_section == "listUser" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
        $result = $userDAO->getAllUsers();
        } else {
            $result = $userDAO->getUserByTeamName($teamName);
        }
} else {
    $result = $userDAO->getUserByID($id);
}
$teamResult = $awardedDAO->getAllAwards();

//Check whether the query was successful or not
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
        //            header("location: userForm.php?id=".$id);
        //            exit();
}

//if($errflag) {
//    require_once('SQLErrorInclude.php');
//}

?>