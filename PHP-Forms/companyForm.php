<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/companyFormAction.php';
$companyId = "";
$name = "";
$logo = "";
$displayname = "";
$website = "";
if ( $id != "" ) {
    $res = mysql_fetch_assoc($result);
    $id = $res['id'];
    $companyNum = $res['companyNum'];
    $name = $res['name'];
    $logo = $res['logo'];
    $displayname = $res['displayname'];
    $website = $res['website'];
    $tmName = $res['teamName'];
} else {
    $result = $companyDAO->getAllCompanies();
    $res = mysql_fetch_array($result);
    $companyNum = mysql_num_rows($result)+1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Login Form</title>
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
    </head>
    <body>
      <h1>Company Form</h1>
    <?php
        require_once('../PHP-GlobalIncludes/ProcessErrorsInclude.php');
        ?>
        <form id="loginForm" name="loginForm" method="post" action="companyForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                <?php if (  $formAction != "delete" ) {?>
                <tr>
                    <th>Company Number </th>
                    <td>
                    <?php if ( $webAdmin == "yes" ) { ?>
                        <input name="companyNum" type="text" class="textfield" id="companyNum"  value="<?= $companyNum ?>"/>
                    <?php } else { ?>
                        <input name="companyNum" type="hidden" class="textfield" id="companyNum"  value="<?= $companyNum ?>"/>
                        <?= $companyNum ?>
                    <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>Company Name </th>
                    <td><input name="name" type="text" class="textfield" id="name"  value="<?= $name ?>"/></td>
                </tr>
                <tr>
                    <th>Company Logo </th>
                    <td><input name="logo" type="text" class="textfield" id="logo"  value="<?= $logo ?>"/></td>
                </tr>
                <tr>
                    <th>Company Display Name </th>
                    <td><input name="displayname" type="text" class="textfield" id="displayname"  value="<?= $displayname ?>"/></td>
                </tr>
                <tr>
                    <th>Company Website </th>
                    <td><input name="website" type="text" class="textfield" id="website"  value="<?= $website ?>"/></td>
                </tr>
                <tr>
                    <td>
                        Member Of Team:
                    </td>
                    <td>
                    <?php if ( $webAdmin == "yes" ) { ?>
                        <select name="tmName" id="tmName" >
                            <option value="" selected></option>
                            <?php $selected = "";
                                  if ( $res['teamName'] == $tmName )
                                                $selected = "selected";
                                    ?>
                            <option value="All" <?=$selected?> >All</option>
                            <?php while($res = mysql_fetch_array($teamResult))
                                    {
                                        $selected = "";
                                        if ( $res['teamName'] == $tmName )
                                                $selected = "selected";
                                    ?>
                                            <option value="<?= $res['teamName'] ?>" <?=$selected?> ><?= $res['teamName'] ?></option>
                            <?php } ?>
                        </select>
                        <?php } else { ?>
                            <input type="hidden" name="tmName" id="tmName" value="<?=$res['teamName']?>"/>
                            <?=$teamName?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } else { ?>
                <tr>
                    <th>Company Number </th>
                    <td><?= $companyNum ?></td>
                </tr>
                <tr>
                    <input type="hidden" name="name" id="name" value="<?= $name ?>"/>
                    <th>Company Name </th>
                    <td><?= $name ?></td>
                </tr>
                <tr>
                    <th>Company Logo </th>
                    <td><?= $logo ?></td>
                </tr>
                <tr>
                    <th>Company Display Name </th>
                    <td><?= $displayname ?></td>
                </tr>
                <tr>
                    <th>Company Website </th>
                    <td><?= $website ?></td>
                </tr>
                <tr>
                    <td>
                        Member Of Team:
                    </td>
                    <td>
                        <input type="hidden" name="tmName" id="tmName" value="<?=$res['teamName']?>"/>
                        <?=$teamName?>
                    </td>
                 </tr>
                <?php } ?>
                <tr>
                    <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
                    <?php if (  $formAction != "delete" ) {?>
                    <td><input type="submit" name="save" id="save" value="Save" /></td>
                    <?php } else { ?>
                    <td><input type="submit" name="delete" id="delete" value="Delete" /></td>
                    <?php } ?>
                </tr>
            </table>
        </form>
    </body>
</html>