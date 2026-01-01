<?php
//Start session
session_start();

require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/globalVars.php';

$section = $_GET['section'];

if ( $section != "" ) {
    $_SESSION['SECTION'] = $section;
    } else if ( isset($_SESSION['SECTION']) && $_SESSION['SECTION'] != "" ) {
        $section = $_SESSION['SECTION'];
    }

$sub_section = $_GET['sub_section'];

if(isset($_SESSION['SESS_FIRST_NAME']) && (trim($_SESSION['SESS_FIRST_NAME']) != '')) {
    $teamMemberName=$_SESSION['SESS_FIRST_NAME']." ";
}

if(isset($_SESSION['SESS_LAST_NAME']) && (trim($_SESSION['SESS_LAST_NAME']) != '')) {
    $teamMemberName.=$_SESSION['SESS_LAST_NAME'];
}

if(isset($_SESSION['COMPANYID']) && (trim($_SESSION['COMPANYID']) != '')) {
    $userCompanyId=$_SESSION['COMPANYID'];
}

if(isset($_SESSION['COMPANYDIR']) && (trim($_SESSION['COMPANYDIR']) != '')) {
    $companyDir=$_SESSION['COMPANYDIR'];
}

if(isset($_SESSION['LOGIN']) && (trim($_SESSION['LOGIN']) != '')) {
    $login=$_SESSION['LOGIN'];
}

if(isset($_SESSION['CURDIR']) && (trim($_SESSION['CURDIR']) != '')) {
    $curDir=$_SESSION['CURDIR'];
} else {
    if (isset($curDir) && $curDir != "") {
            $_SESSION['CURDIR'] = $curDir;
        }
}

if(isset($_SESSION['TEAMNAME']) && (trim($_SESSION['TEAMNAME']) != '') && (trim($_SESSION['TEAMNAME']) != 'web')) {
    $teamName=$_SESSION['TEAMNAME'];
    } else if ( isset($teamName) && $teamName != "" && (!isset($_SESSION['TEAMNAME']) || (trim($_SESSION['TEAMNAME']) == '') || (trim($_SESSION['TEAMNAME']) == 'web'))) {
            $_SESSION['TEAMNAME']=$teamName;
        }

if(isset($_SESSION['ISAWARDED']) && (trim($_SESSION['ISAWARDED']) != 'no')) {
    $isAwarded=$_SESSION['ISAWARDED'];
    $awardWorkDir = $teamDir;
}

if (trim($teamName) != 'web') {
    $awardDir .= "/".$teamName;
    $libDir .= "/".$teamName;
}

if(isset($_SESSION['TEAMMANAGER']) && (trim($_SESSION['TEAMMANAGER']) == 'yes')) {
    $teamManager=$_SESSION['TEAMMANAGER'];
}

if(isset($_SESSION['WEBADMIN']) && (trim($_SESSION['WEBADMIN']) == 'yes')) {
    $webAdmin=$_SESSION['WEBADMIN'];
}

//Check whether the session variable SESS_MEMBER_ID is present or not
if(isset($section) && (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) ) {
    if ( isset($section) && $section == "" ) {
            header("location: http://www.frc_its_sb.com");
            exit();
        }
} else {
    if(isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) != '')) {
            $isLogedIn = "yes";
            $loginId = $_SESSION['SESS_MEMBER_ID'];
            $userId = $loginId;
        }
}

require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/functions.php';
$notifyTeamName = $teamName;

?>