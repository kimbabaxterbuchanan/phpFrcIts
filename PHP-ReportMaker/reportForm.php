<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/ReportDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/TableLinkDAO.php';
require_once dirname(__FILE__) .'/../PHP-DAOs/DbDAO.php';

foreach ( $_POST as $key => $val ) {
    $$key = $val;
}
foreach ( $_GET as $key => $val ) {
    $$key = $val;
}
foreach ( $_REQUEST as $key => $val ) {
    $$key = $val;
}

$tableLinkModel = new StdClass;
$tableLinkDAO = new TableLinkDAO();
$reportModel = new StdClass;
$reportDAO = new ReportDAO();
$dbDAO = new DbDAO();
$db_DATABASE = DB_DATABASE;
if ( isset($runQry) && $runQry == "true"){
    $runQry = true;
} else {
    $runQry = false;
}
if ( ! isset($btnSubmit) ) $btnSubmit = "";
if ( ! isset($btnTable1) ) $btnTable1 = "";
if ( ! isset($btnTable2) ) $btnTable2 = "";
if ( ! isset($host) ) $host = "";
if ( ! isset($user) ) $user = "";
if ( ! isset($password) ) $password = "";
$omittables = array();
$omittables[]="library";
$omittables[]="libraryreference";
$omittables[]="notification";
$omittables[]="tablelink";
$omittables[]="report";

$omitfields = array();
$omitfields[]="id";
$omitfields[]="create_date";
$omitfields[]="last_modified";

$omitlinkfields = array();
$omitlinkfields[]="create_date";
$omitlinkfields[]="last_modified";
//        include("dbconn.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php $hdrLabel = getLabel("WrongQuantity",$locale); ?>
<html>
    <head>
        <title>FRC Report Generator</title>
        <link href="../../PHP-css/frc-career.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="lib/color.css">
            <script language="javascript" type="text/javascript">
                function limitSelections(obj,qty){
                    var ctr = 1;
                    var alertflag = false
                    for ( var i=0; i < obj.options.length; i++ ) {
                        if ( obj.options[i].selected == true ){
                            if ( ctr > qty ){
                                obj.options[i].selected = false;
                                ctr--;
                                if ( ! alertflag ){
                                    var msg = "<?=$hdrLabel?>";
                                    msg.replace(/XxXquantityXxX/,qty);
                                    alert(msg);
                                }
                                alertflag = true;
                            }
                            ctr++;
                        }
                    }
                }
            </script>
    </head>
    <table width="807" height="1000" border="1" align="center" bordercolor="#000000"  cellpadding="0" cellspacing="0" >
          <tr><td width="100%"  height="750"  valign="top" align="left">
    <?php
    // Version 1.0
    // Revised by Jay P. Narain : narain2@yahoo.com
    // This code is distributed under GPL
    ?>

    <body bgcolor="#FFFFFF">

     Team FRC Report Generator<br>
        <hr>

        <!--a href="lib/capabilites.html">Capabilites</a>
        |<a href="lib/references.html">References</a>|-->
        <a href="lib/instruct.html">Instructions</a>
        <hr>
        <?php
        if ($btnSubmit == "Create" && $database == "" )
        {
        ?>
                <ul>
                    <li>Select a database
                </ul>

            <?php
            $result = mysql_list_dbs($link);

            if ( mysql_num_rows ($result) < 1 ) {
                $hdrLabel = getLabel("noDatabase",$locale);
                echo $hdrLabel;
                exit(1);
            }

            echo "<form method=\"post\" action=\"reportForm.php\"><select size=5 name=\"database\">\n";
            $i = 0;
            while ($i < mysql_num_rows ($result))
            {
                $db_names = mysql_tablename ($result, $i);
                echo "<option value=\"$db_names\"".($db_names == $database?' selected':'').">$db_names</option>\n";
                $i++;
            }

            echo "</select>\n";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=submit name=btnSubmit value='selectTables'>\n";
            echo "</form>\n";
            mysql_close($link);
        }


        if ($btnSubmit == "SelectTables" || ( $btnSubmit == "Create" && $database != "" ) )
        {
            $hdrLabel = getLabel("selectTablesInstructions",$locale);
            echo $hdrLabel;

            $result = mysql_listtables($database);

            echo "<form method=\"post\" action=\"reportForm.php\"><select multiple size=5 name=\"table[]\">\n";
            $i = 0;
            while ($i < mysql_num_rows ($result))
            {
                $tb_names = mysql_tablename ($result, $i);
                if ( ! in_array($tb_names,$omittables) )
                    echo "<option value=\"$tb_names\"".($table[$i] == $tb_names?' selected':'').">$tb_names</option>\n";
                $i++;
            }

            echo "</select>\n";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=submit name=btnSubmit  value='SelectFields'>\n";
            echo "</form>\n";
            mysql_free_result($result);
            mysql_close($link);
        }

        if ($btnSubmit == "SelectFields")
        {

            if (isset ($table) )
            {
                $xz = matchset($table);
                $cnty = count($xz);
                $fldy  = "";
                $nonfldy  = "";
                $fldyID  = "";
                $j = 0;
                for($j = 0; $j<$cnty; $j++)
                {
                    $nonfldy .=  $xz[$j].":";
                    $fldyID .=  "t".$j.":";
                    $fldy .=  $xz[$j].":"."t".$j. ($j<$cnty-1?",":"");
                }
            }


            $xy = matchset($table);
            $cnt = count($xy);

            $hdrLabel = getLabel("selectFieldsInstructions",$locale);
            echo "<h3>".$hdrLabel.":</h3>\n";
            echo "<table border=1 cellpadding=3><tr>";
            for($j = 0; $j<$cnt; $j++)
            {
                $tabz = $xy[$j];
                echo "<th><font color =red>$tabz</font></th>\n";
            }
            echo "</tr>";
            echo "<form method=\"post\" action=\"reportForm.php\">\n";
            echo "<tr>";
            for($j = 0; $j<$cnt; $j++)
            {
                $tabz = $xy[$j];

                $SQL = "select * from $tabz";
                $result = mysql_db_query($database,$SQL,$link);

                $x = mysql_num_fields($result);

                echo "<td><select multiple size=5 name=\"fields[]\">\n";
                $i = 0;
                while ($i < $x)
                {
                    $fx  = mysql_field_name($result, $i);
                    $fx_fields = "t".$j.".".$fx;
                    if ( ! in_array($fx,$omitfields) )
                        echo "<option value=\"$fx_fields\"".($fields[$i] == $fx_fields?' selected':'').">$fx_fields</option>\n";

                    $i++;
                }
                $fx_fields = "t".$j."."."*";
                echo "<option value=\"$fx_fields\"".($fields[$i] == $fx_fields?' selected':'').">$fx_fields</option>\n";

                echo "</select></td>\n";

                mysql_free_result($result);
            }
            echo "</tr><tr>";
            echo "<td colspan=".$cnt." >&nbsp;</td>\n";
            echo "</tr><tr>";
            $hdrLabel = getLabel("selectTwoFieldsInstructions",$locale);
            echo "<td colspan=".$cnt." >".$hdrLabel."</td>\n";
            echo "</tr><tr>";
            for($m=0; $m<$cnt-1; $m++)
            {
            echo "<td><select multiple size=5 name=\"tablelinks[]\" onchange=\"limitSelections(this,'2');\">\n";
            for($j = 0; $j<$cnt; $j++)
            {
                $tabz = $xy[$j];

                $SQL = "select * from $tabz";
                $result = mysql_db_query($database,$SQL,$link);

                $x = mysql_num_fields($result);

                $i = 0;
                while ($i < $x)
                {
                    $fx  = mysql_field_name($result, $i);
                    $fx_fields = "t".$j.".".$fx;
                    if ( ! in_array($fx,$omitlinkfields) )
                    echo "<option value=\"$fx_fields\"".($fields[$i] == $fx_fields?' selected':'').">$fx_fields</option>\n";

                    $i++;
                }
                mysql_free_result($result);
            }
            echo "</select></td>\n";
            }
            echo "<td>&nbsp;</td>";
            echo "</tr>";
            echo "</table>\n";


            $hdrLabel = getLabel("aggregateFunctions",$locale);
            $hdrLabel1 = getLabel("operator",$locale);
            $hdrLabel2 = getLabel("expr",$locale);
            echo "<h4><b>".$hdrLabel."</b></h4>
                                                        <table cellspacing=0 cellpadding=1 border=1>
                                                        <tr>
                                                        <td><b>".$hdrLabel1."</b></td>
                                                        <td><b>".$hdrLabel2."</b></td>
                                                        </tr>";
            echo "<tr>";
            for ($i=0; $i<5; $i++)
            {
                echo "<td>
                                                        <SELECT name=\"agg_op[]\">
                                                        <option value=\"None\"".($agg_op[$i] == 'None'?' selected':'').">None</option>
                                                        <OPTION VALUE=\"AVG\"".($agg_op[$i] == 'AVG'?' selected':'').">AVG(expr)</OPTION>
                                                        <OPTION VALUE=\"COUNT\"".($agg_op[$i] == 'COUNT'?' selected':'').">COUNT(expr)</OPTION>
                                                        <OPTION VALUE=\"MAX\"".($agg_op[$i] == 'MAX'?' selected':'').">MAX(expr)</OPTION>
                                                        <OPTION VALUE=\"MIN\"".($agg_op[$i] == 'MIN'?' selected':'').">MIN(expr)</OPTION>
                                                        <OPTION VALUE=\"STD\"".($agg_op[$i] == 'STD'?' selected':'').">STD(expr)</OPTION>
                                                        <OPTION VALUE=\"SUM\"".($agg_op[$i] == 'SUM'?' selected':'').">SUM(expr)</OPTION>
                                                        </SELECT>
                                                        </td>
                                                        <td><input type=\"text\" size=\"60\" name=\"agg_val[]\" value=\"".$agg_val[$i]."\"></td>\n</tr>";
            }
            echo "</table>";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=hidden name=nonfldy  value='$nonfldy'>\n";
            echo "<input type=hidden name=fldyID  value='$fldyID'>\n";
            echo "<input type=hidden name=fldy  value='$fldy'>\n";
            echo "<input type=hidden name=cnty  value='$cnty'>\n";
            echo "<INPUT TYPE=reset value=\"Clear All\">\n";
            echo "<input type=submit name=btnSubmit  value=SelectRows>\n";
            echo "</form>\n";
            mysql_close($link);
        }

        if ( $btnSubmit == "SelectRows")
        {
            $xy = matchset($fields);
            if ( $errflag ) {
                $linkAry = explodex(",",tablelinks);
                $xy = explodex(",",$fields);
                $agg_op = explodex(",",$ag_op);
                $agg_val = explodex(",",$ag_val);
            }
            if ( $tablelinks)
                $linkAry = matchset($tablelinks);
            $lcnt = count($linkAry);
            $cnt = count($xy);
            $cntr = $cnt;
            echo "<form method=\"post\" action=\"reportForm.php\">\n";
            $hdrLabel = getLabel("selectRowInstructions",$locale);
            echo $hdrLabel;
            echo "<table border='1'>";
            echo "<tr>";
            $nonflex = explodex(":", $nonfldy);
            $nflex = explodex(":",$fldyID);
            $tcnt = 0;
            $tval = "t0";
            $hdrLabel1 = getLabel("select",$locale);
            $hdrLabel2 = getLabel("column",$locale);
            echo "<th>".$hdrLabel1."</th><th>".$hdrLabel2."</th><th>&nbsp;</th><th>".$hdrLabel1."</th><th>".$hdrLabel2."</th></tr><tr>";
            if ( !is_array($chkfields)) {
                $chkfields = explodex(",",$chkfields);
            }
            $kcnt = count($chkfields);
            for($j = 0; $j<$cnt; $j++)
            {
                    $selected = "";
                    if ( isset($chkfields) && is_array($chkfields) ) {
                        for($k = 0; $k<$kcnt; $k++)
                        {
                            if ( $chkfields[$k] == $xy[$j] )
                                $selected = "checked";
                        }
                    }

                    $tary = explodex(".",$xy[$j]);
                    if ( $tval != $tary[0]) {
                        $tval = $tary[0];
                        $tcnt ++;
                        $tstr = $nonflex[$tcnt].".".$tary[1];
                    } else {
                        $tstr = $nonflex[$tcnt].".".$tary[1];
                    }
                    echo "<td width='75' align='center'><input name=\"chkfields[]\" type=\"checkbox\" value=\"".$xy[$j]."\" ".$selected."></td><td width='200'>".$tstr."</td>";
                    if ( $j > 0 && $j%2 == 1 ){
                       echo "</tr><tr>";
                    } else {
                        echo "<td width='100'>&nbsp;</td>";
                    }

            }
            echo "</tr>";
            echo "<table>";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=hidden name=fields value='$fldx'>\n";
            echo "<input type=hidden name=sql value='$sql'>\n";
            echo "<input type=hidden name=tablelinks value='".implodex(",",$linkAry)."'>\n";
            echo "<input type=hidden name=fields value='".implodex(",",$xy)."'>\n";
            echo "<input type=hidden name=qfields value='".$qfields."'>\n";
            echo "<input type=hidden name=fields_op value='".$fields_op."'>\n";
            echo "<input type=hidden name=fields_val value='".$fields_val."'>\n";
            echo "<input type=hidden name=fields_paren value='".$fields_paren."'>\n";
            echo "<input type=hidden name=fields_enab value='".$fields_enab."'>\n";
            echo "<input type=hidden name=gfields value='$gfields'>\n";
            echo "<input type=hidden name=hfields_val value='$hfields_val'>\n";
            echo "<input type=hidden name=ofields value='$ofields'>\n";
            echo "<input type=hidden name=ofields_val value='$ofields_val'>\n";
            echo "<input type=hidden name=ofields_enab value='$ofields_enab'>\n";
            echo "<input type=hidden name=lfields_off value='$lfields_off'>\n";
            echo "<input type=hidden name=lfields_row value='$lfields_row'>\n";
            echo "<input type=hidden name=ag_op value='".implodex(",",$agg_op)."'>\n";
            echo "<input type=hidden name=ag_val value='".implodex(",",$agg_val)."'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=hidden name=nonfldy  value='$nonfldy'>\n";
            echo "<input type=hidden name=fldyID  value='$fldyID'>\n";
            echo "<input type=hidden name=fldy  value='$fldy'>\n";
            echo "<input type=hidden name=cnty  value='$cnty'>\n";
            echo "<INPUT TYPE=reset value=\"Clear All\">\n";
            echo "<input type=submit name=btnSubmit  value=SelectOptions>\n";
            echo "</form>\n";
            mysql_close($link);
        }


        if ($btnSubmit == "SelectOptions")
        {
            $tablelinkAry = explodex(",",$tablelinks);
            $xy = explodex(",",$fields);
            $agg_op = explodex(",",$ag_op);
            $agg_val = explodex(",",$ag_val);
            $qfields = explodex(",",$qfields);
            $fields_op = explodex(",",$fields_op);
            $fields_val = explodex(",",$fields_val);
            $fields_paren = explodex("XxX",$fields_paren);
            $fields_enab = explodex(",",$fields_enab);
            $cnt = count($xy);
            $cntr = $cnt;
            $hdrLabel = getLabel("currentSelectStatement",$locale);
            echo $hdrLabel.":";
            $fldx  = "";
            $j = 0;
            $kcnt = count($chkfields);
            for($j = 0; $j<$cnt; $j++)
            {
                for($k = 0; $k<$kcnt; $k++)
                {
                    if ( $chkfields[$k] == $xy[$j] ){
                        $fldx .= $xy[$j] . ($k<$kcnt-1?", ":"");
                    }
                }
            }

            $sql = "SELECT  $fldx ";
            //Aggregation functions

            $asql = "";
            for ($i=0; $i<$cntr; $i++)
            {
                if ($agg_val[$i] != "")
                {
                    if (strstr($agg_val[$i],"*"))
                    {
                        $agg_val[$i] = "*";
                    }
                    $asql .= $agg_op[$i]."(".$agg_val[$i].")"." ";
                }
            }
            $sqlWhere = " WHERE ";
            $sqlWhere1 = " WHERE ";
            $conj = "";
            $fndTables = "XxX";
            echo "<BR>";
            if ( trim($sql) == "SELECT" && $asql !== "")
            $sql .= " ".$asql." From ";
            elseif ( $sql != "" && $asql == "")
            $sql .= " From ";
            else
            $sql .= ",".$asql." From ";

            $flex = explodex(":", $fldy);
            $nonflex = explodex(":", $nonfldy);
            $nflex = explodex(":",$fldyID);
            $fldz  = "";
            $tableLinksList = $tableLinkDAO->getRecord('tablelink','');
            for($j = 0; $j<$cnty; $j++)
            {
                $fldz .=  $flex[2*$j]." ".$flex[2*$j+1]. ($j<cnty-1?",":" ");
                $tblname = $nonflex[$j];
                $pflId = $nflex[$j];
                if ( $tableLinksList ) {
                    for ( $l = 0; $l < $cnty; $l++ ) {
                        if ( strpos($fndTables,"XxX".$nonflex[$l]."XxX") === false ) {
                            $db = mysql_select_db($database);
                            $sort = " where primarytable = '".$tblname."' and linktable = '".$nonflex[$l]."'";
                            $tableLinkModel = $tableLinkDAO->getRecord('tablelink',$sort);
                            if ( $tableLinkModel ) {
                                $tflId = $nflex[$l];
                                $fndTables .= $tableLinkModel->primarytable."XxX".$tableLinkModel->linktable."XxX";
                                $sqlWhere .= $conj.$pflId.".".$tableLinkModel->primaryfield;
                                $sqlWhere .= " = ".$tflId.".".$tableLinkModel->linkfield;
                                $conj = " and ";
                            }
                        }
                    }
                }
            }
            if ( ! $tableLinksList ) {
                    for($l = 0; $l<count($tablelinkAry); $l++)
                    {
                        $sqlWhere .= $conj.$tablelinkAry[$l]." = ".$tablelinkAry[$l+1];
                        $l++;
                        $conj = " and ";
                    }
            }
            $sql .= $fldz.$sqlWhere;

            echo "<p>";
            echo "<SPAN class=query>$sql</SPAN><BR><BR>\n";
            echo "</p>";

            echo "<form method=\"post\" action=\"reportForm.php\">\n";
            echo "<input type=hidden name=sql value=\"$sql\">";
            echo "<p>";
            echo "</p>";
            $hdrLabel = getLabel("whereClause",$locale);
            echo "<h3>".$hdrLabel."</h3>
                                                        <table cellspacing=0 cellpadding=1 border=1>
                                                        <tr>
                                                        <td><b>Field</b></td>
                                                        <td><b>Operator</b></td>
                                                        <td><b>Value</b></td>
                                                        <td><b>Grouping</b></td>
                                                        <td><b>Condition</b></td>
                                                        </tr>";
            for ($i=0; $i<$cntr; $i++)
            {
                $tval = "t0";
                $tcnt = "0";
                echo "<td>";
                echo "<SELECT name=\"qfields[]\">";
//                $xy = matchset($fields);
//                $cnt = count($xy);

                $j = 0;
                for($j = 0; $j<$cnt; $j++)
                {
                    $selected = "";
                    if ( isset($qfields) && is_array($qfields) && $qfields[$i] == $xy[$j]) {
                        $selected = "selected";
                    } else if ( $i == $j ) {
                        $selected = "selected";
                    }

                    $tary = explodex(".",$xy[$j]);
                    if ( $tval != $tary[0]) {
                        $tval = $tary[0];
                        $tcnt ++;
                        $tstr = $nonflex[$tcnt].".".$tary[1];
                    } else {
                        $tstr = $nonflex[$tcnt].".".$tary[1];
                    }
                    echo "<option value=\"".$xy[$j]."\" ".$selected." >".$tstr."</option>\n";
                }

                echo "</select></td>\n";
                echo "<td align='center'>
                                                        <SELECT name=\"fields_op[]\">
                                                        <OPTION VALUE=\"=\"".($fields_op[$i] == '='?' selected':'').">=</OPTION>
                                                        <OPTION VALUE=\"<>\"".($fields_op[$i] == '<>'?' selected':'')."><></OPTION>
                                                        <OPTION VALUE=\">\">".($fields_op[$i] == '>'?' selected':'')."></OPTION>
                                                        <OPTION VALUE=\">=\"".($fields_op[$i] == '>='?' selected':'').">>=</OPTION>
                                                        <OPTION VALUE=\"<\"".($fields_op[$i] == '<'?' selected':'')."><</OPTION>
                                                        <OPTION VALUE=\"<=\"".($fields_op[$i] == '<='?' selected':'')."><=</OPTION>
                                                        <OPTION VALUE=\"LIKE\"".($fields_op[$i] == 'LIKE'?' selected':'').">LIKE</OPTION>
                                                        <OPTION VALUE=\"NOT LIKE\"".($fields_op[$i] == 'NOT LIKE'?' selected':'').">Not LIKE</OPTION>
                                                        </SELECT>
                                                        </td>
                                                        <td><input type=\"text\" size=\"60\" name=\"fields_val[]\" value=\"".$fields_val[$i]."\"></td>";
                if ( $i == 0 ) {
                    echo "<td align='center'><select  name=\"fields_paren[]\">
                                                        <OPTION VALUE=\" \">  </OPTION>
                                                        <OPTION VALUE=\"open\"".($fields_paren[$i] == 'open'?' selected':'').">(</OPTION>
                                                        </SELECT>";
                } else if ( $i < $cntr -1 ) {
                    echo "<td align='center'><select  name=\"fields_paren[]\">
                                                        <OPTION VALUE=\" \">  </OPTION>
                                                        <OPTION VALUE=\"open\"".($fields_paren[$i] == 'open'?' selected':'').">(</OPTION>
                                                        <OPTION VALUE=\"close\"".($fields_paren[$i] == 'close'?' selected':'').">)</OPTION>
                                                        </SELECT>";
                }
                if ( $i < $cntr -1 ) {
                    echo "</td><td align='center'><select  name=\"fields_enab[]\">
                                                        <OPTION VALUE=\" \">  </OPTION>
                                                        <OPTION VALUE=\"AND\"".($fields_enab[$i] == 'AND'?' selected':'').">AND</OPTION>
                                                        <OPTION VALUE=\"OR\"".($fields_enab[$i] == 'OR'?' selected':'').">OR</OPTION>
                                                        </SELECT>";
                } else {
                    echo "<td align='center'><select  name=\"fields_paren[]\">
                                                        <OPTION VALUE=\" \">  </OPTION>
                                                        <OPTION VALUE=\"close\"".($fields_paren[$i] == 'close'?' selected':'').">)</OPTION>
                                                        </SELECT>";
                    echo "</td><td>&nbsp;";
                }
                echo "</td>\n</tr>";
            }
            echo "</table>";
            echo "<p>";
            echo "</p>";
            $hdrLabel = getLabel("groupClause",$locale);
            echo "<h3>".$hdrLabel."</h3>";
            echo "<table cellspacing=0 cellpadding=1 border=1>
                                                        <tr>
                                                        <td><b>Field</b></td>
                                                        </tr>";
            echo "<td>";
            echo "<SELECT name=\"gfields\">";
//            $xy = matchset($fields);
            $cnt = count($xy);

            echo "<option value=\"None\">None</option>\n";
            $j = 0;
            for($j = 0; $j<$cnt; $j++)
            {
                echo "<option value=\"$xy[$j]\"".(gfields == $xy[$j] ?' selected':'').">$xy[$j]</option>\n";
            }

            echo "</select></td>\n";

            echo "</table>";
            echo "<p>";
            echo "</p>";
            $hdrLabel = getLabel("havingClause",$locale);
            echo "<h3>".$hdrLabel."</h3>";
            echo "<table cellspacing=0 cellpadding=1 border=1>
                                                        <tr>
                                                        <td><b>Expr Field</b></td>
                                                        </tr>";
            echo "<td><input type=\"text\" size=\"60\" name=\"hfields_val\" value=\"".$hfields_val."\">
                                                        </td>\n";
            echo "</table>";
            echo "<p>";
            echo "</p>";
            $hdrLabel = getLabel("orderClause",$locale);
            echo "<h3>".$hdrLabel."</h3>";
            echo "<table cellspacing=0 cellpadding=1 border=1>
                                                        <tr>
                                                        <td><b>Field</b></td>
                                                        <td><b>Expr Field</b></td>
                                                        <td><b>SORT</b></td>
                                                        </tr>";
            echo "<td>";
            echo "<SELECT name=\"ofields\">";
//            $xy = matchset($fields);
            $cnt = count($xy);

            echo "<option value=\"None\">None</option>\n";
            $j = 0;
            for($j = 0; $j<$cnt; $j++)
            {
                echo "<option value=\"$xy[$j]\"".($ofields == $xy[$j] ?' selected':'').">$xy[$j]</option>\n";
            }

            echo "</select></td>\n";
            echo "<td><input type=\"text\" size=\"60\" name=\"ofields_val\"></td>\n";
            echo "<td><select  name=\"ofields_enab\">
                                                        <OPTION VALUE=\"ASC\"".($ofields_val == 'ASC' ?' selected':'').">ASC</OPTION>
                                                        <OPTION VALUE=\"DESC\"".($ofields_val == 'DESC' ?' selected':'').">DESC</OPTION>
                                                        </select>";
            echo "</td>\n";
            echo "</table>";
            echo "<p>";
            echo "</p>";
            $hdrLabel = getLabel("limitClause",$locale);
            echo "<h3>".$hdrLabel."</h3>";
            echo "<table cellspacing=0 cellpadding=1 border=1>
                                                        <tr>
                                                        <td><b>OFFSET BY</b></td>
                                                        <td><b>No.Of ROWS</b></td>
                                                        </tr>";
            echo "<td><input type=\"text\" size=\"30\" name=\"lfields_off\" value=\"".$lfields_off."\">
                                                        </td>\n";
            echo "<td><input type=\"text\" size=\"30\" name=\"lfields_row\" value=\"".$lfields_row."\">
                                                        </td>\n";
            echo "</table>";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=cntr value='$cntr'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=hidden name=tablelinks value='".$tablelinks."'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=hidden name=fields value='$fields'>\n";
            echo "<input type=hidden name=chkfields value='".implodex(",",$chkfields)."'>\n";
            echo "<input type=hidden name=ag_op value='$ag_op'>\n";
            echo "<input type=hidden name=ag_val value='$ag_val'>\n";
            echo "<input type=hidden name=nonfldy  value='$nonfldy'>\n";
            echo "<input type=hidden name=fldyID  value='$fldyID'>\n";
            echo "<input type=hidden name=fldy  value='$fldy'>\n";
            echo "<input type=hidden name=cnty  value='$cnty'>\n";
            echo "<INPUT TYPE=reset value=\"Clear All\">\n";
            echo "<input type=submit name=btnSubmit value=AssembleQuery>\n";
            echo "</form>\n";
            mysql_close($link);
        }
        if ($btnSubmit == "AssembleQuery")
        {

            //Construct Query from the inputs

            //Where  Constructs

            $whsql = "";
            $ssql = "";
            for ($i=0; $i<$cntr; $i++)
            {
                $begParen = "";
                $endParen = "";
                if ( $fields_paren[$i] == "open" ) $begParen = "(";
                if ( $fields_paren[$i] == "close" ) $endParen = ")";
                if ($fields_val[$i] != "" ) {
                    $conjNeeded = true;
                    if (trim($fields_enab[$i]) == "" )
                        $fields_enab[$i] = "AND";
                    $ssql .= stripslashes($begParen." ".$qfields[$i])." ".$fields_op[$i]." '".stripslashes($fields_val[$i])."' ".$endParen." ";
                    for ($m=$i+1; $m<$cntr; $m++)
                    {
                        if ($fields_val[$m] != "" && $conjNeeded ) {
                            $conjNeeded = false;
                            $ssql .= $fields_enab[$i]." ";
                        }
                    }
                }

            }

            if ( $ssql != "")
            {
                $whsql .= " and ". " ". $ssql;
            }

            $sql .= " ".$whsql;

            //Group By  Constructs

            $gpsql = "";
            if ( $gfields != "None")
            {
                $gpsql = " Group By"." ".$gfields;
            }
            $sql .= " ".$gpsql;

            //Having    Constructs

            $hvsql = "";
            if ( $hfields_val != "")
            {
                $hvsql = " Having"." ".$hfields_val;
            }
            $sql .= " ".$hvsql;

            //Order by  Constructs

            $obsql = "";
            if ( $ofields != "None")
            {
                $obsql = " Order by"." ".$ofields." ".$ofields_val." ".$ofields_enab;
            }
            $sql .= " ".$obsql;

            //Limit Constructs

            $lmsql = "";
            if ( $lfields_off != "" &&  $lfields_row != "" )
            $lmsql .= "Limit"." ".$lfields_off.", ".$lfields_row;
            elseif($lfields_off == "" &&  $lfields_row != "" )
            $lmsql = " Limit"." ".$lfields_row;

            $sql .= " ".$lmsql;

            $hdrLabel = getLabel("currentSelectStatement",$locale);
            echo "<h3>".$hdrLabel."</h3>";
            echo "<form method=\"post\" action=\"reportForm.php\">\n";
            echo "<textarea rows=10 cols=100 wrap=virtual  name=cquery> $sql</textarea>\n";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=hidden name=cntr value='$cntr'>\n";
            echo "<input type=hidden name=sql value='".urlencode($sql)."'>\n";
            echo "<input type=hidden name=fields value='$fields'>\n";
            echo "<input type=hidden name=tablelinks value='".$tablelinks."'>\n";
            echo "<input type=hidden name=chkfields value='".$chkfields."'>\n";
            echo "<input type=hidden name=qfields value='".implodex(",",$qfields)."'>\n";
            echo "<input type=hidden name=fields_op value='".implodex(",",$fields_op)."'>\n";
            echo "<input type=hidden name=fields_val value='".implodex(",",$fields_val)."'>\n";
            echo "<input type=hidden name=fields_paren value='".implodex("XxX",$fields_paren)."'>\n";
            echo "<input type=hidden name=fields_enab value='".implodex(",",$fields_enab)."'>\n";
            echo "<input type=hidden name=gfields value='$gfields'>\n";
            echo "<input type=hidden name=hfields_val value='$hfields_val'>\n";
            echo "<input type=hidden name=ofields value='$ofields'>\n";
            echo "<input type=hidden name=ofields_val value='$ofields_val'>\n";
            echo "<input type=hidden name=ofields_enab value='$ofields_enab'>\n";
            echo "<input type=hidden name=lfields_off value='$lfields_off'>\n";
            echo "<input type=hidden name=lfields_row value='$lfields_row'>\n";
            echo "<input type=hidden name=ag_op value='$ag_op'>\n";
            echo "<input type=hidden name=ag_val value='$ag_val'>\n";
            echo "<input type=hidden name=nonfldy  value='$nonfldy'>\n";
            echo "<input type=hidden name=fldyID  value='$fldyID'>\n";
            echo "<input type=hidden name=fldy  value='$fldy'>\n";
            echo "<input type=hidden name=cnty  value='$cnty'>\n";
            echo "<input type=submit name=btnSubmit value=ExecQuery>\n";
            echo "<INPUT TYPE=reset value=\"Clear All\">\n";
            echo "<hr>\n";
            $hdrLabel = getLabel("saveQueryAs",$locale);
            echo $hdrLabel;
            echo "<input type=text name=saveas value='$saveas'>\n";
            echo "<hr>\n";
            echo "<input type=submit name=btnTable1 value=SaveQuery>\n";
            echo "<input type=submit name=btnTable2 value=PickQuery>\n";
            echo "</form>\n";
            mysql_close($link);
        }
        if ($btnTable1 == "SaveQuery")
        {
            if ($btnSubmit == "")
            $btnSubmit  ="default";

            //   $dirname = "sqldocs";
            $hdrLabel = getLabel("saveQueryFile",$locale);
            echo $hdrLabel." ".$dirname."/".$saveas;
            $savename = $dirname."/".$saveas;
            $fp = fopen($savename, "w+");
            fputs($fp, $cquery);
            fclose($fp);

        }

        if ($btnSubmit == "Save")
        {
            $tmName = $teamName;
            $stat = $reportDAO->saveUpdateReportById ($id,$reportname,$reportdescription,$reporttitle,$selected_tables,
                    $cquery,$displaytype,$tmName,$create_dt );
            $cquery = urldecode($cquery);
            $sql = urldecode($sql);
            $btnSubmit = "ExecQuery";
        }
        if ($btnSubmit == "Run" )
        {
            $result = $reportDAO->getReportByID($id);
            $report = mysql_fetch_assoc($result);
            $cquery = $report['reportsql'];
            $reportname=$report['reportname'];
            $reportdescription=$report['reportdescription'];
            $reporttitle=$report['reporttitle'];
            $displaytype=$report['displaytype'];
            $btnSubmit = "ExecQuery";
            $runQry = true;
        }


        if ($btnTable2 == "PickQuery")
        {

            if ($btnSubmit == "")
            $btnSubmit  ="default";

            if ($database == "")
            $database ="mysql";

            $hdrLabel = getLabel("PickFile",$locale);
            echo $hdrLabel;

            echo "<form method=\"post\" action=\"reportForm.php\"><select  name=\"loadas\">\n";
            //     $dirname = "sqldocs";
            $dh = opendir($dirname);
            while ( gettype ( $file = readdir( $dh )) != boolean )
            {
                echo "<option value=\"$file\">$file</option>\n";
            }
            closedir ($dh);
            echo "</select>";

            echo "<input type=text   name=newdb value='$newdb'>\n";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=submit name=btnSubmit value=LoadQuery>\n";
            echo "</form>\n";
        }

        if ($btnSubmit == "LoadQuery" )
        {
            //   $dirname = "sqldocs";
            $loadfn = $dirname."/".$loadas;
            $hdrLabel = getLabel("couldNotLoadFile",$locale);
            $fp = fopen($loadfn, "r") or die($hdrLabel);
            while (! feof($fp))
            {
                $sql .= fread($fp, 64);
            }
            fclose($fp);

            // Change the database if necessary

            if ($newdb == "")
            $newdb = $database;

            if ($database != $newdb )
            {
                $database = $newdb;
                mysql_select_db( $newdb, $link );
            }

            $hdrLabel = getLabel("currentSelectStatement",$locale);
            echo "<h3>".$hdrLabel."</h3>";

            echo "<form method=\"post\" action=\"reportForm.php\">\n";
            echo "<textarea rows=10 cols=100 wrap=virtual  name=cquery>". urldecode($sql)."</textarea>\n";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=submit name=btnSubmit value=ExecQuery>\n";
            echo "<INPUT TYPE=reset value=\"Clear All\">\n";
            echo "<hr>\n";
            echo "Save Query As";
            echo "<input type=text name=saveas value='$saveas>\n";
            echo "<hr>\n";
            echo "<input type=submit name=btnTable1 value=SaveQuery>\n";
            echo "<input type=submit name=btnTable2 value=PickQuery>\n";
            echo "</form>\n";
        }


        if ($btnSubmit == "ExecQuery")
        {
            if( $db_DATABASE == "" && !$dbi = mysql_select_db($database)) {
                $hdrLabel = getLabel("databaseSelectionError",$locale);
                echo $hdrLabel;
            }
            $cquery = urldecode($cquery);
            $qid = mysql_query(stripslashes($cquery));

            echo "<form id=\"rptForm\" name=\"rptForm\" method=\"post\" action=\"reportForm.php\" >";
            if ( $displaytype == "" ) $displaytype="list";
            $hdrLabel1 = getLabel("reportType",$locale);
            $hdrLabel2 = getLabel("list",$locale);
            $hdrLabel3 = getLabel("tablular",$locale);
            $hdrLabel4 = getLabel("SaveReportAsCSV",$locale);
            echo "<div nowrap align=center>".$hdrLabel1.":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$hdrLabel2.":<input type=radio name=displaytype id=displaytype value='list' ".($displaytype == "list"?checked:'')." onclick='javascript:document.rptForm.submit();'>";
            echo "&nbsp;&nbsp;&nbsp;".$hdrLabel3.": <input type=radio name=displaytype id=displaytype value='nonlist' ".($displaytype == "nonlist"?checked:'')." onclick='javascript:document.rptForm.submit();'>";
            echo "<div nowrap align=right><a href='saveReport.php?cquery=".urlencode($cquery)."&displaytype=".$displaytype."'>".$hdrLabel4."</a></div></div>";
            echo "<hr>\n";
            echo "<table align='center' border=0 cellpadding=3>\n";
            echo "<tr><td><table border=1 cellpadding=3>\n";
            $rptCnt = mysql_num_fields($qid);
            echo "<tr><td align=center colspan=".$rptCnt.">".$reporttitle."</td></tr>";
            $colspan = $rptCnt;
            echo "<tr><td align=right colspan=".$rptCnt.">Date: ".date("Y/m/d H:i:s")."</td></tr>";
            if ( $displaytype == "list" ){
                echo "<tr class=\"trhead\">\n";
                echo "<td><table border=1 cellpadding=3><tr>";

                for ($i=0; $i<$rptCnt; $i++)
                {
                    $hdr = mysql_field_name($qid,$i);
                    $hdr = getLabel($hdr,$locale);
                    if (!$hdr) {
                        $hdrLabel1 = getLabel("noInformationAvailable",$locale);
                        print ($hdrLabel."<br>\n");
                        continue;
                    }
                    printf( "<th>%s</th>\n",$hdr );

                }
                echo "</tr>\n";

                $classes=array("td1", "td2");
                $classifier=0;

                while ($row = mysql_fetch_row($qid))
                {
                    echo "<tr class=\"". $classes[$classifier]."\">\n";
                    for ($i=0; $i< mysql_num_fields($qid); $i++)
                    {
                        printf( "<td>%s</td>\n",htmlspecialchars ($row[$i]) );
                    }
                    echo "</<tr>\n";
                    $classifier=($classifier+1)%2;
                }
                echo "</table></td></tr>";
            } else {
                $i = 0;
                echo "<tr class=\"trhead\">\n";
                echo "<td align=center><table border=0 cellpadding=3><tr>";
                $classes=array("td1", "td2");
                $classifier=0;
                while ($row = mysql_fetch_row($qid))
                {
                        for ($i=0; $i< mysql_num_fields($qid); $i++)
                        {
                            echo "<tr class=\"". $classes[$classifier]."\">\n";
                            $hdr = mysql_field_name($qid,$i);
                            $hdr = getLabel($hdr,$locale);
                            printf( "<td align=right colspan='2' >%s:</td>\n", $hdr);
                            printf( "<td align=left colspan='2' >%s</td>\n",htmlspecialchars ($row[$i]) );
                            echo "</<tr>\n";
                        }
                        echo "<tr><td>&nbsp;</td></tr>\n";
                    $classifier=($classifier+1)%2;
                }
                echo "</table></td></tr>";
            }
            echo "<input type=hidden name=btnSubmit value='$btnSubmit'>\n";
            echo "<input type=hidden name=host value='$host'>\n";
            echo "<input type=hidden name=user value='$user'>\n";
            echo "<input type=hidden name=password value='$password'>\n";
            echo "<input type=hidden name=database value='$database'>\n";
            echo "<input type=hidden name=reportname id=reportname value=\"".$reportname."\">";
            echo "<input type=hidden name=reportdescription id=reportdescription value=\"".$reportdescription."\">";
            echo "<input type=hidden name=reporttitle id=reporttitle value=\"".$reporttitle."\">";
            echo "<input type=hidden name=cntr value='$cntr'>\n";
            echo "<input type=hidden name=sql value='".urlencode($sql)."'>\n";
            echo "<input type=hidden name=cquery value='".urlencode($cquery)."'>\n";
            echo "<input type=hidden name=fields value='$fields'>\n";
            echo "<input type=hidden name=tablelinks value='".$tablelinks."'>\n";
            echo "<input type=hidden name=chkfields value='".$chkfields."'>\n";
            echo "<input type=hidden name=qfields value='".$qfields."'>\n";
            echo "<input type=hidden name=fields_op value='".$fields_op."'>\n";
            echo "<input type=hidden name=fields_val value='".$fields_val."'>\n";
            echo "<input type=hidden name=fields_paren value='".$fields_paren."'>\n";
            echo "<input type=hidden name=fields_enab value='".$fields_enab."'>\n";
            echo "<input type=hidden name=gfields value='$gfields'>\n";
            echo "<input type=hidden name=hfields_val value='$hfields_val'>\n";
            echo "<input type=hidden name=ofields value='$ofields'>\n";
            echo "<input type=hidden name=ofields_val value='$ofields_val'>\n";
            echo "<input type=hidden name=ofields_enab value='$ofields_enab'>\n";
            echo "<input type=hidden name=lfields_off value='$lfields_off'>\n";
            echo "<input type=hidden name=lfields_row value='$lfields_row'>\n";
            echo "<input type=hidden name=ag_op value='$ag_op'>\n";
            echo "<input type=hidden name=ag_val value='$ag_val'>\n";
            echo "<input type=hidden name=nonfldy  value='$nonfldy'>\n";
            echo "<input type=hidden name=fldyID  value='$fldyID'>\n";
            echo "<input type=hidden name=fldy  value='$fldy'>\n";
            echo "<input type=hidden name=cnty  value='$cnty'>\n";
            echo "<tr><td align=center colspan=".$colspan."><input type=\"submit\" name=\"btnSubmit\" id=\"btnSubmit\" value=\"Start Over\">&nbsp;&nbsp;&nbsp;";
            if ( ! $runQry ) {
                echo "<input type=hidden name=runQry  value='false'>\n";
                echo "<input type=\"submit\" name=\"btnSubmit\" id=\"btnSubmit\" value=\"SelectRows\">&nbsp;&nbsp;&nbsp;";
                echo "<input type=\"submit\" name=\"btnSubmit\" id=\"btnSubmit\" value=\"Save\">";
            } else {
                echo "<input type=hidden name=runQry  value='true'>\n";
            }
            echo "</td></tr>";
            echo "</table>\n";
            echo "</form>";
            mysql_free_result($qid);

            mysql_close($link);
        }
        ?>

        <?php
        if (empty($btnSubmit) || $btnSubmit == "Start Over" )
        {
            ?>
                Welcome to FRC Report Generator
                <ul>
                    <li>Create or Run a Report
                </ul>

                <form id="rptForm" name="rptForm" method="post" action="reportForm.php" >
                    <input type="hidden" id='database' name='database' value='<?=$db_DATABASE?>' />
                    <table border=1 cellpadding=3>
                        <tr><th><font color =green>Create Query</font></th>
                        <th>&nbsp;</th></tr>
                        <tr><td>Report Name: </td><td><input type="text" size="40" name="reportname" id="reportname" value="<?=$name?>"></td></tr>
                        <tr><td>Report Descriptiong: </td><td><textarea cols=60 rows=5 name="reportdescription" id="reportdescription"><?=$description?></textarea></td></tr>
                        <tr><td>Report Title: </td><td><input type="text" size="40" name="reporttitle" id="reporttitle" value="<?=$title?>"></td></tr>
                        <tr>
                            <td colspan=2 align=center><input type="submit" name="btnSubmit" id="btnSubmit" value="Create"></td>
                        </tr>
                    </table>
                </form>
                <?php
            }
            ?>
            </td></tr></table>
            <?
            function explodex($sep,$xx){
                $str = explode($sep,$xx);
                $strAry = array();
                for ( $i = 0; $i < count($str); $i++ ) {
                    if ( trim($str[$i]) == "reportFormSpacer")
                        $str[$i] = "";
                    $strAry[$i] = $str[$i];
                }
                return $strAry;
            }
            function implodex($sep,$xx){
                $str = "";
                for ( $i = 0; $i < count($xx); $i++ ) {
                    if ( trim($xx[$i]) == "")
                        $xx[$i] = "reportFormSpacer";
                    $str .= $xx[$i].($i<count($xx)-1?$sep:"");
                }
                return $str;
            }
            function matchset($xx)
            {
                $arrx = array_values($xx);
                $i = 0;
                while (list ($key, $val) = each ($arrx)) {
                    $xy[$i]  = $val;
                    $i++;
                }
                $cnt = $i;
                return $xy;
            }
            function CustomErrorHandler($errNumber, $errDescript, $errFile, $errLine)
            {
                global $errflag;
                if ( $errNumber < 2048 && $errNumber != 8) {
                    $errflag = true;
                }
            }
            ?>

    </body>
</html>