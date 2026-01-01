<?php
require_once dirname(__FILE__) .'/PHP-GlobalIncludes/auth.php';

switch ($section){
    case "home":
        $btn = "0c";
        break;
    case "homePage":
        $btn = "0c";
        break;
    case "aboutTheTeam":
        $btn = "1c";
        break;
    case "contactUs":
        $btn = "2c";
        break;
    case "teamMemberDirectory":
        $btn = "3c";
        break;
    case "library":
        $btn = "4c";
        break;
    case "proposal":
        $btn = "5c";
        break;
    case "toRFQs":
        $btn = "6c";
        break;
    case "toAwards":
        $btn = "7c";
        break;
    case "changePassword":
        $btn = "8c";
        break;
    case "config":
        $btn = "9c";
        break;
    case "login":
        $btn = "10c";
        break;
    Default:
        $btn = "";
}

?>
<html>
    <head>

        <title>ITS-SB (Information Technology Services - Small Business) Program -- Team FRC </title>
        <meta name="Keywords" content="ITSB Information Technology Services-Small Business Small Business Team FRC FRC Future Research Corporation EPEAT IA  IV&amp;V IPv6 Migration Integration Warranty Maintenance " /> <meta name="Description" content="Welcome to the Team FRC Portal for the Information Technology Services-Small Business (ITS-SB) Program. Future Research Corporation (FRC) has been chosen in a downselect as one of the 25 viable vendors in the Army�s 400 million dollar Information Technology Service-Small Business (ITS-SB) IDIQ contract. For more information or to submit your company�s qualifications, please contact us." />
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="PHP-css/gmail.css" rel="stylesheet" type="text/css">
        <script language="JavaScript" type="text/javascript"></script>
        <script language="JavaScript" type="text/javascript" src="PHP-jsScript/teamFRC.js"></script>
        <script language="JavaScript" type="text/javascript" src="PHP-jsScript/Dom_Utils.js"></script>
    </head>
    <body onload="btn0n=loadImage('n0.gif');btn0o=loadImage('o0.gif');btn0c=loadImage('c0.gif');
        btn1n=loadImage('n1.gif');btn1o=loadImage('o1.gif');btn1c=loadImage('c1.gif');
        btn2n=loadImage('n2.gif');btn2o=loadImage('o2.gif');btn2c=loadImage('c2.gif');
        btn3n=loadImage('n3.gif');btn3o=loadImage('o3.gif');btn3c=loadImage('c3.gif');
        btn4n=loadImage('n4.gif');btn4o=loadImage('o4.gif');btn4c=loadImage('c4.gif');
        btn5n=loadImage('n5.gif');btn5o=loadImage('o5.gif');btn5c=loadImage('c5.gif');
        btn6n=loadImage('n6.gif');btn6o=loadImage('o6.gif');btn6c=loadImage('c6.gif');
        btn7n=loadImage('n7.gif');btn7o=loadImage('o7.gif');btn7c=loadImage('c7.gif');
        btn8n=loadImage('n8.gif');btn8o=loadImage('o8.gif');btn8c=loadImage('c8.gif');
        btn9n=loadImage('n9.gif');btn9o=loadImage('o9.gif');btn9c=loadImage('c9.gif');
        btn10n=loadImage('n10.gif');btn10o=loadImage('o10.gif');btn10c=loadImage('c10.gif');
        btn11n=loadImage('n11.gif');btn11o=loadImage('o11.gif');btn11c=loadImage('c11.gif');
        btn12n=loadImage('n12.gif');btn12o=loadImage('o12.gif');btn12c=loadImage('c12.gif');
        getBtnVal();">
          <form name="TeamFRC" id="TeamFRC" method="post">
            <input name="btnVal" id="btnVal" type="hidden" value="<?=$btn?>"/>
            <table width="909" height="1000" border="1" align="center" bordercolor="#000000" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%" height="106"><?php require_once dirname(__FILE__) .'/PHP-FileIncludes/TeamFRCHeader.php'; ?></td>
                </tr>

                <tr>
                    <td width="100%" height="30" valign="top" bgcolor="#999999"><?php require_once dirname(__FILE__) .'/PHP-FileIncludes/TeamFRCSideBar.php'; ?></td>
                </tr>
                <tr>
                    <td width="100%"  height="1000"  valign="top" align="right"><?php require_once dirname(__FILE__) .'/PHP-FileIncludes/TeamFRCBody.php'; ?></td>
                </tr>
            </table>
        </form>
    </body>

</html>