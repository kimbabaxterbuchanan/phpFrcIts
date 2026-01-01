<?php
session_start();
require_once dirname(__FILE__) . '/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) . '/../PHP-Actions/listAwardedFormAction.php';
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
        <form id="listAwardForm" name="listAwardForm" method="post" action="listAwardForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <h1>List Awards</h1>
            <table>
                <?php if ( $webAdmin == "yes" ) { ?>
                <tr>
                    <td>
                        <a href="awardedForm.php?section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=">Create New Award</a>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <table width="500" border="1" align="center" cellpadding="2" cellspacing="0">
                            <tr>
                                <th>
                                    Award Name
                                </th>
                                <th>
                                    Awarded
                                </th>
                                <th>
                                    <table>
                                        <tr>
                                            <th>
                                                Edit
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                Info
                                            </th>
                                        </tr>
                                    </table>
                                </th>
                                <?php if ( $webAdmin == "yes" ) { ?>
                                <th>
                                    Delete
                                </th>
                                <? } ?>
                            </tr>
                            <?php while($res = mysql_fetch_array($result)){  ?>
                            <tr>
                                <td>
                                    <?=$res['teamName']?>
                                </td>
                                <td>
                                    <?=$res['awarded']?>
                                </td>
                                <td align="center">
                                    <table>
                                        <tr>
                                            <td align="center">
                                                <a href="awardedForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <?php if ( $webAdmin == "yes" ) { ?>
                                <td>
                                    <a href="awardedForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=delete">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        <?php if ( $section == "config" ) { ?>
                        <tr>
                            <td colspan="8" align="center"><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                        </tr>
                        <?php } ?>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>