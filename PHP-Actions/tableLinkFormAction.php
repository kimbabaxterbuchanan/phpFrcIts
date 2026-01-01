<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/TableLinkDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';

$awardedDAO = new awardedDAO();

$tableLinkDAO = new TableLinkDAO();

foreach ( $_POST as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_GET as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_REQUEST as $key => $val ) {
    $$key = clean($val);
}

$table = "tableLink";
$dataCheck = array();
$optType = "";
$optVar = "";
$selSelect = "";
$selFrom = "";
$selWhere = "";
$selOrder = "";
if ( isset($postForm) && $postForm != "" ) {
    //Sanitize the POST values
    if ( $cancel != ""){
        doUrl($hdrLocation, $paramString, $section, $sub_section);
        }
    switch ($formAction){
        case "delete":
            $stat = $tableLinkDAO->deleteTableLinksById ($id);
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
            }
            break;
        Default:
            $stat = $tableLinkDAO->saveUpdateTableLinkById ($id,$primarytable,$primaryfield,$linktable,$linkfield );
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
            }
    }

}
//Create INSERT query
if ( $sub_section == "listTableLink" && ! isset($formAction) ) {
    echo "here<br>";
    if ( $webAdmin == "yes") {
    $result = $tableLinkDAO->getAllTableLinks();
        } else {
            $result = $tableLinkDAO->getToTableLinkByTeamName($teamName);
        }
} else {
    $result = $tableLinkDAO->getTableLinkByID($id);
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
?>