<?php
//Start session
session_start();

//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userProfileDAO.php';

$companyDAO = new companyDAO();
$userDAO = new userDAO();
$userProfileDAO = new userProfileDAO();

//Create INSERT query
if ( $webAdmin == "yes" ) {
    $companyResult = $companyDAO->getAllCompanies();
} else {
    $companyResult = $companyDAO->getCompanyByTeamName($teamName);
}

//Check whether the query was successful or not

if (!$companyResult) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}
?>