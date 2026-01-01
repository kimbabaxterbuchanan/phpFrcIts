<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/forgotPasswordFormAction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h1>Forgotten Password Form</h1>
        <?php
        require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/ProcessErrorsInclude.php';
        ?>
        <form id="fogottenPasswordForm" name="fogottenPasswordForm" method="post" action="forgotPasswordForm.php">
            <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
            <input type="hidden" name="postForm" id="postForm" value="yes"/>
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
            
                <tr>
                    <th>Login Id</th>
                    <td><input name="login" type="text" class="textfield" id="login" value="" /></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><input name="email" type="text" class="textfield" id="email" value="" /></td>
                </tr>
                <tr>
                    <td><input type="submit" name="forgotten" id="forgotten" value="Get Password" /></td>
                </tr>
                
            </table>
        </form>
    </body>
</html>
