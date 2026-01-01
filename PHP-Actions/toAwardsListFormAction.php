<?php
//Start session
session_start();

//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) . '/../PHP-DAOs/toAwardsDAO.php';

$toAwardsDAO = new toAwardsDAO();

$result = $toAwardsDAO->getToAwardByOnlyTeamName($teamName);
if ( $webAdmin == "yes") {
    $result = $toAwardsDAO->getAllToAwards();
}
//Check whether the query was successful or not
if (!$result) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}

?>