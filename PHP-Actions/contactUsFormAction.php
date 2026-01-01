<?php
//Start session
session_start();

//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/userProfileDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/primeContactsDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyProfileDAO.php';

//Create INSERT query
$userProfileDAO = new userProfileDAO();
$userDAO = new userDAO();
$primeContactsDAO = new primeContactsDAO();
$companyDAO = new companyDAO();
$companyProfileDAO = new companyProfileDAO();

$parentResult = $companyDAO->getCompanyByTeamNameAll();

//Check whether the query was successful or not

if(!$parentResult) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}
?>