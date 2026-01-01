<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) . '/../PHP-DAOs/toAwardsDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';

$postForm = clean($_POST['postForm']);
$companyDAO = new companyDAO();
$userDAO = new userDAO();
$awardedDAO = new awardedDAO();
$toAwardsDAO = new toAwardsDAO();
if ( isset($postForm) && $postForm != "" ) {
//Sanitize the POST values
    $rtnPage = clean($_POST['rtnPage']);
    $section = clean($_POST['section']);
    $sub_section = clean($_POST['sub_section']);
    $formAction = clean($_POST['formAction']);

    $cancel = clean($_POST['cancel']);
    if ( $cancel != ""){
            if ( $section == "") {
                    $section = "toAwardsInputs";
                }
            doUrl($hdrLocation, $paramString, $section, $sub_section);
        }

    $id = clean($_POST['id']);
    $docNumber = clean($_POST['docNumber']);
    if ( $formAction != "delete") {
            $title = clean($_POST['title']);
            $customer = clean($_POST['customer']);
            $value = clean($_POST['value']);
            $performancePeriod = clean($_POST['performancePeriod']);

            $received_dt = phpDateToMysqlDate(clean($_POST['received_dt']));

            $questionDue_dt = phpDateToMysqlDate(clean($_POST['questionDue_dt']));

            $draftDue_dt = phpDateToMysqlDate(clean($_POST['draftDue_dt']));

            $finalDue_dt = phpDateToMysqlDate(clean($_POST['finalDue_dt']));

            $bid = clean($_POST['bid']);
            $results = clean($_POST['results']);
            $notes = clean($_POST['notes']);
            $tmName = clean($_POST['tmName']);
            $userId = clean($_POST['userId']);
            $posted = clean($_POST['posted']);
            $companyId = clean($_POST['companyId']);
            if ( isset($companyId) && $companyId == "" )
                    $companyId = "0";

                        //Input Validations
            if($docNumber == '') {
                    $errmsg_arr[] = 'To Awards Document Number missing';
                    $errflag = true;
                }
            if($title == '') {
                    $errmsg_arr[] = 'To Awards title missing';
                    $errflag = true;
                }
                //If there are input validations, redirect back to the registration form

                //Create INSERT query
            $cDate = date("Y-m-d H:i:s");
            $posted=$cDate;
            if ( !$errflag && $formAction != "view") {
                    $result = $toAwardsDAO->saveUpdateToAwardById ($id,$docNumber, $title,$customer, $value, $performancePeriod, $received_dt,
                        $questionDue_dt, $draftDue_dt, $finalDue_dt, $bid, $results, $notes, 
                        $tmName, $usrId, $posted, $companyId);

                        //Check whether the query was successful or not
                    if($result) {
                            if ( $id == "" ) {
                                  mkDirectory($homeDirectory.$awardDir."/toAwards/".$docNumber."/");
                            }
                            if ( $section == "") {
                                    $section = "toAwardsInputs";
                                }
                            doUrl($hdrLocation, $paramString, $section, $sub_section);
                        } else {
                            $errmsg_arr[] = 'Unable to Save or Update User.';
                            $errflag = true;
                        }
                }
        } else {
            $result = $toAwardsDAO->deleteToAwardById ($id);
                //Check whether the query was successful or not
            if($result) {
                    rmDirectory($homeDirectory.$awardDir."/toAwards/".$docNumber."/");
                    if ( $section == "") {
                            $section = "toAwardsInputs";
                        }
                    doUrl($hdrLocation, $paramString, $section, $sub_section);
                } else {
                    $errmsg_arr[] = 'Unable to delete user.';
                    $errflag = true;
                }
        }
}
//Create INSERT query
if ( $sub_section == "listToAwards" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
            $result = $toAwardsDAO->getAllToAwards();
        } else {
            $result = $toAwardsDAO->getToAwardByOnlyTeamName($teamName);
        }
} else {
    $result = $toAwardsDAO->getToAwardById($id);
}

//Check whether the query was successful or not
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
}
$teamResult = $awardedDAO->getAwardByTeamName($teamName);
$userResult = $userDAO->getUserByOnlyTeamName($teamName);
$companyResult = $companyDAO->getCompanyByOnlyTeamName($teamName);
if ( $webAdmin == "yes" ) {
    $teamResult = $awardedDAO->getAllAwards($teamName);
    $userResult = $userDAO->getAllUsers($teamName);
    $companyResult = $companyDAO->getAllCompanies($teamName);
}

?>