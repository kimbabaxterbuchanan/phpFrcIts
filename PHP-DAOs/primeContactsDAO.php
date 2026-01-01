<?php
//Start session
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class primeContactsDAO {

    function primeContactsDAO () {
        
        }

    function getAllPrimeContacts() {
            $qry = "select * from primecontacts order by teamName";    
            return @mysql_query($qry);
        }
        
    function getPrimeContactsByUserID($id) {
            $qry = "select * from primecontacts where userId = '$id'";
            return @mysql_query($qry);
        }

    function getPrimeContactsByCompanyID($id) {
            $qry = "select * from primecontacts where companyId = '$id'";
            return @mysql_query($qry);
        }

    function saveUpdatePrimeContactsById ($id,$title,$sortinfo,$companyId,$userId) {
            if ( $id == "" ) {
                    $qry = "insert into primecontacts(title,poctype,sortinfo,companyId,userId) Values
                        ('$title','All','$sortinfo','$companyId','$userId')";
                } else {
                    $qry = "update primecontacts set title = '".$title."',poctype = 'All',sortinfo = '".$sortinfo."',companyId = '".$companyId."'
                        where userId = '".$userId."'";                    
                }
            return @mysql_query($qry);
        }

    function deletePrimeContactsByUserId ($id) {
            $qry = "delete from primecontacts where userId = '".$id."'";
            return @mysql_query($qry);
        }

    function deletePrimeContactsByCompanyId ($id) {
            $qry = "delete from primecontacts where companyId = '".$id."'";
            return @mysql_query($qry);
        }

    function deletePrimeContactsByAllTeamName () {
            $qry = "delete from primecontacts where teamName = 'All'";
            return @mysql_query($qry);
        }

}
?>