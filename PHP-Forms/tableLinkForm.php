<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/tableLinkFormAction.php';

if ( $id != "") {
    $res = mysql_fetch_assoc($result);
    foreach ( $res as $key => $val) {
        $$key = $val;
    }
}
?>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/Dom_Utils.js"></script>

<form id="form1" name="form1" method="post" action="tableLinkForm.php">
    <input type="hidden" name="id" id="id" value="<?= $id ?>" />
    <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
    <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
    <input type="hidden" name="postForm" id="postForm" value="yes"/>
    <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
    <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
    <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
    <div align="center">
        <p align="center">
            <strong>Team FRC Table Links</strong>
        </p>
    </div>
    <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
    <?php if ( $formAction != "delete" ) { ?>
    <tr>
        <td nowrap align='right'>Primary Table: </td>
        <td><input size='40' name="primarytable" id="primarytable" type="text" size="15" value="<?=$primarytable?>" /></td>
    </tr>
    <tr>
        <td nowrap align='right'>Primary Field: </td>
        <td><input size='40' name="primaryfield" id="primaryfield" type="text" size="15" value="<?=$primaryfield?>" /></td>
    </tr>
    <tr>
        <td nowrap align='right'>Link Table: </td>
        <td><input size='40' name="linktable" id="linktable" type="text" size="15" value="<?=$linktable?>" /></td>
    </tr>
    <tr>
        <td nowrap align='right'>Link Field: </td>
        <td><input size='40' name="linkfield" id="linkfield" type="text" size="15" value="<?=$linkfield?>" /></td>
    </tr>
    <?php } else { ?>
    <tr>
        <td nowrap align='right'>ID: </td>
        <td nowrap><?=$id?></td>
    </tr>
    <tr>
        <td nowrap align='right'>Primary Table: </td>
        <td nowrap><?=$primarytable?></td>
    </tr>
    <tr>
        <td nowrap align='right'>Primary Field: </td>
        <td nowrap><?=$primaryfield?></td>
    </tr>
    <tr>
        <td nowrap align='right'>Link Table: </td>
        <td nowrap><?=$linktable?></td>
    </tr>
    <tr>
        <td nowrap align='right'>Link Field: </td>
        <td nowrap><?=$linkfield?></td>
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