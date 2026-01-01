<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
if ( $isLogedIn != "yes"  ) {
    if ( strtolower($section) != "home" && strtolower($section) != "abouttheteam" && strtolower($section) != "contactus" && strtolower($section) != "login" ) {
        $section = "home";
    }
}
?>
<html>
    <head>
        <link href="../PHP-css/gmail.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <span width="100%" class="style1">
        <?php if ( isset($section) && $section != "" && $section != "home" ) {
            switch ($section){
                case "config":
                switch ($sub_section){
                case "listAwards": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/awardedListForm.php?section=config&sub_section=listAwards" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listCompany": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/companyListForm.php?section=config&sub_section=listCompany" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listUser": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/userListForm.php?section=config&sub_section=listUser" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listProposal": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/proposalsListForm.php?section=config&sub_section=listProposal" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listToRFQs": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/toRFQsListForm.php?section=config&sub_section=listToRFQs" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listToAwards": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/toAwardsListForm.php?section=config&sub_section=listToAwards" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listEmail": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/emailListForm.php?section=config&sub_section=listEmail" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listNotification": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/notificationListForm.php?section=config&sub_section=listNotification" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listReport": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/reportListForm.php?section=config&sub_section=listReport" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "listTableLinks": ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-Forms/tableLinkListForm.php?section=config&sub_section=listTableLinks" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "admin": ?>
        <iframe bgcolor="#000000" class="style1" width="99%" height="1045" src="/PHP-FileManager/adminfilemanager.php?section=config&sub_section=admin" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        case "masterfilemanager": ?>
        <iframe bgcolor="#000000" class="style1" width="99%" height="1045" src="/masterfilemanager.php?section=config&sub_section=admin" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php break; // Empty Frame
        Default:?>
            <table align=center border='1'>
                <tr>
                    <td colspan=3 align="left" valign='bottom'> <b> Primary Steps </b> </td>
                </tr>
                <tr>
                    <?php $stpCnt = 1;
                        if ( $webAdmin == "yes" || $isAwarded != "na") { ?>
                        <td><a href="TeamFRC.php?section=config&sub_section=listAwards">List Current Awards</a> <san class="style50"> (New Team Step <?=$stpCnt++?>) </span>&nbsp;&nbsp;&nbsp;</td>
                    <?php } else {?>
                        <td>&nbsp;</td>
                    <?php } ?>
                    <td><a href="TeamFRC.php?section=config&sub_section=listCompany">List Current Companies</a>  <span class="style50">(New Team Step <?=$stpCnt++?>) </span>&nbsp;&nbsp;&nbsp;</td>
                    <td><a href="TeamFRC.php?section=config&sub_section=listUser">List Current Users</a>  <span class="style50">(New Team Step <?=$stpCnt++?>) </span>&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr><td colspan=3 align="left">&nbsp;</td></tr>
                <tr>
                <td colspan=3 align="left"><b>Contracts</b></td>
                </tr>
                <tr>
                <?php if (  $webAdmin == "yes" || $isAwarded == "no" ) { ?>
                        <td><a href="TeamFRC.php?section=config&sub_section=listProposal">List Current Proposals</a></td>
                <?php } else {
                        echo "<td>&nbsp;</td>";
                      }
                      if (  $webAdmin == "yes" || $isAwarded == "yes" ) { ?>
                        <td><a href="TeamFRC.php?section=config&sub_section=listToRFQs">List Current To RFQs</a></td>
                        <td><a href="TeamFRC.php?section=config&sub_section=listToAwards">List Current ToAwards</a></td>
                <?php } ?>
                </tr>
                <tr><td colspan=3 align="left">&nbsp;</td></tr>
                <tr>
                <td colspan=3 align="left"><b>File & Table Maintenance</b></td>
                </tr>
                 <tr>
                <?php if (  $webAdmin == "yes" || $isAwarded == "yes" ) { ?>
                    <td><a href="TeamFRC.php?section=config&sub_section=admin">File Content Manager</a></td>
                     <?php if (  $webAdmin == "yes" ) { ?>
                        <td><a href="TeamFRC.php?section=config&sub_section=masterfilemanager" target="_blank">Master File Content Manager</a></td>
                        <td><a href="TeamFRC.php?section=config&sub_section=listTableLinks">List table Links Manager</a></td>
                    <?php } else { ?>
                        <td colspan=2>&nbsp;</td>
                    <?php } ?>
                <?php } else {?>
                    <td colspan=3 align="left"><a href="TeamFRC.php?section=config&sub_section=admin">File Content Manager</a></td>
                <?php } ?>
                </tr>
                <tr><td colspan=3 align="left"3>&nbsp;</td></tr>
                <tr>
                    <td colspan=3 align="left"><b>Notifications and Reports</b></td>
                </tr>
                <tr>
                    <td><a href="TeamFRC.php?section=config&sub_section=listEmail">List Current Emails</a>  <span class="style50"> </span></td>
                    <td><a href="TeamFRC.php?section=config&sub_section=listNotification">List Current Notificationss</a>  <span class="style50"> </span></td>
                    <td><a href="TeamFRC.php?section=config&sub_section=listReport">List Current Reports</a>  <span class="style50"> </span></td>
                </tr>
            </table>
        <?php } ?>
        <?php break; // Empty Frame
        default: ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-FileIncludes/TeamFRC<?=$section?>.php" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php   } ?>
        <?php } else { ?>
        <iframe bgcolor="#000000" class="style1" width="100%" height="100%" src="/PHP-FileIncludes/TeamFRCHomeText.html" name="frame3" border="0" marginwidth="0" marginheight="0"/>
        <?php } ?>
        </span>
    </body>
</html>