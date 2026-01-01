<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/aboutTheTeamFormAction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Login Form</title>
        <link href="../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
    </head>
    <body>
        <form id="AboutTheTeamForm" name="AboutTheTeamForm" method="post" action="aboutTheTeamForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <h1>About The <?= strtoupper($teamName) ?> Team</h1>
            <table width="100%">
                <tr>
                     <td align="center">
                         <table width="98%" align="center" cellpadding="2" cellspacing="2">
                            <?php
                            $i = 0;
                            while($res = mysql_fetch_array($parentResult)){
                                if ( $i == 0 ) {
                                    ?>
                            <tr>
                                <?php } ?>
                                <td align="center">
                                    <a href="http://<?=$res['website']?>" target="_blank">
                                        <img border="0" src="/PHP-images/<?=$res['logo']?>"/></a>
                                        <br/>
                                        <?=$res['displayname']?>
                                        <?php if ( $webAdmin == "yes") { ?>
                                        <br/>
                                        Team <?= $res['teamName'] ?>
                                        <?php } ?>
                                </td>
                                <?php
                                 $i++;
                                  if ( $i == 2 ) { ?>
                            </tr>
                            <?php }
                            if ( $i == 2 )
                                $i = 0;
                        }
                         if ( $i > 0 && $i < 2 ) { ?>
                             </tr>
                         <?php } ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="98%" border="0" align="center" cellpadding="2" cellspacing="2">
                            <?php
                            $i = 0;
                            while($res = mysql_fetch_array($companyResult)){
                                if ( $i == 0 ) {
                                    ?>
                            <tr>
                                <?php } ?>
                                <td align="center">
                                    <a href="http://<?=$res['website']?>" target="_blank">
                                        <img border="0" src="/PHP-images/<?=$res['logo']?>"/></a>
                                        <br/>
                                        <?=$res['displayname']?>
                                        <?php if ( $webAdmin == "yes") { ?>
                                        <br/>
                                        Team <?= $res['teamName'] ?>
                                        <?php } ?>
                                </td>
                                <?php
                                $i++;
                                 if ( $i == 3 ) { ?>
                                      </tr>
                                      <tr><td colspan="3">&nbsp;</td></tr>
                                 <?php }
                                 if ( $i == 3 )
                                      $i = 0;
                           }
                         if ( $i > 0 && $i < 3 ) { ?>
                             </tr>
                         <?php } ?>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>