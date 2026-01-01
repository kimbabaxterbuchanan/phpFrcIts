<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/sendMailFormAction.php';

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
}
if ( $tmName == "" && $webAdmin != "yes")
$tmName = $teamName;
?>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
<script language="JavaScript" type="text/javascript" src="/PHP-jsScript/Dom_Utils.js"></script>
<script language="JavaScript" type="text/javascript">
    function showInfo(iName){
        obj = new getObj(iName);
        obj.value="";
        document.forms['form1'].submit();
    }
    function clearReset() {
        obj = new getObj("resetPassword");
        obj.checked = false;
    }
    function setSend() {
        obj = new getObj("sendType");
        obj.checked = true;
    }
</script>

<form id="form1" name="form1" method="post" action="sendMailForm.php">
<input type="hidden" name="id" id="id" value="<?= $id ?>" />
<input type="hidden" name="section" id="section" value="<?= $section ?>"/>
<input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
<input type="hidden" name="postForm" id="postForm" value="yes"/>
<input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
<input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
<input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
<input name="seq_num" id="seq_num" type="hidden" value="<?=$seq_num?>" />
<input name="role" id="role" type="hidden" value="<?=$role?>" />
<input name="mailHost" id="mailHost" type="hidden" value="<?=$mailHost?>" />
<input name="isHTML" id="isHTML" type="hidden" value="<?=$isHTML?>" />
<input name="smtpPort" id="smtpPort" type="hidden" value="<?=$smtpPort?>" />
<input name="smtpAuth" id="smtpAuth" type="hidden" value="<?=$smtpAuth?>" />
<input name="username" id="smtpUsername" type="hidden" value="<?=$smtpUsername?>" />
<input name="password" id="smtpPassword" type="hidden" value="<?=$smtpUsername?>" />
<div align="center">
    <p align="center">
        <strong>Team FRC Email</strong>
    </p>
</div>
<table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
        <td align="right">
            Mail To Who:
        </td>
        <td>
            <?php
            $toTypeValAry = Array();
            $toTypeValAry[] = "all";
            $toTypeValAry[] = "teammember";
            $toTypeValAry[] = "member";
            $toTypeAry = Array();
            $toTypeAry[] = "Entire Team";
            $toTypeAry[] = "Corporate Team";
            $toTypeAry[] = "Member";
            ?>
            <select id="toType" name="toType" onchange="showInfo('postForm')">
                <?php for ( $i = 0; $i < count($toTypeAry); $i++ ) {
                    $selected = "";
                    if ( $toType == "" ) $toType = "member";
                    if ( $toTypeValAry[$i] == $toType )
                    $selected = "selected";
                    ?>
                <option value="<?=$toTypeValAry[$i]?>" <?=$selected?> ><?=$toTypeAry[$i]?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <?php
        $selectTxt = "checked";
        $selectPwd = "";
        $selectRst = "";
        if ( $resetPassword == "reset" ) {
            $selectRst = "checked";
        }
        if ( $sendType == "password" ) {
            $selectTxt = "";
            $selectPwd = "checked";
        }
        ?>
        <td nowrap>
            Send Password: <input <?=$selectPwd?> type="radio" name="sendType" id="sendType" value="password"/>
            <br>
            Reset Password: <input <?=$selectRst?>  type="checkbox" name="resetPassword" id="resetPassword" value="reset" onclick="setSend()"/>
        </td>
        <td nowrap align=center>
            Send Text: <input <?=$selectTxt?> type="radio" name="sendType" id="sendType" value="text" onclick="clearReset()"/>
        </td>
    </tr>
    <?php if ( $toType == "teammember"){ ?>
    <tr>
        <?php
        $qry = "select * from companies order by name";
        $companyList = $userDAO->executeQry($qry);
        ?>
        <td nowrap align='right'>Company: </td>

        <td>
            <select id="companyId" name="companyId" >
                <option value="" selected></option>
                <?php
                while($company= mysql_fetch_array($companyList))
                {
                    $select = "";
                    if ( $companyId == $company['id'])
                    $select = "selected";
                    ?>
                <option value="<?=$company['id']?>" <?=$select?> > <?=$company['name']?></option>
                <?php
            }
            ?>
            </select>
            <?php } ?>
        </td>
    </tr>
    <?php if ( $toType == "member"){ ?>
    <tr>
        <?php
        $limg = "<a href=\"javascript:popEmalName('toMail','toName','form1','tmName')\">Add Email to Mail To List</a><br>";
        ?>
        <td nowrap align='right'>Mail To: </td>
        <td><?=$limg?><input size='40' name="toMail" id="toMail" type="text" value="<?=$toMail?>" /></td>
    </tr>
    <tr>
        <td nowrap align='right'>To Name: </td>
        <td><input size='40' name="toName" id="toName" type="text" value="<?=$toName?>" /></td>
    </tr>
    <?php } ?>
    <tr>
        <?php
        $limg = "<a href=\"javascript:popEmalName('fromMail','fromName','form1','tmName')\">Add Email to Mail From List</a><br>";
        ?>

        <td nowrap align='right'>Mail From: </td>
        <td><?=$limg?><input size='40' name="fromMail" id="fromMail" type="text" value="<?=$fromMail?>" /></td>
    </tr>
    <tr>
        <td nowrap align='right'>From Name: </td>
        <td><input size='40' name="fromName" id="fromName" type="text" value="<?=$fromName?>" /></td>
    </tr>
    <tr>
        <?php
        $limg = "<a href=\"javascript:popEmalName('replyMail','replyName','form1','tmName')\">Add Email to Mail Reply List</a><br>";
        ?>
        <td nowrap align='right'>Mail Reply: </td>
        <td><?=$limg?><input size='40' name="replyMail" id="replyMail" type="text" value="<?=$replyMail?>" /></td>
    </tr>
    <tr>
        <td nowrap align='right'>Reply Name: </td>
        <td><input size='40' name="replyName" id="replyName" type="text" value="<?=$replyName?>" /></td>
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
        <td>
            Member Of Team:
        </td>
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
            <?= $tmName ?>
            <?php } ?>
        </td>
    </tr>
    <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
    <td><input type="submit" name="save" id="save" value="Send" /></td>
    </tr>
</table>
</form>
</body>
</html>