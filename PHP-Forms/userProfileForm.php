<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/userProfileFormAction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>My Profile</title>
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
    </head>
    <body>
        <h1>User Profile Form</h1>
        <?php
        require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/ProcessErrorsInclude.php';
        ?>
        <form id="loginForm" name="loginForm" method="post" action="userProfileForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>"/>
            <input type="hidden" name="userId" id="userId" value="<?= $userId ?>"/>
            <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
            <input type="hidden" name="postForm" id="postForm" value="yes"/>
            <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                    <th>Name </th>
                    <td><?=$name?></td>
                </tr>
                <tr>
                    <th>Email </th>
                    <td><input name="email" type="text" class="textfield" id="email" value="<?= $email ?>" /></td>
                </tr>
                <tr>
                    <th>Phone </th>
                    <td><input name="phone" type="text" class="textfield" id="phone" value="<?= $phone ?>" /></td>
                </tr>
                <tr>
                    <th>POC Type </th>
                    <td>
                    <?php
                        $selected0 = "";
                        $selected1 = "";
                        $selected2 = "";
                        $selected99 = "";
                        switch ($pocType){
                        case "0":
                        $selected0="selected";
                        break;
                        case "1":
                        $selected1="selected";
                        break;
                        case "2":
                        $selected2="selected";
                        break;
                        default:
                        $selected99="selected";
                        break;
                        }?>
                        <?php if ( $teamManager == 'yes' ) { ?>
                        <select name="pocType" id="pocType">
                                <option value="99" <?= $selected99 ?>></option>
                                    <option value="0" <?= $selected0 ?>>Contracts/Prices</option>
                                <option value="1" <?= $selected1 ?>>Technical</option>
                                    <option value="2" <?= $selected2 ?>>Contracts/Prices/Technical</option>
                        </select>
                        <?php } else {
                            switch ($pocType){
                                case "0":
                                $selected="Contracts/Prices";
                                break;
                                case "1":
                                $selected="Technical";
                                break;
                                case "2":
                                $selected="Contracts/Prices/Technical";
                                break;
                                default:
                                $selected="";
                                break;
                                }
                            echo $selected;
                        } ?>
                    </td>
                </tr>
                <tr>
                    <th>Company</th>
                    <td>
                    <?php if ( $webAdmin == "yes" || $companyId == "" ) { ?>
                        <select name="companyId" id="companyId">
                            <option value="" selected></option>
                            <?php
                                while($res = mysql_fetch_array($companyresult))
                                    {
                                        $selected = "";
                                        if ( $res['id'] == $companyId )
                                                $selected =  "selected";
                                        $name = $res['name'];
                                        if ( $webAdmin == "yes") {
                                                $name .= " of ".$res['teamName'];
                                            } ?>
                                            <option value="<?= $res['id'] ?>" <?= $selected ?> ><?= $name ?></option>;
                                            <?php } ?>
                                            <?php } else { ?>
                                <input type="hidden" name="companyId" id="companyId" value="<?= $companyId ?>"/>
                                    <?= $companyName ?>
                                    <?php } ?>
                        </select>
                    </td>
                </tr>
                <?php if ( $webAdmin == "yes" ) { 
                    $selectNo = "checked";
                    $selectYes = "";
                    if ($primeContact == "yes") {
                            $selectNo = "";
                            $selectYes = "checked";
                        }
                ?>
                <tr>
                    <th>
                        Prime Contact
                    </th>
                    <td>
                    
                        Yes: <input type="radio" name="primeContact" id="primeContact" value="yes" <?=$selectYes?>/><br>
                            &nbsp;No: <input type="radio" name="primeContact" id="primeContact" value="" <?=$selectNo?>/>
                                     </td>
                </tr>
                <?php
                    $result = $primeContactsDAO->getPrimeContactsByUserID($userId);
                    $primeRes = mysql_fetch_assoc($result);
                ?>
                <tr>
                    <th>
                        Title
                    </th>
                    <td>
                            <input size="30" type="text" name="title" id="title" value="<?=$primeRes['title']?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Sort Order 
                            </th>
                            <td>
                                <input maxlength="2" size="2" type="text" name="sortinfo" id="sortinfo" value="<?=$primeRes['sortinfo']?>"/> ( Enter Number where 1 is first )
                    </td>
                </tr>
                <?php } else { ?>
                <input type="hidden" name="primeContact" id="primeContact" value="<?=$primeContact?>"/>
                <?php } ?>
                <tr>
                <?php if ( $teamManager == "yes") {?>
                    <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
                    <?php } else { ?>
                    <td><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                    <?php } ?>
                    <td><input type="submit" name="save" id="save" value="Save" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>