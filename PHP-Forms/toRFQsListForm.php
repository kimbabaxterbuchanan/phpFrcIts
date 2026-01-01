<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/toRFQsListFormAction.php';
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
        <form id="toRFQsListForm" name="toRFQsListForm" method="post" action="toRFQsListForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <h1>List To RFQs</h1>
            <table>
            <?php if ( $section == "config" ) { ?>
                <tr>
                    <td>
                        <a href="toRFQsForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=">Create New ToRFQ</a>
                    </td>
                </tr>
            <?php } ?>
                <tr>
                    <td>
                        <table width="500" border="1" cellpadding="2" cellspacing="0">
                            <tr>
                                <th>
                                    Document Number
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Posted
                                </th>
                                <th>
                                    Due
                                </th>
                                <?php if ( $webAdmin == "yes") { ?>
                                <th>
                                    Team
                                </th>
                                <?php } ?>
                                <?php if ( $section == "config" ) { ?>
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
                                <th>
                                    Delete
                                </th>
                                <?php } ?>
                            </tr>
                            <?php
                            $cnt = 0;
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
                                <?php if ( $section != "config" ) { ?>
                                         <a href="toRFQsForm.php?id=<?=$res['id']?>&docNumber=<?=$res['docNumber']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=view"><?=$res['docNumber']?></a>
                                <?php } else { ?>
                                         <?=$res['docNumber']?>
                                <?php } ?>
                                </td>
                                <td>
                                    <?=$res['title']?>
                                </td>
                                <td>
                                    <?=mysqlDateToPhpDate($res['posted'])?>
                                </td>
                                <td>
                                    <?=mysqlDateToPhpDate($res['finalDue_dt'])?>
                                </td>
                                <?php if ( $webAdmin == "yes") { ?>
                                <td>
                                    <?= $res['teamName'] ?>
                                </td>
                                <?php } ?>
                                <?php if ( $section == "config" ) { ?>
                                <td align="center">
                                    <table>
                                        <tr>
                                            <td align="center">
                                                <a href="toRFQsForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <a href="toRFQsForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=delete">Delete</a>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php
                            }
                            $cnt++;
                            }
                            ?>
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