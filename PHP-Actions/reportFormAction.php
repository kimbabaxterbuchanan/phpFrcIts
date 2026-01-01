<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/ReportDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';

$awardedDAO = new awardedDAO();

$reportDAO = new ReportDAO();

foreach ( $_POST as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_GET as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_REQUEST as $key => $val ) {
    $$key = clean($val);
}

$table = "report";
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
            $stat = $reportDAO->deleteReportsById ($id);
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
            }
            break;
        Default:
            $stat = $reportDAO->saveUpdateReportById ($id,$reportname,$reportdescription,$reporttitle,$selected_tables,
                    urlencode($reportsql),$displaytype,$tmName,$create_dt );
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
            }
    }

}
//Create INSERT query
if ( $sub_section == "listReport" && ! isset($formAction) ) {
    echo "here<br>";
    if ( $webAdmin == "yes") {
    $result = $reportDAO->getAllReports();
        } else {
            $result = $reportDAO->getToReportByTeamName($teamName);
        }
} else {
    $result = $reportDAO->getReportByID($id);
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