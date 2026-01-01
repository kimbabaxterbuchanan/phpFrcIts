<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/reportFormAction.php';

if ( $id != "") {
    $res = mysql_fetch_assoc($result);
    foreach ( $res as $key => $val) {
        if ( $val != "" && $key != 'teamName' && $key != 'reportsql') {
            $$key = $val;
        } else if ( $key == "teamName" ) {
            $tmName = $val;
        } else if ( $key == 'reportsql') {
            $$key = urldecode($val);
        }
    }
}
if ( $tmName == "" && $webAdmin != "yes")
$tmName = $teamName;
?>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/Dom_Utils.js"></script>

<form id="form1" name="form1" method="post" action="reportForm.php">
    <input type="hidden" name="id" id="id" value="<?= $id ?>" />
    <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
    <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
    <input type="hidden" name="postForm" id="postForm" value="yes"/>
    <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
    <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
    <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
    <div align="center">
        <p align="center">
            <strong>Team FRC Email</strong>
        </p>
    </div>
    <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
        <?php if ( $formAction != "delete" ) { ?>
        <tr>
            <td nowrap align='right'>Report Id: </td>
            <td><?=$id?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Report Name: </td>
            <td><input size='40' name="reportname" id="reportname" type="text" size="15" value="<?=$reportname?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Report Description: </td>
            <td><input size='40' name="reportdescription" id="reportdescription" type="text" size="15" value="<?=$reportdescription?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Reort Title: </td>
            <td><input size='40' name="reporttitle" id="reporttitle" type="text" size="15" value="<?=$reporttitle?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Built Report SQL: </td>
            <td><?=$limg?><input size='40' name="reportsql" id="reportsql" type="text" size="15" value="<?=$reportsql?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Type of Display: </td>
            <td><input size='40' name="displaytype" id="displaytype" type="text" size="15" value="<?=$displaytype?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Team Name: </td>
            <td>
                <?php if ( $webAdmin == "yes" ) { ?>
                <select name="tmName" id="tmName" >
                    <option value="" selected></option>
                    <?php while($res = mysql_fetch_array($teamResult)) {
                        $selected = "";
                        if ( $res['teamName'] == $tmName )
                        $selected = "selected";
                        ?>
                    <option value="<?= $res['teamName'] ?>" <?=$selected?> ><?= $res['teamName'] ?></option>
                    <?php } ?>
                </select>
                <?php } else { ?>
                <input type="hidden" name="wbAdmin" id="wbAdmin" value="<?=$wbAdmin?>"/>
                <input type="hidden" name="tmName" id="tmName" value="<?= $teamName ?>"/>
                <?= $teamName ?>
                <?php } ?>
            </td>
        </tr>
        <?php } else { ?>
        <tr>
            <td nowrap align='right'>ID: </td>
            <td nowrap><?=$id?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Report Name: </td>
            <td nowrap><?=$reportname?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Report Description: </td>
            <td nowrap><?=$reportdescription?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Report Title: </td>
            <td nowrap><?=$reporttitle?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Built Report SQL: </td>
            <td nowrap><?=$reportsql?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Type of Display: </td>
            <td nowrap><?=$displaytype?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Team Name: </td>
            <td nowrap><?=$tmName?></td>
        </tr>
        <?php } ?>
        <tr>
            <?php if ( $teamManager == "yes") {
                if ( $formAction != "view" ) { ?>
            <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
            <?php } else { ?>
            <td><input type="submit" name="cancel" id="cancel" value="Back" /></td>
            <?php }
    } else {
        if ( $formAction != "view" ) { ?>
            <td><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
            <?php } else { ?>
            <td><input type="button" onclick="cancelButton();" name="back" id="back" value="Back"/></td>
            <?php }
    }
    if (  $formAction != "delete"  && $formAction != "view" ) {?>
            <td><input type="submit" name="save" id="save" value="Save" /></td>
            <?php } else {
            if ( $formAction != "view" ) { ?>
            <td><input type="submit" name="delete" id="delete" value="Delete" /></td>
            <?php }
    } ?>
        </tr>
    </table>
</form>
</body>
</html>