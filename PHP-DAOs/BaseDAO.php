<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';

class BaseDAO
{
    function getRecord($table,$sort) {
        $qry = "select * from ".$table.$sort;
        $result = @mysql_query($qry);
        if ( ! $result ) {
            echo '<ul class="err">';
            echo  "GetRecord Query on ".$table." failed with Error: ".mysql_error();
            echo '</ul>';
        } else {
            if ( mysql_num_rows($result) > 1 ) {
                $obj = array();
                $cnt = 0;
                while ( $res = mysql_fetch_array($result)) {
                    $obj[$cnt] =  array2object($res);
                    $cnt += 1;
                }
            } else if ( mysql_num_rows($result) == 1 ) {
                $obj = new StdClass();
                $ary = mysql_fetch_assoc($result);
                $obj = array2object($ary);
            } else {
                $obj = false;
            }
            return $obj;
        }
        return $result;
    }

    function executeQry($qry) {
        global $table;

        $result = @mysql_query($qry);
        if ( ! $result ) {
            echo '<ul class="err">';
            echo  "ExecuteQry Query on ".$table." failed with Error: ".mysql_error();
            echo '</ul>';
        } else {
            if ( mysql_num_rows($result) > 1 ) {
                $obj = array();
                $cnt = 0;
                while ( $res = mysql_fetch_array($result)) {
                    $obj[$cnt] =  array2object($res);
                    $cnt += 1;
                }
            } else {
                $ary = mysql_fetch_assoc($result);
                $obj = array2object($ary);
            }
            return $obj;
        }
        return $result;
    }

    function executeBaseQry($qry) {
        $result = @mysql_query($qry);
        return $result;
    }

    function saveUpdate ($obj,$table) {
        $id = $obj->id;
        $t = true;
        $query = "";
        $qry = "";
        $where = "";
        $value = "";
        $method = "";
        $last_modified = date("Y-m-d H:i:s");
        if ( $obj->id == "" || $obj->id == "0") {
            $method="Insert";
            $query="INSERT INTO ".$table." ( ";
            $qry = "";
            $value = " , last_modified ) VALUES ( ";
            $where = ",'".$last_modified."' )";
        } else {
            $method="Update";
            $t = false;
            $query="update ".$table." set last_modified = '".$last_modified."', ";
            $where = " where id = '".$obj->id."'";
        }
        $comma = "";
        foreach($obj as $key => $val) {
            if ( $t && $key != "id" && $key != "create_date" && $key != "last_modified" ) {
                $query .= $comma.$key;
                $qry .= $comma."'".$val."'";
                $comma = ",";
            } else if ( !$t && $key != "id" && $key != "create_date" && $key != "last_modified" ) {
                $query .= $comma.$key." = '".$val."'";
                $comma = ",";
            }
        }
        $qury = $query.$value.$qry.$where;
        $stat = @mysql_query($qury);
        if ( ! $stat ) {
            echo '<ul class="err">';
            echo $qury."<BR>";
            echo  $method." failed with Error: ".mysql_error();
            echo '</ul>';
        }
        return $stat;
    }

    function delete ($id,$table) {
        $qry = "delete from ".$table." where id = '".$id."'";
        $stat = @mysql_query($qry);
        if ( ! $stat ) {
            echo '<ul class="err">';
            echo  "Delete on ".$table." failed with Error: ".mysql_error();
            echo '</ul>';
            $errmsg_arr[] = "Delete on ".$table." failed with Error: ".mysql_error();
        }
        return $stat;
    }
}
?>
