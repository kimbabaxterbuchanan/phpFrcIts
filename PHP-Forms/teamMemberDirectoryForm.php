<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/teamMemberDirectoryFormAction.php';
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
        <form id="teamMemberDirectoryForm" name="teamMemberDirectoryForm" method="post" action="teamMemberDirectoryForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>" />
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>" />
            <input type="hidden" name="postForm" id="postForm" value="yes" />
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <h1><?= strtoupper($teamName) ?> Team Member Directory</h1>
            <table border="1" width="85%">
                <tr>
                    <th align="center" width="25%">
                        <Strong>Name</Strong>
                    </th>
                    <th align="center" width="25%">
                        <Strong>Phone #</Strong>
                    </th>
                    <th align="center" width="40%">
                        <Strong>Email</Strong>
                    </th>
                    <th align="center" width="10%">
                        <Strong>POC Type</Strong>
                    </th>
                </tr>
                <?php while($company = mysql_fetch_array($companyResult)) { ?>
                <tr>
                    <td colspan="4" nowrap class="style100a">
                        <table width="100%" class="style100a">
                            <tr>
                                <td width="50%" class="style100a" nowrap>
                                        <font class="style100a"><?= $company['displayname']?></font>
                                        </td>
                                            <?php if ( $webAdmin == "yes") { ?>
                                <td align="right" width="30%" class="style100a" nowrap>
                                        Team  <?= $company['teamName'] ?>
                                        </td>
                                        <?php } else { ?>
                                <td align="right" width="30%" class="style100a" nowrap>
                                    &nbsp;
                                </td>
                                <?php } ?>
                                <td width="20%" class="style100a" nowrap>
                                    &nbsp;
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                $id = $company['id'];
                $contactProfileResult = $userProfileDAO->getUserProfileByCompanyIdNotPocType($id);
                ?>

                <?php while($contactProfile = mysql_fetch_array($contactProfileResult)){
                    $id = $contactProfile['userId'];
                    $contactResult = $userDAO->getUserById($id);
                    $contact = mysql_fetch_assoc($contactResult);
                    $pocType = "";
                    switch ($contactProfile['pocType']){
                        case "0":
                        $pocType="Contracts/Pricing";
                        break;
                        case "1":
                        $pocType="Technical";
                        break;
                        case "2":
                        $pocType="Contracts/Pricing/Technical";
                        break;
                        default:
                            $pocType="";
                        }
                ?>
                <tr>
                        <td nowrap width="20%"><?= $contact['firstname']." ".$contact['lastname'] ?></td>
                            <td nowrap width="20%"><?= $contactProfile['phone'] ?></td>
                    <td nowrap width="40%"><a href="mailto:<?= $contactProfile['email'] ?>"><?= $contactProfile['email'] ?></a></td>
                    <td nowrap width="20%"><?= $pocType ?></td>
                </tr>
                <?php     } ?>
                <tr>
                    <td height="2" colspan="4" nowrap>&nbsp;</td>
                </tr>
                <?php }; ?>
            </table>
        </form>
    </body>
</html>