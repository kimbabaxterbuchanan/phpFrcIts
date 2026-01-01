<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/notificationListFormAction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Notification List Form</title>
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
    </head>
    <body>
         <form id="notificationListForm" name="notificationListForm" method="post" action="notificationListForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <h1>List Email Notifications</h1>
            <table>
                <tr>
                    <td>
                        <table width="500" border="1" align="center" cellpadding="2" cellspacing="0">
                            <tr>
                                <th>
                                    Mail To
                                </th>
                                <th>
                                    Subject
                                </th>
                                <th>
                                    Date
                                </th>
                                <?php if ( $webAdmin == "yes") { ?>
                                <td>
                                    Team
                                </td>
                                <?php } ?>
                                <th valign="center">
                                    View
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
                                <?php 
                                if ( strlen(trim($res['toMail'])) < '41') {
                                ?>
                                    <td nowrap>
                                        &nbsp;<?=$res['toMail']?>
                                    </td>
                                <?php
                                } else {
                                    $hght = ( strlen(trim($res['toMail'])) / 41 );
                                ?>
                                    <td height="<?=$hght?>" char=";">
                                        &nbsp;<?=$res['toMail']?>
                                    </td>
                                <?php
                                }
                                ?>
                                <?php 
                                if ( strlen(trim($res['message'])) < '25') {
                                ?>
                                    <td nowrap>
                                        &nbsp;<?=$res['message']?>
                                    </td>
                                <?php
                                } else {
                                    $hght = ( strlen(trim($res['message'])) / 25 );
                                ?>
                                    <td height="<?=$hght?>" char=" ">
                                        &nbsp;<?=$res['message']?>
                                    </td>
                                <?php
                                }
                                ?>
                                <td nowrap>
                                    &nbsp;<?=$res['create_dt']?>
                                </td>
                                <?php if ( $webAdmin == "yes") { ?>
                                <td nowrap>
                                    &nbsp;<?= $res['teamName'] ?>
                                </td>
                                <?php } ?>
                                <td>
                                    <a href="notificationForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=view">View</a>
                                </td>
                                <?php if ( $res['login'] != "admin" ) {  ?>
                                    <td>
                                        <a href="notificationForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=delete">Delete</a>
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
                                <td colspan="6" align="center"><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>