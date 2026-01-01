<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userProfileDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/primeContactsDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';

$postForm = clean($_POST['postForm']);
$userProfileDAO = new userProfileDAO();
$userDAO = new userDAO();
$primeContactsDAO = new primeContactsDAO();
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
    $userId = clean($_POST['userId']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone']);
    $pocType = clean($_POST['pocType']);
    $companyId = clean($_POST['companyId']);
    $primeContact = clean($_POST['primeContact']);
    $title = clean($_POST['title']);
    $sortinfo = clean($_POST['sortinfo']);
        
        //Input Validations
    if($email == '') {
            $errmsg_arr[] = 'Company Email missing';
            $errflag = true;
        }
    if($phone == '') {
            $errmsg_arr[] = 'Company Phone missing';
            $errflag = true;
        }
        
        //If there are input validations, redirect back to the registration form
        
        //Create INSERT query
    if ( !$errflag ) {
            $result = $userProfileDAO->saveUpdateUserProfileById($id,$userId, $phone, $email, $pocType, $companyId, $primeContact);
            $presult = $primeContactsDAO->getPrimeContactsByUserID($userId);
            $pid = "";
            if ( $presult ) {
                    $pres = mysql_fetch_assoc($presult);
                    $pid = $pres['userId'];
                }
        
            if ( $primeContact == "yes" ) {
                        $presult = $primeContactsDAO->saveUpdatePrimeContactsById ($pid,$title,$sortinfo,$companyId,$userId);
                } else {
                    if ( $pid != "" ) {
                        $presult = $primeContactsDAO->deletePrimeContactsByUserId($pid);                  
                    }
                }
                
                //Check whether the query was successful or not
            if($result) {
                    if ( $teamManager != "yes" ) {
                            $section="homePage";
                            $sub_section="";
                        }
                    doUrl($hdrLocation, $paramString, $section, $sub_section);
                } else {
                    $errmsg_arr[] = 'Unable to Save or Update User.';
                    $errflag = true;
                }
        }
}

$result = $userDAO->getUserById($id);
$res = mysql_fetch_assoc($result);
$name = $res['firstname']." ".$res['lastname'];

$result = $userProfileDAO->getUserProfileByUserId($id);
$userId = $id;
$id = 0;
$email = "";
$phone = "";
$pocType = "";
$companyId = "";
$companyName = "";
//Check whether the query was successful or not
if ( $result ) {
    $res = mysql_fetch_assoc($result);
    $id = $res['id'];
    if ( $id != "") {
            $userId = $res['userId'];
        }
    $email = $res['email'];
    $phone = $res['phone'];
    $pocType = $res['pocType'];
    $companyId = $res['companyId'];
    $primeContact = $res['primecontact'];
    $result = $companyDAO->getCompanyById($id);
    $res = mysql_fetch_assoc($result);
    $companyName = $res['name'];
}
if ( $webAdmin == "yes") {
    $companyresult = $companyDAO->getAllCompanies();
} else {
    $companyresult = $companyDAO->getCompanyByTeamName($teamName);
}

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