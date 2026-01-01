<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/emailFormAction.php';

$seq_num = "";
$toMail = "";
$toName = "";
$fromMail = "";
$fromName = "";
$replyMail = "";
$replyName = "";
$ccMail = "";
$ccName = "";
$subject = "";
$body = "";
$altBody = "";
if ( $id != "") {
    $res = mysql_fetch_assoc($result);
    foreach ( $res as $key => $val ){
        if ( $val != "" && $key != 'teamName') {
            $$key = $val;
        } else if ( $key == "teamName" ) {
            $tmName = $val;
        }
    }
} else {
    $seq_num = $emailDAO->getNextSeqNum();
}
if ( $tmName == "" && $webAdmin != "yes")
$tmName = $teamName;
?>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/Dom_Utils.js"></script>

<form id="form1" name="form1" method="post" action="emailForm.php">
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
            <td nowrap align='right'>Sequence Number: </td>
            <td><input size='40' name="seq_num" id="seq_num" type="text" size="15" value="<?=$seq_num?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Smtp Port: </td>
            <td><input size='40' name="smtpPort" id="smtpPort" type="text" size="15" value="<?=$smtpPort?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>MailHost: </td>
            <td><input size='40' name="mailHost" id="mailHost" type="text" size="15" value="<?=$mailHost?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>is HTML: </td>
            <?php
            $selectNo = "selected";
            $selectYes = "";
            if ( "yes" == $isHTML ) {
                $selectNo = "";
                $selectYes = "selected";
            }
            ?>

            <td><select name="isHTML" id="isHTML">
                    <option value="no" <?= $selectNo ?> >No</option>
                    <option value="yes" <?= $selectYes ?> >Yes</option>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap align='right'>SMTP Authorization Required: </td>
            <?php
            $selectNo = "selected";
            $selectYes = "";
            if ( "yes" == $smtpAuth ) {
                $selectNo = "";
                $selectYes = "selected";
            }
            ?>

            <td><select name="smtpAuth" id="smtpAuth">
                    <option value="no" <?= $selectNo ?> >No</option>
                    <option value="yes" <?= $selectYes ?> >Yes</option>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap align='right'>SMTP Email Username: </td>
            <td><input size='40' name="smtpUsername" id="smtpUsername" type="text" size="15" value="<?=$smtpUsername?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>SMTP Email Password: </td>
            <td><input size='40' name="smtpPassword" id="smtpPassword" type="password" size="15" value="<?=$smtpPassword?>" /></td>
        </tr>
        <tr>
            <?php
            $limg = "<a href=\"javascript:popEmalName('fromMail','fromName','form1','tmName')\">Add Email to Mail From List</a><br>";
            ?>

            <td nowrap align='right'>Mail From: </td>
            <td><?=$limg?><input size='40' name="fromMail" id="fromMail" type="text" size="15" value="<?=$fromMail?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>From Name: </td>
            <td><input size='40' name="fromName" id="fromName" type="text" size="15" value="<?=$fromName?>" /></td>
        </tr>
        <tr>
            <?php
            $limg = "<a href=\"javascript:popEmalName('toMail','toName','form1','tmName')\">Add Email to Mail To List</a><br>";
            ?>
            <td nowrap align='right'>Mail To: </td>
            <td><?=$limg?><input size='40' name="toMail" id="toMail" type="text" size="15" value="<?=$toMail?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>To Name: </td>
            <td><input size='40' name="toName" id="toName" type="text" size="15" value="<?=$toName?>" /></td>
        </tr>
        <tr>
            <?php
            $limg = "<a href=\"javascript:popEmalName('replyMail','replyName','form1','tmName')\">Add Email to Mail Reply List</a><br>";
            ?>
            <td nowrap align='right'>Mail Reply: </td>
            <td><?=$limg?><input size='40' name="replyMail" id="replyMail" type="text" size="15" value="<?=$replyMail?>" /></td>
        </tr>
        <tr>
            <td nowrap align='right'>Reply Name: </td>
            <td><input size='40' name="replyName" id="replyName" type="text" size="15" value="<?=$replyName?>" /></td>
        </tr>
        <tr>
            <?php
            $limg = "<a href=\"javascript:popEmalList('ccMail','ccName','form1','tmName')\">Add Email to From Mail CC List</a><br>";
            ?>
            <td nowrap align='right'>Mail CC: </td>
            <td><?=$limg?><textarea name="ccMail" id="ccMail" type="text" rows=2 cols=50 ><?=$ccMail?></textarea></td>
        </tr>
        <tr>
            <td nowrap align='right'>CC Name: </td>
            <td><textarea name="ccName" id="ccName" type="text" rows=2 cols=50 ><?=$ccName?></textarea></td>
        </tr>
        <tr>
            <td nowrap align='right'>Subject: </td>
            <?php
            $limg = "<a href=\"javascript:showTextFormat('subject','form1','tmName')\">Edit Email Subject</a><br>";
            ?>
            <td><?=$limg?><textarea name="subject" id="subject" type="text" rows=2 cols=50 ><?=$subject?></textarea></td>
        </tr>
        <tr>
            <td nowrap align='right'>HTML Body: </td>
            <?php
            $limg = "<a href=\"javascript:showTextFormat('body','form1','tmName')\">Edit Email Body with HTML</a><br>";
            ?>
            <td><?=$limg?><textarea name="body" id="body" type="text" rows=5 cols=50 ><?=$body?></textarea></td>
        </tr>
        <tr>
            <td nowrap align='right'> Text Body: </td>
            <?php
            $limg = "<a href=\"javascript:showTextFormat('altBody','form1','tmName')\">Edit Email Body without Text with optional HTML</a><br>";
            ?>
            <td><?=$limg?><textarea name="altBody" id="altBody" type="text" rows=5 cols=50 ><?=$altBody?></textarea></td>
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
            <td nowrap align='right'>Sequence Number: </td>
            <td nowrap><?=$seq_num?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Role: </td>
            <td nowrap><?=$role?></td>
        </tr>
        <tr>
            <td nowrap align='right'>MailHost: </td>
            <td nowrap><?=$mailHost?></td>
        </tr>
        <tr>
            <td nowrap align='right'>is HTML: </td>
            <td nowrap><?=$isHTML?></td>
        </tr>
        <tr>
            <td nowrap align='right'>SMTP Authorization Required: </td>
            <td nowrap><?=$smtpAuth?></td>
        </tr>
        <tr>
            <td nowrap align='right'>SMTP Email Address: </td>
            <td nowrap><?=$username?></td>
        </tr>
        <tr>
            <td nowrap align='right'>SMTP Email Password: </td>
            <td nowrap><?=$password?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail From: </td>
            <td nowrap><?=$fromMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>From Name: </td>
            <td nowrap><?=$fromName?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail From: </td>
            <td nowrap><?=$toMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Name From: </td>
            <td nowrap><?=$toName?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail Reply: </td>
            <td nowrap><?=$replyMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Reply Name: </td>
            <td nowrap><?=$replyName?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Mail CC: </td>
            <td nowrap><?=$ccMail?></td>
        </tr>
        <tr>
            <td nowrap align='right'>CC Name: </td>
            <td nowrap><?=$ccName?></td>
        </tr>
        <tr>
            <td nowrap align='right'>Subject: </td>
            <td nowrap><?=$subject?></td>
        </tr>
        <tr>
            <td nowrap align='right'>HTML Body: </td>
            <td nowrap><?=$body?></td>
        </tr>
        <tr>
            <td nowrap align='right'> Text Body: </td>
            <td nowrap><?=$altBody?></td>
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