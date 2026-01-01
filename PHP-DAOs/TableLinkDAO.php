<?php
require_once dirname(__FILE__) .'/../PHP-DAOs/BaseDAO.php';


class TableLinkDAO  extends BaseDAO
{
    function TableLinkDAO() {
    }

    function getAllTableLinks() {
        $qry = "select * from tablelink order by primarytable";
        return @mysql_query($qry);
    }
    function getTableLinkByID($id) {
        $qry = "select * from tablelink where id = '$id'";
        return @mysql_query($qry);
    }

    function saveUpdateTableLinkById ($id,$primarytable, $primaryfield, $linktable, $linkfield ) {
        if ( $id == "" ) {

            $create_dt = date("Y/m/d H:i:s");
            $qry = "INSERT INTO tablelink(primarytable, primaryfield, linktable, linkfield, create_dt) VALUES
                                    ('$primarytable', '$primaryfield', '$linktable', '$linkfield','$create_dt')";
        } else {
            $qry = "update tablelink set  primarytable = '".$primarytable."',
                                primaryfield = '".$primaryfield."',
                                linktable = '".$linktable."',
                                linkfield =  '".$linkfield."' where id = '".$id."'";
        }
        $stat = @mysql_query($qry);
        if ( ! $stat ) {
            echo $qry."<BR>";
            echo '<ul class="err">';
            echo  "Save or Update on ".$table." failed with Error: ".mysql_error();
            echo '</ul>';
            $errmsg_arr[] = "Delete on ".$table." failed with Error: ".mysql_error();
            exit();
        }
        return $stat;

    }

    function deleteTableLinksById ($id) {
        $qry = "delete from tablelink where id = '".$id."'";
        return @mysql_query($qry);
    }
}
?>
