<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/awardedFormAction.php';
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
      <h1>Award Form</h1>
        <?php
        require_once('../PHP-GlobalIncludes/ProcessErrorsInclude.php');
        ?>
        <form id="userForm" name="userForm" method="post" action="awardedForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <table border="0" align="center" cellpadding="2" cellspacing="0">
                <?php if ( $formAction != "delete" ) {
                    $res = mysql_fetch_assoc($result);
                ?>
                <tr>
                    <th>
                        <?php if ( $res['awarded'] == "yes" ) { ?>
                        Award Name
                        <?php } else { ?>
                        Award Name
                        <?php } ?>
                    </th>
                    <td>
                        <input type="text" name="tmName" id="tmName" value="<?=$res['teamName']?>"/>
                    </td>
                </tr>
                <tr>
                    <th>
                        Is Awarded
                    </th>
                    <td>
                        <?php
                                $selectedNa = "selected";
                        $selectedNo = "";
                        $selectedYes = "";
                        if ( $res['awarded'] == "yes") {
                            $selectedYes = "selected";
                            $selectedNo = "";
                            $selectedNa = "";
                            } else if ( $res['awarded'] == "no") {
                                    $selectedYes = "";
                                    $selectedNo = "selected";
                                    $selectedNa = "";
                                } ?>
                        
                        <select name="awarded" id="awarded" >
                            <option value="na" <?=$selectedNa?>></option>
                            <option value="no" <?=$selectedNo?>>No</option>
                            <option value="yes" <?=$selectedYes?>>Yes</option>
                        </select>
                    </td>
                    
                </tr>
                <?php } else {
                $res = mysql_fetch_assoc($result); ?>
                <tr>
                    <th>
                        <?php if ( $res['awarded'] == "yes" ) { ?>
                        Award Name
                        <?php } else { ?>
                        Award Name
                        <?php } ?>
                    </th>
                    <td>
                        <input type="hidden" name="tmName" id="tmName" value="<?=$res['teamName']?>"/>
                        <?=$res['teamName']?>"
                    </td>
                </tr>
                <tr>
                    
                    <th>
                        Is Awarded
                    </th>
                    <td>
                        <?=$res['awarded']?>
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