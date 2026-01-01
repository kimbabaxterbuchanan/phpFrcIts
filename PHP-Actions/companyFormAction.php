<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';

$postForm = clean($_POST['postForm']);
$awardedDAO = new awardedDAO();
$companyDAO = new companyDAO();

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
    $name = clean($_POST['name']);
    $tmName = clean($_POST['tmName']);

    if ( $formAction != "delete") {
            $companyNum = clean($_POST['companyNum']);
            $logo = clean($_POST['logo']);
            $displayname = clean($_POST['displayname']);
            $website = clean($_POST['website']);
                
                //Input Validations
            if($name == '') {
                    $errmsg_arr[] = 'Company name missing';
                    $errflag = true;
                }
            if($logo == '') {
                    $errmsg_arr[] = 'Company Logo Image File name missing';
                    $errflag = true;
                }
            if($displayname == '') {
                    $errmsg_arr[] = 'Company Display Name missing';
                    $errflag = true;
                }
            if($website == '') {
                    $errmsg_arr[] = 'Company website missing';
                    $errflag = true;
                }
                //If there are input validations, redirect back to the registration form
                
                //Create INSERT query
            if ( !$errflag ) {
                    $result = $companyDAO->saveUpdateCompanyById ($id,$companyNum,$name,$logo,$displayname,$website,$tmName);
                        
                        //Check whether the query was successful or not
                    if( $result ) {
                            if ( $teamManager == "yes" ) {
                                    if ( $id == '') {
                                            $result = $companyDAO->getCompanyByNumberTeamName($companyNum, $tmName);
                                            $res = mysql_fetch_assoc($result);
                                            $hdrLocation="companyProfileForm.php";
                                            $id=$res['id'];
                                            $paramString="companyId=".$id;
                                        }
                                } else {
                                    $section="homePage";
                                    $sub_section="";
                                }
                            doUrl($hdrLocation, $paramString, $section, $sub_section);
                        } else {
                            $errmsg_arr[] = 'Unable to Save or Update Company.';
                            $errflag = true;
                        }
                }
        } else {
            $result = $companyDAO->deleteCompanyById($id,$tmName,$name);
                //Check whether the query was successful or not
            if($result) {
                    doUrl($hdrLocation, $paramString, $section, $sub_section);
                } else {
                    $errmsg_arr[] = 'Unable to delete company\'s profile.';
                    $errflag = true;
                }
        }
}
//Create INSERT query
if ( $sub_section == "listCompany" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
            $result = $companyDAO->getAllCompanies();
        } else {
            $result = $companyDAO->getCompanyByOnlyTeamName($teamName);
        }
} else {
    $result = $companyDAO->getCompanyById($id);
}

$teamResult = $awardedDAO->getAwardByTeamName('All');

if ( $webAdmin == "yes" ) {
    $teamResult = $awardedDAO->getAllAwards();
}

//Check whether the query was successful or not
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
        //            header("location: userForm.php?id=".$id);
        //            exit();
}

if(!$teamResult) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}

?>