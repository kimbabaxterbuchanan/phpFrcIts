<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'FRCAdmin');
define('DB_PASSWORD', 'frcadmin01');
define('DB_DATABASE', 'teamfrc');

foreach ($_GET as $key => $val) $$key=htmldecode($val);

$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Connect to mysql server
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if(!$link) {
    die('Failed to connect to server: ' . mysql_error());
}

//Select database
$db = mysql_select_db(DB_DATABASE);
if(!$db) {
    die("Unable to select database");
}


?>