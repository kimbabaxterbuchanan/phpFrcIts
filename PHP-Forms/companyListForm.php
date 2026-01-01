<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/companyFormAction.php';
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
            <h1>List Companies</h1>
            <table>
                <tr>
                    <td>
                        <a href="companyForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=">Create New Company</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="500" border="1" cellpadding="2" cellspacing="0">
                            <tr>
                                <th>
                                    Name
                                </th>
                                <?php if ( $webAdmin == "yes") { ?>
                                    <th>
                                        Team
                                    </th>
                                <?php } ?>
                                <th>
                                    <table>
                                        <tr>
                                            <th>
                                                Edit
                                            </th>
                                        </tr>
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
                                <?=$res['displayname']?>
                                </td>
                                 <?php if ( $webAdmin == "yes") { ?>
                                    <td>
                                        <?= $res['teamName'] ?>
                                    </td>
                                 <?php } ?>
                                 <td align="center">
                                    <table>
                                        <tr>
                                            <td align="center">
                                                <a href="companyForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit</a>
                                                &nbsp;|&nbsp;
                                                <a href="companyProfileForm.php?companyId=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                <?php if ( ( $webAdmin == "no" && $res['teamName'] != "All") || ( $webAdmin == "yes") ) { ?>
                                    <a href="companyForm.php?id=<?=$res['id']?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=delete">Delete</a>
                                 <?php } else { ?>
                                   &nbsp;
                                 <?php } ?>
                                </td>
                            </tr>
                            <?php
                            }
                            $cnt++;
                            }
                            ?>
                            <tr>
                                <td colspan="5" align="center"><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>