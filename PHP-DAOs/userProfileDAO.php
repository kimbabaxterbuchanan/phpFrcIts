<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/primeContactsDAO.php';

class userProfileDAO {

    function userProfileDAO() {
        
        }

    function getUserProfileById($id) {
            $qry = "SELECT * FROM userprofile WHERE id='$id'";
            return @mysql_query($qry);
        }

    function getUserProfileByUserId($id) {
            $qry = "SELECT * FROM userprofile WHERE userId='$id'";
            return @mysql_query($qry);
        }

    function getUserProfileByEmail($email) {
            $qry = "SELECT * FROM userprofile WHERE email='$email'";
            return @mysql_query($qry);
        }

    function getUserProfileByCompanyId($id) {
            $qry = "SELECT * FROM userprofile WHERE companyId='$id'";
            return @mysql_query($qry);
        }
        
    function getUserProfileByCompanyIdNotPocType($id) {
                $qry = "select * from userprofile where companyId = '$id' and pocType != '99' order by pocType";
            return @mysql_query($qry);
        }
        
    function saveUpdateUserProfileById ($id,$userId, $phone, $email, $pocType, $companyId, $primeContact) {
            if ( $id == "" ) {
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO userprofile(userId, phone, email, POCType, companyId, create_dt, primecontact) VALUES
                        ('$userId', '$phone', '$email', '$pocType', '$companyId', '$create_dt', '$primeContact')";                        
                } else {
                //Create INSERT query
                    $qry = "update userprofile set userId = '".$userId."', phone='".$phone."', email='".$email."',
                        POCType='".$pocType."', companyId='".$companyId."', primecontact='".$primeContact."'
                        where id = '".$id."'";
                }
            return @mysql_query($qry);
        }

    function deleteUserProfileByCompanyId ($id) {
            $primeContactsDAO = new primeContactsDAO();
            $primeContactsDAO->deletePrimeContactsByCompanyId($id);
            $qry = "delete from userprofile where companyId = '".$id."'";
            return @mysql_query($qry);
        }

    function deleteUserProfileByUserId ($id) {
            $primeContactsDAO = new primeContactsDAO();
            $primeContactsDAO->deletePrimeContactsByUserId($id);
            $qry = "delete from userprofile where userId = '".$id."'";
            return @mysql_query($qry);
        }

    function deleteUserProfileById ($id) {
            $qry = "delete from userprofile where id = '".$id."'";
            return @mysql_query($qry);
        }

    function deleteUserProfileByTeamName ($tmName) {
            $qry = "delete from userprofile where teamName = '".$tmName."'";
            return @mysql_query($qry);
        }

}
?>