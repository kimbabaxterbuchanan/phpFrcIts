<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';

$awardedDAO = new awardedDAO();

$postForm = clean($_POST['postForm']);

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
    $tmName = clean($_POST['tmName']);
       
    if ( $formAction != "delete") {
            $awarded = clean($_POST['awarded']);
                //Input Validations
            if($tmName == '') {
                    $errmsg_arr[] = 'Team name missing';
                    $errflag = true;
                }
            if ( !$errflag ) {
                    $result = $awardedDAO->saveUpdateAwardsById($id,$awarded,$tmName);
                    if($result) {
                        doUrl($hdrLocation, $paramString, $section, $sub_section);
                        }
                }
        } else {
            $result = $awardedDAO->deleteAwardsById($id,$tmName);
            if($result) {
                    doUrl($hdrLocation, $paramString, $section, $sub_section);
                } else {
                    $errmsg_arr[] = 'Unable to delete user\'s profile.';
                    $errflag = true;
                }
        }

}
//Create INSERT query
if ( $sub_section == "listAwards" && ! isset($formAction) ) {
    $result = $awardedDAO->getAllAwards();
} else {
    $result = $awardedDAO->getAwardByID($id);
}

//Check whether the query was successful or not
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    $_SESSION['ERRFLAG'] = "set";
    session_write_close();
}

if(!$result) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}

?>