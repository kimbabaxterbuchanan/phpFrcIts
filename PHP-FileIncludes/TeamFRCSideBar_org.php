<?php
require_once dirname(__FILE__) . '/../PHP-GlobalIncludes/auth.php';

$norm_bgcolor = "BA1303";
$sel_bgcolor = "801303";
$bgcolor= "BA1303";
?>

<table width="198" height="403" border="0" cellpadding="5">

    <!-- ?php if ( $webAdmin == "yes" ) { ? >
        <tr>
            <td height="21" bgcolor="#7E130D" nowrap>
                <div align="center" class="style31">
                    <a href="TeamFRC.php?section=SelectTeam">Select Team</a>
                </div>
            </td>
        </tr>
    < ?php } ? -->
    <tr>
        <td height="21" valign="top" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
    <?php if ( $isLogedIn == "no" ) { ?>
                <a style="text-decoration: none;" href="TeamFRC.php?section=home">Home</a>
    <?php } else { ?>
                <a  style="text-decoration: none;" href="TeamFRC.php?section=homePage">Home</a>
    <?php } ?>
            </div>
        </td>
    </tr>
    <!--tr>
        <td height="21" valign="top" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
                <div align="center">
                    <a href="TeamFRC.php?section=news">News</a>
                </div>
            </div>
        </td>
    </tr-->
    <tr>
        <td height="21" valign="top" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
                <a style="text-decoration: none;" href="TeamFRC.php?section=aboutTheTeam">About The Team</a>
            </div>
        </td>
    </tr>
    <tr>
        <td height="21" valign="top" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
                <a style="text-decoration: none;" href="TeamFRC.php?section=contactUs">Contact Us</a>
            </div>
        </td>
    </tr>

    <?php if ( $isLogedIn == "no" ) { ?>
    <div id="loginDiv">
        <tr>
            <td height="111" nowrap>
    <?php
        require_once dirname(__FILE__) . '/../PHP-GlobalIncludes/ProcessErrorsInclude.php';
        if ( $section != "ForgotPassword") {
        
            require_once dirname(__FILE__) . '/../PHP-Forms/loginForm.php'  ;
               } ?>
            </td>
        </tr>
    </div>
    <?php } else { ?>
    <tr>
        <td height="21" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
                <a style="text-decoration: none;" href="TeamFRC.php?section=teamMemberDirectory">Team Member Directory</a>
            </div>
        </td>
    </tr>
    <tr>
        <td height="21" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
                <a style="text-decoration: none;" href="TeamFRC.php?section=library">Library</a>
            </div>
        </td>
    </tr>
    <?php if ( $webAdmin == "yes" || $isAwarded == "no" ) { ?>
        <tr>
            <td height="21" bgcolor="#7E130D" nowrap>
                <div align="center" class="style31">
                    <a style="text-decoration: none;" href="TeamFRC.php?section=proposal">Proposals</a>
                </div>
            </td>
        </tr>
    <?php }
    if ( $webAdmin == "yes" || $isAwarded == "yes" ) { ?>
        <tr>
            <td height="21" bgcolor="#7E130D" nowrap>
                <div align="center" class="style31">
                    <a style="text-decoration: none;" href="TeamFRC.php?section=toRFQs">TO RFQs</a>
                </div>
            </td>
        </tr>
        <tr>
            <td height="21" bgcolor="#7E130D" nowrap>
                <div align="center" class="style31">
                    <a style="text-decoration: none;" href="TeamFRC.php?section=toAwards">TO Awarded</a>
                </div>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td height="21" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
             <?php if ( $teamManager == "yes" ) { ?>
                <a style="text-decoration: none;" href="TeamFRC.php?section=config">Change Configuration</a>
                <?php } else { ?>
                <a style="text-decoration: none;" href="TeamFRC.php?section=changePassword">Change Password</a>
                <?php } ?>
            </div>
        </td>
    </tr>
    <tr>
        <td height="21" bgcolor="#7E130D" nowrap>
            <div align="center" class="style31">
                <a style="text-decoration: none;" href='/PHP-Actions/logOutAction.php'>Logout</a>
            </div>
        </td>
    </tr>
    <?php } ?>
</table>
