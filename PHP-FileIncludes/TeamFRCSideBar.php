<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';

$norm_bgcolor = "BA1303";
$sel_bgcolor = "801303";
$bgcolor= "BA1303";
$spwidth = "513";  // this value Plus number of buttons equals 1009.
//400 - not logged in size
//700 - award logged in size
//800 - site admin logged in size
echo "<script language='JavaScript' type='text/javascript' src='PHP-jsScript/Dom_Utils.js'></script>";

echo "<table width='0' border='0'  cellpadding='0' cellspacing='0'>";
echo "<tr>";
if ( $isLogedIn == "no" ) {
    $homesection="home";
} else {
    $homesection="homePage";
}
echo "<td width=100 height=30 bgcolor=\"WHITE\" nowrap cellpadding=\"0\" cellspacing=\"0\"><a style=\"text-decoration: none;\" href=\"TeamFRC.php?section=".$homesection."\" onmouseover='msOver(\"0o\");' onmouseout='msOut(\"0n\");' onmousedown='btnSelect(\"0c\");'><img src=\"PHP-images/wbimg/imgn0.gif\" name=btn0 width=100 height=30 border=0 alt='Home' hspace=\"0\"></a></td>";
echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=aboutTheTeam' onmouseover='msOver(\"1o\");' onmouseout='msOut(\"1n\");' onmousedown='btnSelect(\"1c\");'><img src='PHP-images/wbimg/imgn1.gif' name=btn1 width=100 height=30 border=0 alt='Team Info' hspace='0'></a></td>";
echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=contactUs' onmouseover='msOver(\"2o\");' onmouseout='msOut(\"2n\");' onmousedown='btnSelect(\"2c\");'><img src='PHP-images/wbimg/imgn2.gif' name=btn2 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
if ( $isLogedIn == "yes" ) {
    echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=teamMemberDirectory' onmouseover='msOver(\"3o\");' onmouseout='msOut(\"3n\");' onmousedown='btnSelect(\"3c\");'><img src='PHP-images/wbimg/imgn3.gif' name=btn3 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
    echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=library' onmouseover='msOver(\"4o\");' onmouseout='msOut(\"4n\");' onmousedown='btnSelect(\"4c\");'><img src='PHP-images/wbimg/imgn4.gif' name=btn4 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
        $spwidth = $spwidth - 200;

    if ( $webAdmin == "yes" || $isAwarded == "no" ) {
        echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=proposal' onmouseover='msOver(\"5o\");' onmouseout='msOut(\"5n\");' onmousedown='btnSelect(\"5c\");'><img src='PHP-images/wbimg/imgn5.gif' name=btn5 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
        $spwidth = $spwidth - 100;
    }
    if ( $webAdmin == "yes" || $isAwarded == "yes" ) {
        echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=toRFQs' onmouseover='msOver(\"6o\");' onmouseout='msOut(\"6n\");' onmousedown='btnSelect(\"6c\");'><img src='PHP-images/wbimg/imgn6.gif' name=btn6 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
        echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=toAwards' onmouseover='msOver(\"7o\");' onmouseout='msOut(\"7n\");' onmousedown='btnSelect(\"7c\");'><img src='PHP-images/wbimg/imgn7.gif' name=btn7 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
    } else {
        $spwidth = $spwidth - 100;
    }
    if ( $teamManager == "yes" ) {
        echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=config' onmouseover='msOver(\"9o\");' onmouseout='msOut(\"9n\");' onmousedown='btnSelect(\"9c\");'><img src='PHP-images/wbimg/imgn9.gif' name=btn9 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
    } else {
        echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=changePassword' onmouseover='msOver(\"8o\");' onmouseout='msOut(\"8n\");' onmousedown='btnSelect(\"8c\");'><img src='PHP-images/wbimg/imgn8.gif' name=btn8 width=100 height=30 border=0 alt='Contact Us' hspace='0'></a></td>";
        //        $spwidth = $spwidth - 300;
    }
    echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='PHP-Actions/logOutAction.php' onmouseover='msOver(\"11o\");' onmouseout='msOut(\"11n\");' onmousedown='btnSelect(\"11c\");'><img src='PHP-images/wbimg/imgn11.gif' name=btn11 width=100 height=30 border=0 alt='Logout' hspace='0'></a></td>";
} else {
    echo "<td width=100 height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'><a  style='text-decoration: none;' href='TeamFRC.php?section=login' onmouseover='msOver(\"10o\");' onmouseout='msOut(\"10n\");' onmousedown='btnSelect(\"10c\");'><img src='PHP-images/wbimg/imgn10.gif' name=btn10 width=100 height=30 border=0 alt='Login' hspace='0'></a></td>";
}
if ( $webAdmin == "no" )
    echo "<td width='".$spwidth."' height=30 bgcolor='WHITE' nowrap cellpadding='0' cellspacing='0'>&nbsp;</td>";
echo "</tr>";
echo "</table>";

