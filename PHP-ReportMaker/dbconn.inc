<?php
$ConID;
$dirname = "sqldocs";
connectToDB();
function connectToDB()
    {
    global $ConID;
    $ConID =  mysql_connect( "localhost", "root", "admin01"  );
    if ( ! $ConID )
        die( "Couldn't connect to MySQL" );
    }
?>