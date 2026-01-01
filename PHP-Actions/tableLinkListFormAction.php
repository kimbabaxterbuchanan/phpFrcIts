<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/TableLinkDAO.php';

$tableLinkDAO = new TableLinkDAO();

$result = $tableLinkDAO->getAllTableLinks();
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    //            header("location: userForm.php?id=".$id);
    //            exit();
}
?>
