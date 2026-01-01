<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/notificationFormAction.php';

if ( $id != "") {
    $res = mysql_fetch_assoc($result);
    foreach ( $res as $key => $val ){
        if ( $val != "" && $key != 'teamName') {
            $$key = $val;
        } else if ( $key == "teamName" ) {
            $tmName = $val;
        }
    }
}
if ( $tmName == "" && $webAdmin != "yes")
$tmName = $teamName;
?>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/Dom_Utils.js"></script>

<form id="form1" name="form1" method="post" action="notificationForm.php">
    <input type="hidden" name="id" id="id" value="<?= $id ?>" />
    <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
    <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
    <input type="hidden" name="postForm" id="postForm" value="yes"/>
    <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
    <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
    <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
    <div align="center">
        <p align="center">
            <strong>Team FRC Notification</strong>
        </p>
    </div>
    <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
            <td nowrap align='right'>Id: </td>
            <td nowrap><?=$id?></td>
        </tr>
        <tr>
            <td nowrap align='right'>User Id: </td>
            <td nowrap><?=$userId?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Email ID: </td>
            <td nowrap><?=$emailId?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Status: </td>
            <td nowrap><?=$status?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail To: </td>
            <td char=";"><?=$toMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail Reply: </td>
            <td nowrap><?=$replyMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail CC: </td>
            <td nowrap><?=$ccMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Subject: </td>
            <td nowrap><?=$message?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Team Name: </td>
            <td nowrap><?=$tmName?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Date Sent: </td>
            <td nowrap><?=$create_dt?></td>
        </tr>
        <tr>
            <?php if ( $teamManager == "yes") { ?>
            <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
            <?php } else { ?>
            <td><input type="submit" name="cancel" id="cancel" value="Back" /></td>
            <?php }
            if (  $formAction == "delete" ) {?>
            <td><input type="submit" name="delete" id="delete" value="Delete" /></td>
            <?php } ?>
        </tr>
    </table>
</form>
</body>
</html>