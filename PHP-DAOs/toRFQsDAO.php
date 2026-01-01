<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class torfqsDAO {

    function torfqsDAO(){
        
        }

    function getAllToRFQs() {
            $qry = "select * from torfqs order by teamName, docNumber, posted";    
            return @mysql_query($qry);
        }
    function getToRFQById($id) {
            $qry = "select * from torfqs where id = '$id'";
            return @mysql_query($qry);
        }

    function getToRFQByOnlyTeamName($tmName) {
            $qry = "select * from torfqs where teamName = '$tmName' order by docNumber";
            return @mysql_query($qry);
        }

    function saveUpdateToRFQById ($id,$docNumber, $title,$customer, $value, $performancePeriod, $received_dt,
        $questionDue_dt, $draftDue_dt, $finalDue_dt, $bid, $results, $notes, 
        $tmName, $usrId, $posted, $companyId) {
            if ( $id == "" ) {
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO torfqs (docNumber, title, customer, value, performancePeriod, 
                        received_dt, questionDue_dt, draftDue_dt, finalDue_dt, bid, results, notes, 
                        teamName, userId, posted, create_dt, companyId) VALUES
                        ('$docNumber', '$title','$customer', '$value', '$performancePeriod', '$received_dt',
                        '$questionDue_dt', '$draftDue_dt', '$finalDue_dt', '$bid', '$results', '$notes', 
                        '$tmName', '$usrId', '$posted', '$create_dt','$companyId')";
                } else {
                    $qry = "update torfqs set docNumber = '".$docNumber.
                        "', title = '".$title."', customer = '".$customer."', value = '".$value.
                        "', performancePeriod = '".$performancePeriod."', received_dt = '".$received_dt."', questionDue_dt = '".$questionDue_dt.
                        "', draftDue_dt = '".$draftDue_dt."', finalDue_dt = '".$finalDue_dt."', bid = '".$bid.
                        "', results = '".$results."', notes = '".$notes.
                        "', teamName = '".$tmName."', userId = '".$usrId."', posted = '".$posted."', companyId = '".$companyId."' where id = '".$id."'";
                }
            return @mysql_query($qry);
        }

    function deleteToRFQById ($id) {
            $qry = "delete from  torfqs where id = '".$id."'";
            return @mysql_query($qry);
        }

    function deleteToRFQsByCompanyId ($id) {
            $qry = "delete from  torfqs where compandId = '".$id."'";
             return @mysql_query($qry);
        }
        
    function deleteToRFQsByTeamName ($tmName) {
            $qry = "delete from  torfqs where teamName = '".$tmName."'";
             return @mysql_query($qry);
        }
}
?>