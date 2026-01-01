<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/userFormAction.php';
$userNum = "";
$fname = "";
$lname = "";
$login = "";
$password = "";
$cpassword = "";
$tmManager = "";
$wbAdmin = "";
$tmName = "";
if ( $id != "") {
    $res = mysql_fetch_assoc($result);
    $id = $res['id'];
    $userNum = $res['userNum'];
    $fname = $res['firstname'];
    $lname = $res['lastname'];
    $login = $res['login'];
    $password = $res['passwd'];
    $cpassword = $password;
    $tmManager = $res['teamManager'];
    $wbAdmin = $res['webAdmin'];
    $tmName = $res['teamName'];
} else {
    $result = $userDAO->getAllUsers();
    $res = mysql_fetch_array($result);
    $userNum = mysql_num_rows($result)+1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Login Form</title>
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
        <script language="Javascript" type="text/javascript">
            function changeAccess(cname, fname) {
                cobj = document.forms['userForm'].elements[cname];
                fobj = document.forms['userForm'].elements[fname];
                if ( cobj[0].checked == false && cobj[1].checked == false )
                    cobj[1].checked = true;
                if ( cobj[0].name == "wbAdmin" ) {
                    if ( cobj[0].checked == true ) {
                        fobj[0].checked = true;
                    }
                } else {
                if ( fobj[0].checked == true && cobj[0].checked == false ) {
                    cobj[0].checked = true;
                }
            }
        }
        </script>
    </head>
    <body>
      <h1>User Form</h1>
        <?php
        require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/ProcessErrorsInclude.php';
        ?>
        <form id="userForm" name="userForm" method="post" action="userForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
            <input type="hidden" name="postForm" id="postForm" value="yes"/>
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                <?php if ( $teamManager == "yes" && $formAction != "delete") { ?>
                <tr>
                    <th>User Number</th>
                    <td><input name="userNum" type="hidden" class="textfield" id="userNum"  value="<?= $userNum ?>"/>
                    <?= $userNum ?>
                    </td>
                </tr>
                <tr>
                    <th>First Name </th>
                    <td><input name="fname" type="text" class="textfield" id="fname"  value="<?=$fname?>"/></td>
                </tr>
                <tr>
                    <th>Last Name </th>
                    <td><input name="lname" type="text" class="textfield" id="lname"  value="<?=$lname?>"/></td>
                </tr>
                <tr>
                    <th width="124">Login</th>
                    <td width="168"><input name="login" type="text" class="textfield" id="login"  value="<?=$login?>"/></td>
                </tr>
                <?php } else { ?>
                <input name="userNum" type="hidden" id="userNum"  value="<?= $userNum ?>"/>
                <input name="fname" type="hidden" id="fname"  value="<?=$fname?>"/>
                <input name="lname" type="hidden" id="lname"  value="<?=$lname?>"/>
                <input name="login" type="hidden" id="login"  value="<?=$login?>"/>
                <?php if ( $formAction != "delete") { ?>
                <tr>
                    <td align="left">
                        <a href="userProfileForm.php?id=<?=$id?>&section=<?=$section?>&sub_section=<?=$sub_section?>&formAction=edit">Edit Profile</a>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <th&nbsp;th>
                    <td>&nbsp;</td>
                </tr>
                <?php } ?>
                <tr>
                    <th>User Id </th>
                    <td><?= $userId ?> </td>
                </tr>
                <tr>
                    <th>First Name </th>
                    <td><?=$fname?></td>
                </tr>
                <tr>
                    <th>Last Name </th>
                    <td><?=$lname?></td>
                </tr>
                <tr>
                    <th width="124">Login</th>
                    <td width="168"><?=$login?></td>
                </tr>
                <?php }
                if ( $formAction != "delete" ) { ?>
                <tr>
                    <th>Password</th>
                    <input type="hidden" name="orgPassword" id="orgPassword" value="<?=$password?>" />
                    <td><input name="password" type="password" class="textfield" id="password" /></td>
                </tr>
                <tr>
                    <th>Confirm Password </th>
                    <input type="hidden" name="orgPassword" id="orgCPassword" value="<?=$cpassword?>" />
                    <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                </tr>
                <?php } ?>

                <?php if ( $teamManager == "yes" && $formAction != "delete") {?>
                <tr>
                    <th valign="top">Manager Type </th>
                    <td>
                    <?php if ( $webAdmin == "yes" ) {
                                $wb1Yes = "";
                                $wb2Yes = "checked";
                                if ( $wbAdmin == "yes" ) {
                                        $wb1Yes = "checked";
                                        $wb2Yes = "";
                                    }
                            ?>
                    Web Admin <br> Yes: <input type="radio" name="wbAdmin" id="wbAdmin" value="yes"  onclick="changeAccess('wbAdmin','tmManager');" <?=$wb1Yes?>/>
                                               No: <input type="radio" name="wbAdmin" id="wbAdmin" value="no"  onclick="changeAccess('wbAdmin','tmManager');" <?=$wb2Yes?>>
                                               <br><br>
                    <?php }
                if ( $teamManager == "yes" ) {
                        $tm1Yes = "";
                        $tm2Yes = "checked";
                        if ( $tmManager == "yes" ) {
                                $tm1Yes = "checked";
                                $tm2Yes = "";
                            } ?>
                    Team Manager <br>
                    Yes: <input type="radio" name="tmManager" id="tmManager" value="yes" onclick="changeAccess('tmManager','wbAdmin');" <?=$tm1Yes?>>
                                No: <input type="radio" name="tmManager" id="tmManager" value="no" onclick="changeAccess('tmManager','wbAdmin');" <?=$tm2Yes?>>
                                </td>
                </tr>
                <tr>
                    <td>
                        Member Of Team:
                    </td>
                    <td>
                        <?php if ( $webAdmin == "yes" ) { ?>
                        <select name="tmName" id="tmName" >
                            <option value="All" selected></option>
                            <?php while($res = mysql_fetch_array($teamResult)) {
                                                $selected = "";
                                                if ( $res['teamName'] == $tmName )
                                                        $selected = "selected";
                                            ?>
                            <option value="<?= $res['teamName'] ?>" <?=$selected?> ><?= $res['teamName'] ?></option>
                            <?php } ?>
                        </select>
                        <?php } else { ?>
                        <input type="hidden" name="wbAdmin" id="wbAdmin" value="<?=$wbAdmin?>"/>
                        <input type="hidden" name="tmName" id="tmName" value="<?= $teamName ?>"/>
                        <?= $teamName ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <th>
                        Member Of Team:
                    </th>
                    <td>
                        <input type="hidden" name="tmName" id="tmName" value="<?= $tmName ?>"/>
                        <?=$teamName?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <?php if ( $teamManager == "yes") {?>
                    <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
                    <?php } else { ?>
                    <td><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                    <?php }
                    if (  $formAction != "delete" ) {?>
                    <td><input type="submit" name="save" id="save" value="Save" /></td>
                    <?php } else { ?>
                    <td><input type="submit" name="delete" id="delete" value="Delete" /></td>
                    <?php } ?>
                </tr>
            </table>
        </form>
    </body>
</html>