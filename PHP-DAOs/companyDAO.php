<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/companyProfileDAO.php';
require_once dirname(__FILE__) .'/userDAO.php';
require_once dirname(__FILE__) .'/proposalsDAO.php';
require_once dirname(__FILE__) .'/toAwardsDAO.php';
require_once dirname(__FILE__) .'/toRFQsDAO.php';
require_once dirname(__FILE__) .'/fileSystemDAO.php';

class companyDAO {

    function companyDAO() {
        
        }

    function getAllCompanies() {
            $qry = "select * from companies order by teamName, displayname";
            return @mysql_query($qry);
        }
    function getCompanyById($id) {
            $qry = "select * from companies where id = '$id'";
            return @mysql_query($qry);
        }

    function getCompanyByOnlyTeamName($tmName) {
            $qry = "select * from companies where teamName = '$tmName' order by displayname";
            return @mysql_query($qry);
        }

    function getCompanyByTeamName($tmName) {
            $qry = "select * from companies where teamName = 'All' or teamName = '$tmName' order by displayname";
            return @mysql_query($qry);
        }

    function getCompanyByTeamNameAll() {
            $qry = "select * from companies where teamName = 'All' order by displayname";
            return @mysql_query($qry);
        }

    function getCompanyByTeamNameNotAll() {
            $qry = "select * from companies where teamName != 'All' order by displayname";
            return @mysql_query($qry);
        }

    function getCompanyByNumberTeamName($companyNum, $tmName) {
            $qry = "select * from companies where companyNum = '$companyNum' and teamName = '$tmName' order by displayname";
            return @mysql_query($qry);
        }

    function saveUpdateCompanyById ($id,$companyNum,$name,$logo,$displayname,$website,$tmName) {
            if ( $id == "" ) {
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO companies(companyNum,name,logo,displayname,website,teamName, create_dt) VALUES ('$companyNum','$name','$logo','$displayname','$website','$tmName','$create_dt')";
                    $result = @mysql_query($qry);
                    if ( $result && $tmName != "All" ) {
                            $fileSystem = new fileSystemDAO();
                            $fileSystem->mkDirectoryByCompanyName($tmName, $name);
                        }
                    return $result;
                } else {
                    $qry = "update companies set companyNum = '".$companyNum."', name = '".$name."', logo = '".$logo."', displayname = '".$displayname."', website = '".$website."', teamName = '".$tmName."' where id = '".$id."'";
                
                    return @mysql_query($qry);
                }
        }

    function deleteCompanyById ($id,$tmName, $name) {
            $proposal = new proposalsDAO();
            $proposal->deleteProposalsByCompanyId($id);
            $toAwards = new toAwardsDAO();
            $toAwards->deleteToAwardsByCompanyId($id);
            $toRFQs = new toRFQsDAO();
            $toRFQs->deleteToRFQsByCompanyId($id);
            $user = new userDAO();
            $user->deleteUserByCompanyId($id);
            if ( $tmName != "All" ) {
                    $fileSystem = new fileSystemDAO();
                    $fileSystem->delDirectoryByCompanyName($tmName, $name);
                }
            $companyProfileDAO = new companyProfileDAO();
            $companyProfileDAO->deleteCompanyProfileByCompanyId($id);
            $qry = "delete from companies where id = '".$id."'";
            return @mysql_query($qry);
        }

    function deleteCompanyByTeamName ($tmName) {
            $proposal = new proposalsDAO();
            $proposal->deleteProposalsByTeamName($tmName);
            $toAwards = new toAwardsDAO();
            $toAwards->deleteToAwardsByTeamName($tmName);
            $toRFQs = new toRFQsDAO();
            $toRFQs->deleteToRFQsByTeamName($tmName);
            $user = new userDAO();
            $user->deleteUserByTeamName($tmName);
            $companyProfileDAO = new companyProfileDAO();
            $companyProfileDAO->deleteCompanyProfileByTeamName($tmName);
            $qry = "delete from companies where teamName = '".$tmName."'";
            return @mysql_query($qry);
        }

}

?>