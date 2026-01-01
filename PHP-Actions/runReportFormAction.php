<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) . '/../../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) . '/../../PHP-DAOs/ReportDAO.php';
require_once dirname(__FILE__) . '/../../PHP-models/ReportModel.php';
require_once dirname(__FILE__) . '/../../PHP-DAOs/TableLinkDAO.php';
require_once dirname(__FILE__) . '/../../PHP-models/TableLinkModel.php';
require_once  dirname(__FILE__) . '/../../PHP-DAOs/DbDAO.php';

$tableLinkModel = new TableLinkModel;
$tableLinkDAO = new TableLinkDAO();
$reportModel = new ReportModel;
$reportDAO = new ReportDAO();
$dbDAO = new DbDAO();

$table = "report";
$dataCheck = array();

$sort = " where id = '".$id."'";
$reportModel =$reportDAO->getRecord($table,$sort);

$selected_tables = split(",",$reportModel->selected_tables);
$selected_fields = split(",",$reportModel->selected_fields);

$sqlLookup = "SELECT ";
$sqlTable = " FROM ";
$sqlWhere = " WHERE ";
$comma = "";
$cnt = 0;
for ( $i = 0; $i < count($selected_tables)-1; $i+=1 ) {
    $sqlTable .= $comma.$selected_tables[$i]." as ".$selected_tables[$i];
    $comma = ", ";
}
$comma = "";
for ( $i = 0; $i < count($selected_fields)-1; $i+=1 ) {
    $valAry = split("=",$selected_fields[$i]);
    if ( strpos(".",$valAry[0]) ) {
        $sqlLookup .= $comma.$valAry[0];
        $comma = ",";
    }
}
$fndTables = "";
$conj = "";
$fndTables = "XxX";
for ( $i = 0; $i < count($selected_tables)-1; $i+=1 ) {
    $fndTables .= $selected_tables[$i]."XxX";
    for ( $j = 0; $j < count($selected_tables)-1; $j+=1 ) {
        if ( strpos($fndTables,"XxX".$selected_tables[$j]."XxX") === false ) {
            $sort = " where primarytable = '".$selected_tables[$i]."' and linktable = '".$selected_tables[$j]."'";
            $tableLinkModel = $tableLinkDAO->getRecord('tablelink',$sort);
            $fndTables .= $selected_tables[$j]."XxX";
            $sqlWhere .= $conj.$tableLinkModel->primarytable.".".$tableLinkModel->primaryfield;
            $sqlWhere .= " = ".$tableLinkModel->linktable.".".$tableLinkModel->linkfield;
            $conj = " and ";
        }
    }
}
for ( $i = 0; $i < count($selected_fields)-1; $i+=1 ) {
    $valAry = split("=",$selected_fields[$i]);
    if ( strpos($selected_fields[$i],"!") ) {
        $valAry[0] = str_replace("!",".",$valAry[0]);
        switch ($valAry[1]){
            case "notequal":
                $sqlWhere .= $conj.$valAry[0]. " != '".$valAry[2]."' ";
                break;
            case "lessthan":
                $sqlWhere .= $conj.$valAry[0]. " < '".$valAry[2]."' ";
                break;
            case "lessthanequal":
                $sqlWhere .= $conj.$valAry[0]. " <= '".$valAry[2]."' ";
                break;
            case "greaterthan":
                $sqlWhere .= $conj.$valAry[0]. " > '".$valAry[2]."' ";
                break;
            case "greaterthanequal":
                $sqlWhere .= $conj.$valAry[0]. " >= '".$valAry[2]."' ";
                break;
            case "like":
                $sqlWhere .= $conj.$valAry[0]. " like '%".$valAry[2]."%' ";
                break;
            case "notlike":
                $sqlWhere .= $conj.$valAry[0]. " not like '%".$valAry[2]."%' ";
                break;
            Default:
                $sqlWhere .= $conj.$valAry[0]. " = '".$valAry[2]."' ";

            }
            $conj = " and ";
        }
    }

    echo $sqlLookup.$sqlTable.$sqlWhere."<BR>";
    $report = $dbDAO->executeQry($sqlLookup.$sqlTable.$sqlWhere);

    ?>