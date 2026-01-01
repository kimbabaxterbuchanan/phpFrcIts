<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';

class reportDAO {

    function reportDAO() {
    }

    function getNextSeqNum() {
        $qry = "select seq_num from report order by seq_num DESC limit 1";
        $result = @mysql_query($qry);
        if ( $result ) {
            $res = mysql_fetch_assoc($result);
            if ( is_numeric($res['seq_num']))
            $seq_num = $res['seq_num']+1;
        }
        return $seq_num;
    }

    function getAllReports() {
        $qry = "select * from report order by teamName";
        return @mysql_query($qry);
    }
    function getReportByID($id) {
        $qry = "select * from report where id = '$id'";
        return @mysql_query($qry);
    }

    function getReportByTeamName($tmName) {
        $qry = "select * from report where teamName = '$tmName'";
        return @mysql_query($qry);
    }

    function saveUpdateReportById ($id,$reportname,$reportdescription,$reporttitle,$selected_tables,
        $reportsql,$displaytype,$tmName,$create_dt ) {

        if ( $id == "" ) {

            $create_dt = date("Y/m/d H:i:s");
            $qry = "INSERT INTO report(reportname,reportdescription,reporttitle,
                                    selected_tables,reportsql,displaytype,
                                    teamName, create_dt) VALUES
                                    ('$reportname','$reportdescription','$reporttitle',
                                    '$selected_tables','$reportsql','$displaytype',
                                    '$tmName','$create_dt')";
            $result = @mysql_query($qry);
            return $result;
        } else {
            $qry = "update report set reportname = '".$reportname."',
                                reportdescription = '".$reportdescription."',
                                reporttitle = '".$reporttitle."',
                                selected_tables = '".$selected_tables."',
                                reportsql = '".$reportsql."',
                                displaytype = '".$displaytype."',
                                teamName = '".$tmName."' where id = '".$id."'";
            $stat = @mysql_query($qry);
            if ( ! $stat ) {
                echo $qry."<BR>";
                echo '<ul class="err">';
                echo  "Save or Update on ".$table." failed with Error: ".mysql_error();
                echo '</ul>';
                $errmsg_arr[] = "Delete on ".$table." failed with Error: ".mysql_error();
            }
            return $stat;

        }
    }

    function deleteReportsById ($id) {
        $qry = "delete from report where id = '".$id."'";
        return @mysql_query($qry);
    }
}?>