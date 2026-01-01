<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) . '/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) . '/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) . '/../PHP-DAOs/proposalsDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';

$postForm = clean($_POST['postForm']);
$companyDAO = new companyDAO();
$userDAO = new userDAO();
$awardedDAO = new awardedDAO();
$proposalsDAO = new proposalsDAO();

if ( isset($postForm) && $postForm != "" ) {
//Sanitize the POST values
    $rtnPage = clean($_POST['rtnPage']);
    $section = clean($_POST['section']);
    $sub_section = clean($_POST['sub_section']);
    $formAction = clean($_POST['formAction']);

    $cancel = clean($_POST['cancel']);
    if ( $cancel != ""){
            if ( $section == "") {
                    $section = "proposal";
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
            $usrId = clean($_POST['userId']);
            $posted = clean($_POST['posted']);
            $companyId = clean($_POST['companyId']);
            if ( isset($companyId) && $companyId == "" )
                    $companyId = "0";
                        
                        //Input Validations
            if($docNumber == '') {
                    $errmsg_arr[] = 'Proposal Document Number missing';
                    $errflag = true;
                }
            if($title == '') {
                    $errmsg_arr[] = 'Proposal title missing';
                    $errflag = true;
                }
                //If there are input validations, redirect back to the registration form
                
                //Create INSERT query
            $cDate = date("Y-m-d H:i:s");
            $posted=$cDate;
            if ( !$errflag && $formAction != "view") {
                    $result = $proposalsDAO->saveUpdateProposalById ($id,$docNumber, $title,$customer, $value, $performancePeriod, $received_dt,
                        $questionDue_dt, $draftDue_dt, $finalDue_dt, $bid, $results, $notes, 
                        $tmName, $usrId, $posted, $companyId);
                        //Check whether the query was successful or not
                    if($result) {
                            mkDirectory($homeDirectory.$awardDir."/proposals/".$docNumber."/");
                            if ( $section == "") {
                                    $section = "proposal";
                                }
                            doUrl($hdrLocation, $paramString, $section, $sub_section);
                        } else {
                            $errmsg_arr[] = 'Unable to Save or Update User.';
                            $errflag = true;
                        }
                }
        } else {
            $result = $proposalsDAO->deleteProposalById ($id);
                //Check whether the query was successful or not
            if($result) {
                    rmDirectory($homeDirectory.$awardDir."/proposals/".$docNumber."/");
                
                    if ( $section == "") {
                            $section = "proposal";
                        }
                    doUrl($hdrLocation, $paramString, $section, $sub_section);
                } else {
                    $errmsg_arr[] = 'Unable to delete user.';
                    $errflag = true;
                }
        }
}   
//Create INSERT query
if ( $sub_section == "listProposal" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
            $result = $proposalsDAO->getAllProposals();
        } else {
            $result = $proposalsDAO->getProposalByOnlyTeamName($teamName);
        }
} else {
    $result = $proposalsDAO->getProposalById($id);
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