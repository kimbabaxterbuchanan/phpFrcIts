<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/encdec.php';
//require_once dirname(__FILE__) .'/../PHP-DAOs/emailDAO.php';
//require_once dirname(__FILE__) .'/../PHP-DAOs/awardedDAO.php';
//require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';

//require_once dirname(__FILE__) .'/../PHP-Mail/mailNotification.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/AES128.php';
$decode = new AES128();

$passwd = $_POST['passwd'];
$salt=$decode->makeKey($salt);

if ( $passwd != "" )
$pPassword = $decode->blockDecrypt($passwd,$salt);

?>
<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
    <form name="passwd" id="passwd" method="post" action="getPasswd.php">
        <input size="50" type="text" name="passwd" id="passwd" value="<?=$passwd?>"><BR>
        <input size=50 type="text" name="$pPassword" id="$pPassword" value="<?=$pPassword?>"><BR>
        <input type="submit" name="submit" id="submit" value="submit">
    </form>
</body>
</html>
