<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/NotificationDAO.php';

$notificationDAO = new NotificationDAO();

$result = $notificationDAO->getNotificationByTeamName($teamName);
if ( $webAdmin == "yes") {
    $result = $notificationDAO->getAllNotifications();
}
    
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
        //            header("location: userForm.php?id=".$id);
        //            exit();
}
?>
