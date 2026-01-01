<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/userFormAction.php';
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
         <form id="userListForm" name="userListForm" method="post" action="userListForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <h1>List Users</h1>
            <table>
                <tr>
                    <td>
                        <a href="userForm.php?section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=">Create New User</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="500" border="1" align="center" cellpadding="2" cellspacing="0">
                            <tr>
                                <th>
                                    FirstName
                                </th>
                                <th>
                                    LastName
                                </th>
                                <th>
                                    LoginName
                                </th>
                                <?php if ( $webAdmin == "yes") { ?>
                                <td>
                                    Team
                                </td>
                                <?php } ?>
                                <?php if ( $webAdmin == "yes" ) { ?>
                                <th>
                                    Web Admin
                                </th>
                                <?php } ?>
                                <?php if ( $teamManager == "yes" ) { ?>
                                <th>
                                    Team Manager
                                </th>
                                <?php } ?>
                                <th valign="center">
                                    Edit
                                    <table>
                                        <tr>
                                            <th>
                                                Info&nbsp;|&nbsp;Profile
                                            </th>
                                        </tr>
                                    </table>
                                </th>
                                <th>
                                    Delete
                                </th>
                            </tr>
                            <?php

                            while($res = mysql_fetch_array($result))
                            {
                                $displayInList = true;
                                if ( $webAdmin == "no" && $wbAdmin[$cnt] == "yes" ){
                                        $displayInList = false;
                                    }
                                if ( $teamManager == "no" && $tmManager[$cnt] == "yes" ){
                                        $displayInList = false;
                                    }
                                if ( $displayInList == true ) {
                                    ?>
                            <tr>
                               <td>
                                    <?=$res['firstname']?>
                                </td>
                                <td>
                                    <?=$res['lastname']?>
                                </td>
                                <td>
                                    <?=$res['login']?>
                                </td>
                                <?php if ( $webAdmin == "yes") { ?>
                                <td>
                                    <?= $res['teamName'] ?>
                                </td>
                                <?php } ?>
                                <?php if ( $webAdmin == "yes" ) { ?>
                                <td>
                                    <?=$res['webAdmin']?>
                                </td>
                                <?php } ?>
                                <?php if ( $teamManager == "yes" ) { ?>
                                <td>
                                    <?=$res['teamManager']?>
                                </td>
                                <?php } ?>
                                <td>
                                    <table>
                                        <tr>
                                            <td>
                                                <a href="userForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit</a>
                                                &nbsp;|&nbsp;
                                                <a href="userProfileForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <?php if ( $res['login'] != "admin" ) {  ?>
                                    <td>
                                        <a href="userForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=delete">Delete</a>
                                    </td>
                                <?php } else {  ?>
                                    <td>&nbsp;</td>
                                <?php } ?>
                            </tr>
                            <?php
                                    }
                            }
                    ?>
                            <tr>
                                <td colspan="9" align="center"><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <form>
    </body>
</html>