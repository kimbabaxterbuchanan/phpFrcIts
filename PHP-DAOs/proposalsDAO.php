<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class proposalsDAO {

    function proposalsDAO(){
        
        }

    function getAllProposals() {
            $qry = "select * from proposals order by teamName, docNumber, posted";    
            return @mysql_query($qry);
        }
    function getProposalById($id) {
            $qry = "select * from proposals where id = '$id'";
            return @mysql_query($qry);
        }

    function getProposalByOnlyTeamName($tmName) {
            $qry = "select * from proposals where teamName = '$tmName' order by docNumber";
            return @mysql_query($qry);
        }

    function saveUpdateProposalById ($id,$docNumber, $title,$customer, $value, $performancePeriod, $received_dt,
                        $questionDue_dt, $draftDue_dt, $finalDue_dt, $bid, $results, $notes, 
                        $tmName, $usrId, $posted, $companyId) {
            if ( $id == "" ) {
                    $create_dt = date("Y/m/d H:i:s");
                    $qry = "INSERT INTO proposals (docNumber, title, customer, value, performancePeriod, 
                        received_dt, questionDue_dt, draftDue_dt, finalDue_dt, bid, results, notes, 
                        teamName, userId, posted, create_dt, companyId) VALUES
                        ('$docNumber', '$title','$customer', '$value', '$performancePeriod', '$received_dt',
                        '$questionDue_dt', '$draftDue_dt', '$finalDue_dt', '$bid', '$results', '$notes', 
                        '$tmName', '$usrId', '$posted', '$create_dt','$companyId')";
                } else {
                    $qry = "update proposals set docNumber = '".$docNumber.
                        "', title = '".$title."', customer = '".$customer."', value = '".$value.
                        "', performancePeriod = '".$performancePeriod."', received_dt = '".$received_dt."', questionDue_dt = '".$questionDue_dt.
                        "', draftDue_dt = '".$draftDue_dt."', finalDue_dt = '".$finalDue_dt."', bid = '".$bid.
                        "', results = '".$results."', notes = '".$notes.
                        "', teamName = '".$tmName."', userId = '".$usrId."', posted = '".$posted."', companyId = '".$companyId."' where id = '".$id."'";
                }
            return @mysql_query($qry);
        }

    function deleteProposalById ($id) {
            $qry = "delete from  proposals where id = '".$id."'";
             return @mysql_query($qry);
        }

    function deleteProposalsByCompanyId ($id) {
            $qry = "delete from  proposals where compandId = '".$id."'";
             return @mysql_query($qry);
        }
        
    function deleteProposalsByTeamName ($tmName) {
            $qry = "delete from  proposals where teamName = '".$tmName."'";
             return @mysql_query($qry);
        }
}
?>