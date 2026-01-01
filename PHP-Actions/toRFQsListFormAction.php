<?php
//Start session
session_start();

//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) . '/../PHP-DAOs/toRFQsDAO.php';

$toRFQsDAO = new toRFQsDAO();

$result = $toRFQsDAO->getToRFQByOnlyTeamName($teamName);
if ( $webAdmin == "yes") {
    $result = $toRFQsDAO->getAllToRFQs();
}

//Check whether the query was successful or not
if (!$result) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}
?>