<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/companyProfileFormAction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>My Profile</title>
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
    </head>
    <body>
      <h1>Company Profile Form</h1>
    <?php
        require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/ProcessErrorsInclude.php';
        ?>
        <form id="loginForm" name="loginForm" method="post" action="companyProfileForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            
            <input type="hidden" name="companyId" id="companyId" value="<?=$companyId?>"/>
            <input type="hidden" name="companyName" id="companyName" value="<?=$companyName?>"/>
            <input type="hidden" name="rtnPage" id="rtnPage" value="<?=$rtnPage?>"/>
            <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                    <th>Company </th>
                    <td><?=$companyName?></td>
                </tr>
                <tr>
                    <th>Street </th>
                    <td><input name="street" type="text" class="textfield" id="street" value="<?=$street?>" /></td>
                </tr>
                <tr>
                    <th>MailStop </th>
                    <td><input name="mailStop" type="text" class="textfield" id="mailStop" value="<?=$mailStop?>" /></td>
                </tr>
                <tr>
                    <th width="124">City</th>
                    <td width="168"><input name="city" type="text" class="textfield" id="city" value="<?=$city?>" /></td>
                </tr>
                <tr>
                    <th>State</th>
                    <td><input name="state" type="text" class="textfield" id="state" value="<?=$state?>" /></td>
                </tr>
                <tr>
                    <th>Zip Code </th>
                    <td><input name="zipCode" type="text" class="textfield" id="zipCode" value="<?=$zipCode?>" /></td>
                </tr>
                <tr>
                    <th>Email </th>
                    <td><input name="email" type="text" class="textfield" id="email" value="<?=$email?>" /></td>
                </tr>
                <tr>
                    <th>Phone </th>
                    <td><input name="phone" type="text" class="textfield" id="phone" value="<?=$phone?>" /></td>
                </tr>
                <tr>
                    <th>Fax </th>
                    <td><input name="fax" type="text" class="textfield" id="fax" value="<?=$fax?>" /></td>
                </tr>
                <tr>
                    <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
                    <td><input type="submit" name="save" id="save" value="Save" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>