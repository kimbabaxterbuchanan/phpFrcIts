<?php
//Start session
session_start();

//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/companyDAO.php';

$companyDAO = new companyDAO();

//Create INSERT query
if ( $webAdmin == "yes" ) {
    $companyResult = $companyDAO->getCompanyByTeamNameNotAll();
} else {
    $temName = $teamName;
    if ( $teamName == "" ) {
      $url_info = parse_url($_SERVER["HTTP_REFERER"]);
      $url = $url_info['host'];
      $urlAry = explode(".",$url);
      $url = $urlAry[1];
      if (strpos($url,"frc") == 0 ) {
            $temName = substr($url,4);
      } else {
            $temName = substr($url,0,strlen($url)-4);
      }
    }
    $companyResult = $companyDAO->getCompanyByOnlyTeamName($temName);
}
$parentResult = $companyDAO->getCompanyByTeamNameAll();

//Check whether the query was successful or not

if (!$companyResult) {
    require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/SQLErrorInclude.php';
}

?>