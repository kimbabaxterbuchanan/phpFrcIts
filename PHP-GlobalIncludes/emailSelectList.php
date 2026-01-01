<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-DAOs/userDAO.php';

$userDAO = new userDAO();

$qry = "select p.email, u.firstname, u.lastname from user u, userprofile p where u.id = p.userId ";
if ( $_GET['tn'] != "" )
$qry .= " and u.teamName = '".$_GET['tn']."' ";

$qry .= " order by u.lastname, u.firstname";
$users = $userDAO->executeQry($qry);
//$users = convert2array($users);
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 

<html> 
    <head>
        <title>Select Email from List</title>
        <script>
            function showselection() {
                document.forms[0].selec.value = document.selection.createRange().text;
            }
            function clearParent(){
                opener.document.<?=$_GET['fn'].".".$_GET['mail'];?>.value = "";
                opener.document.<?=$_GET['fn'].".".$_GET['name'];?>.value = "";
            }
            function updateParent(obj){
                var selIdx = obj.selectedIndex;
                var val = obj.options[selIdx].value;
                var txt = obj.options[selIdx].text;
                var addPost = "";
                if ( opener.document.<?=$_GET['fn'].".".$_GET['mail'];?>.value != "" )
                addPost = "; ";
                opener.document.<?=$_GET['fn'].".".$_GET['mail'];?>.value += addPost+val;
                opener.document.<?=$_GET['fn'].".".$_GET['name'];?>.value += addPost+txt;
            }
        </script>
    </head>
    
    <body>
        <select name="emailList" id="emailList" size=10 onchange="updateParent(this)">
            <option value=""></option>
            <?php
            while($user = mysql_fetch_array($users))
            {
                echo "<option value='".$user['email']."'>".$user['lastname'].", ".$user['firstname']."</option>";
            }
            ?>
        </select>
        <br>
        <input type=button onclick="showselection();" value='select'/>&nbsp;&nbsp;&nbsp;
        <input type=button onclick="clearParent();" value='Reset'/>&nbsp;&nbsp;&nbsp;
        <input type=button onclick="javascript: window.close();" value='Close'/>
    </body>
</html> 
