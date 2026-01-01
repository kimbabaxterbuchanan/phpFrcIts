<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/companyDAO.php';
require_once dirname(__FILE__) .'/userDAO.php';
require_once dirname(__FILE__) .'/proposalsDAO.php';
require_once dirname(__FILE__) .'/toAwardsDAO.php';
require_once dirname(__FILE__) .'/toRFQsDAO.php';
require_once dirname(__FILE__) .'/fileSystemDAO.php';
require_once dirname(__FILE__) .'/primeContactsDAO.php';

class awardedDAO {

    function awardedDAO() {
        }

    function getAllTeams() {
            $qry = "select distinct teamName from user order by teamName";
            return @mysql_query($qry);
        }
        
    function getAllAwards() {
            $qry = "select * from isawarded order by teamName";    
            return @mysql_query($qry);
        }
    function getAwardByID($id) {
            $qry = "select * from isawarded where id = '$id'";
            return @mysql_query($qry);
        }

    function getAwardByTeamName($tmName) {
            $qry = "select * from isawarded where teamName = '$tmName'";
            return @mysql_query($qry);
        }

    function saveUpdateAwardsById ($id,$awarded,$tmName) {
            if ( $id == "" ) {
                
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO isawarded(awarded, teamName, create_dt) VALUES
                        ('$awarded','$tmName','$create_dt')";
                    $result = @mysql_query($qry);
                    if ( $result ) {
                            $fileSystem = new fileSystemDAO();
                            $fileSystem->mkDirectoryByTeamName($tmName);
                        }
                    return $result;
                } else {
                    $qry = "update isawarded set awarded = '".$awarded."',
                        teamName = '".$tmName."' where id = '".$id."'";
                
                    return @mysql_query($qry);
                }
        }

    function deleteAwardsById ($id,$tmName) {
            $proposal = new proposalsDAO();
            $proposal->deleteProposalsByTeamName($tmName);
            $toAwards = new toAwardsDAO();
            $toAwards->deleteToAwardsByTeamName($tmName);
            $toRFQs = new toRFQsDAO();
            $toRFQs->deleteToRFQsByTeamName($tmName);
            $user = new userDAO();
            $user->deleteUserByTeamName($tmName);
            $primeContacts = new primeContactsDAO();
            $primeContacts->deletePrimeContactsByAllTeamName();
            $company = new companyDAO();
            $company->deleteCompanyByTeamName($tmName);
            $fileSystem = new fileSystemDAO();
            $fileSystem->delDirectoryByTeamName($tmName);
            $qry = "delete from isawarded where id = '".$id."'";
            return @mysql_query($qry);
        }
}
?>