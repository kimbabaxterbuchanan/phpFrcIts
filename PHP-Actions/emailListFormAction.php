<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/emailDAO.php';
$emailDAO = new emailDAO();

$result = $emailDAO->getEmailByTeamName($teamName);
if ( $webAdmin == "yes") {
    $result = $emailDAO->getAllEmails();
}
if(!$result) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}
?>
