<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyProfileDAO.php';

$postForm = clean($_POST['postForm']);
$companyDAO = new companyDAO();
$companyProfileDAO = new companyProfileDAO();

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
    $companyId = clean($_POST['companyId']);
    $companyName = clean($_POST['companyName']);
    if ( $companyName == "" ) {
            $result = $companyDAO->getCompanyById($companyId);
            $companyName = "";
            if($result) {
                    $res = mysql_fetch_assoc($result);
                    $companyName=$res['name'];
                }
        }
    $street = clean($_POST['street']);
    $mailStop = clean($_POST['mailStop']);
    $city = clean($_POST['city']);
    $state = clean($_POST['state']);
    $zipCode = clean($_POST['zipCode']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone']);
    $fax = clean($_POST['fax']);
        
        //Input Validations
    if($street == '') {
            $errmsg_arr[] = 'Company Street missing';
            $errflag = true;
        }
        //        if($mailStop == '') {
        //                $errmsg_arr[] = 'Company MailStop missing';
        //                $errflag = true;
        //        }
    if($city == '') {
            $errmsg_arr[] = 'Company City missing';
            $errflag = true;
        }
    if($state == '') {
            $errmsg_arr[] = 'Company State missing';
            $errflag = true;
        }
    if($zipCode == '') {
            $errmsg_arr[] = 'Company ZipCode missing';
            $errflag = true;
        }
        //        if($email == '') {
        //                $errmsg_arr[] = 'Company Email missing';
        //                $errflag = true;
        //        }
    if($phone == '') {
            $errmsg_arr[] = 'Company Phone missing';
            $errflag = true;
        }
    if($fax == '') {
            $errmsg_arr[] = 'Company Fax missing';
            $errflag = true;
        }
        
        //Create INSERT query
    if ( !$errflag ) {
            $result = $companyProfileDAO->saveUpdateCompanyProfileById ($id,$companyId,$street,$mailStop,$city,$state,$zipCode,$email,$phone,$fax);
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
if ( $companyName == "" || $companyTeamName == "" ) {
    $result = $companyDAO->getCompanyById($companyId);
    $companyName = "";
    if($result) {
            $res = mysql_fetch_assoc($result);
            $companyName=$res['name'];
        }
}
$result = $companyProfileDAO->getCompanyProfileById($companyId);
$id = "";
$street = "";
$mailStop = "";
$city = "";
$state = "";
$zipCode = "";
$email = "";
$phone = "";
$fax = "" ;

if($result) {
    $res = mysql_fetch_assoc($result);
    $id = $res['id'];
    if ( $id != "") {
            $companyId = $res['companyId'];
        }
    $street = $res['street'];
    $mailStop = $res['mailStop'];
    $city = $res['city'];
    $state = $res['state'];
    $zipCode = $res['zipCode'];
    $email = $res['email'];
    $phone = $res['phone'];
    $fax = $res['fax'];
}
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
}

?>