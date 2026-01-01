<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-DAOs/UserDAO.php';
$userDAO = new UserDAO();

$qry = "select u.email, u.first_name, u.last_name, u.middle_initial from user u, userprofile p where u.id = p.userId and p.super_user > '0' order by u.last_name, u.first_name";
$users = $userDAO->executeQry($qry);
//$users = convert2array($users);
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 

<html> 
    <head> 
        <title>View Text</title>
    </head> 
    
    <body>
    <div id="viewText"></div>
        <input type=button onclick="javascript: window.close();" value='Close'/>
    </body> 
        <script>
            var obj = document.getElementById("viewText");
            obj.innerHTML = "<?=$_GET['changeText']?>";
        </script>
</html> 
