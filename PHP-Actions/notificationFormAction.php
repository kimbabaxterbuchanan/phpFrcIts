<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/NotificationDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';

$awardedDAO = new awardedDAO();

$notificationDAO = new NotificationDAO();
$table = "notification";
foreach ( $_POST as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_GET as $key => $val ) {
    $$key = clean($val);
}
foreach ( $_REQUEST as $key => $val ) {
    $$key = clean($val);
}

if ( isset($postForm) && $postForm != "" ) {
//Sanitize the POST values
    if ( $cancel != ""){
        doUrl($hdrLocation, $paramString, $section, $sub_section);
        }
        
    switch ($formAction){
        case "delete":
            $stat = $notificationDAO->deleteNotificationsById ($id);
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
                }
            break;
        Default:
           $stat = $notificationDAO->saveUpdateNotificationById($id,$userId,$careerId,$notificationId,$status,$toMail,$replyMail,
                     $ccMail,$subject,$teamName,$create_dt);
            if ( $stat ) {
                doUrl($hdrLocation, $paramString, $section, $sub_section,$table);
                }
            break;
        }

} 
//Create INSERT query
if ( $sub_section == "listNotification" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
    $result = $notificationDAO->getAllNotifications();
        } else {
            $result = $notificationDAO->getToNotificationByTeamName($teamName);
        }
} else {
    $result = $notificationDAO->getNotificationByID($id);
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
