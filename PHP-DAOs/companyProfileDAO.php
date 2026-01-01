<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class companyProfileDao {

    function companyProfileDAO() {
        
        }

    function getCompanyProfileById($id) {
            $qry = "SELECT * FROM companyprofile WHERE companyId='$id'";
           return @mysql_query($qry);
        }

    function saveUpdateCompanyProfileById ($id,$companyId,$street,$mailStop,$city,$state,$zipCode,$email,$phone,$fax) {
            if ( $id == "" ) {
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO companyprofile(companyId, street, mailStop, city, state, zipCode, email, phone, fax, create_dt) VALUES
                        ('$companyId','$street','$mailStop','$city','$state', '$zipCode', '$email', '$phone', '$fax', '$create_dt')";
                } else {
                    $qry = "update companyprofile set companyId = '".$companyId."', street='".$street."',
                        mailStop='".$mailStop."', city='".$city."', state='".$state."',
                        zipCode='".$zipCode."', email='".$email."', phone='".$phone."',
                        fax='".$fax."'
                        where id = '".$id."'";
                
                }
            return @mysql_query($qry);
        }

    function deleteCompanyProfileByCompanyId ($id) {
            $qry = "delete from companyprofile where companyId = '".$id."'";
            return @mysql_query($qry);
        }

    function deleteCompanyProfileById ($id) {
            $qry = "delete from companyprofile where id = '".$id."'";
            return @mysql_query($qry);
        }
        
    function deleteCompanyProfileByTeamName ($tmName) {
            $qry = "delete from companyprofile where teamName = '".$tmName."'";
            return @mysql_query($qry);
        }
}
?>