<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/contactUsFormAction.php';
?>
<html>
    <head>
        <link href="../PHP-css/loginModule.css" rel="stylesheet" type="text/css">
    </head>
    <body>
      <h1>Contact Us</h1>
      <?php while($res = mysql_fetch_array($parentResult)) {
            $profileResult = $companyProfileDAO->getCompanyProfileById($res['id']);
            $profileRes = mysql_fetch_assoc($profileResult);
            $primeResult = $primeContactsDAO->getPrimeContactsByCompanyID($res['id']);
      ?>

        <p class="style21"  align="center">
                <table>
                    <tr>
                        <td colspan="2" align="center"><font class="style100"><b><?=$res['displayname']?></b></font></td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                   <tr>
                        <td valign="top">
                            Address:
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <?=$profileRes['street']?><br>
                            <?=$profileRes['mailStop']?><br>
                            <?=$profileRes['city']?>, <?=$profileRes['state']?> <?=$profileRes['zipCode']?><br>
                            Office: <?=$profileRes['phone']?><br>
                            Fax: <?=$profileRes['fax']?><br>
                            <a href="http://<?=$res['website']?>/" target="_blank"><?=$res['website']?></a>
                        </td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td valign="top">
                        Contacts:
                    </td>
                    <td>&nbsp;</td>
                </tr>
      <?php while($primeRes = mysql_fetch_array($primeResult)) {  
                $userResult = $userDAO->getUserById($primeRes['userId']);
                $userRes = mysql_fetch_assoc($userResult);
                $profileResult = $userProfileDAO->getUserProfileByUserId($primeRes['userId']);
                $profileRes = mysql_fetch_assoc($profileResult);?>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <a href="mailto:<?=$profileRes['email']?>?subject=ITS-SB"><?=$userRes['firstname']?> <?=$userRes['lastname']?></a>
                        <br />
                        <?=$primeRes['title']?><br />
                    </td>
                </tr>
                <?php } ?>
            </table>
        </p>
        <?php } ?>
    </body>
</html>