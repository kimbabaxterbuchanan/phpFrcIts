<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class toawardsDAO {

    function toawardsDAO(){
        
        }

    function getAllToAwards() {
            $qry = "select * from toawards order by teamName, docNumber, posted";    
            return @mysql_query($qry);
        }
    function getToAwardById($id) {
            $qry = "select * from toawards where id = '$id'";
            return @mysql_query($qry);
        }

    function getToAwardByOnlyTeamName($tmName) {
            $qry = "select * from toawards where teamName = '$tmName' order by docNumber";
            return @mysql_query($qry);
        }

    function saveUpdateToAwardById ($id,$docNumber, $title,$customer, $value, $performancePeriod, $received_dt,
                        $questionDue_dt, $draftDue_dt, $finalDue_dt, $bid, $results, $notes, 
                        $tmName, $usrId, $posted, $companyId) {
            if ( $id == "" ) {
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO toawards (docNumber, title, customer, value, performancePeriod, 
                        received_dt, questionDue_dt, draftDue_dt, finalDue_dt, bid, results, notes, 
                        teamName, userId, posted, create_dt, companyId) VALUES
                        ('$docNumber', '$title','$customer', '$value', '$performancePeriod', '$received_dt',
                        '$questionDue_dt', '$draftDue_dt', '$finalDue_dt', '$bid', '$results', '$notes', 
                        '$tmName', '$usrId', '$posted', '$create_dt','$companyId')";
                } else {
                    $qry = "update toawards set docNumber = '".$docNumber.
                        "', title = '".$title."', customer = '".$customer."', value = '".$value.
                        "', performancePeriod = '".$performancePeriod."', received_dt = '".$received_dt."', questionDue_dt = '".$questionDue_dt.
                        "', draftDue_dt = '".$draftDue_dt."', finalDue_dt = '".$finalDue_dt."', bid = '".$bid.
                        "', results = '".$results."', notes = '".$notes.
                        "', teamName = '".$tmName."', userId = '".$usrId."', posted = '".$posted."', companyId = '".$companyId."' where id = '".$id."'";
                }
            return @mysql_query($qry);
        }

    function deleteToAwardById ($id) {
            $qry = "delete from  toawards where id = '".$id."'";
             return @mysql_query($qry);
        }

    function deleteToAwardsByCompanyId ($id) {
            $qry = "delete from  toawards where compandId = '".$id."'";
             return @mysql_query($qry);
        }
        
    function deleteToAwardsByTeamName ($tmName) {
            $qry = "delete from toawards where teamName = '".$tmName."'";
             return @mysql_query($qry);
        }
}
?>