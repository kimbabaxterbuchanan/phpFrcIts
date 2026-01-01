<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) . '/../PHP-DAOs/proposalsDAO.php';

$proposalsDAO = new proposalsDAO();

$result = $proposalsDAO->getProposalByOnlyTeamName($teamName);
if ( $webAdmin == "yes") {
    $result = $proposalsDAO->getAllProposals();
}
//Check whether the query was successful or not
if (!$result) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}

?>