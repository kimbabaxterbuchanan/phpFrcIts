<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
require_once dirname(__FILE__) .'/userProfileDAO.php';

class userDAO {

    function userDAO() {

    }

    function executeQry($qry) {
        return @mysql_query($qry);
    }

    function getAllUsers() {
        $qry = "select * from user order by teamName, lastname, firstname, login";
        return @mysql_query($qry);
    }

    function getAllTeams() {
        $qry = "select distinct teamName from user order by teamName";
        return @mysql_query($qry);
    }

    function getUserById($id) {
        $qry = "select * from user where id = '$id'";
        return @mysql_query($qry);
    }

    function getUserByLogin($login) {
        $qry = "select * from user where login = '$login'";
        return @mysql_query($qry);
    }

    function getUserByOnlyTeamName($tmName) {
        $qry = "select * from user where teamName='$tmName' order by lastname, firstname";
        return @mysql_query($qry);
    }

    function getUserByTeamName($tmName) {
        $qry = "select * from user where teamName = 'All' or teamName = '$tmName'";
        return @mysql_query($qry);
    }

    function getUserByLoginTeamName($login,$tmName) {
        $qry = "select * from user where login = '$login' and teamName = '$tmName'";
        return @mysql_query($qry);
    }

    function getUserByLoginPassword($login,$password) {
        $qry="SELECT * FROM user WHERE login='$login' AND passwd='".$password."'";
        $stat = @mysql_query($qry);
        if ( !$stat || mysql_num_rows($stat) == 0) {
            $qry="SELECT * FROM user WHERE login='$login' AND passwd='".md5($_POST['password'])."'";
            $stat = @mysql_query($qry);
        }
        return $stat;
    }

    function saveUpdateUserById ($id,$userNum,$fname,$lname,$login,$password,$tmManager,$wbAdmin,$tmName) {
        if ( $id == "" ) {
            $create_dt = date("Y/m/d H:i:s");
            $qry = "INSERT INTO user(userNum,firstname, lastname, login, passwd,teamManager,webAdmin,teamName,create_dt) VALUES
                        ('$userNum','$fname','$lname','$login','$password','$tmManager','$wbAdmin','$tmName','$create_dt')";
        } else {
            $qry = "update user set userNum = '".$userNum."', firstname = '".$fname."',
                        lastname = '".$lname."', login = '".$login."', passwd = '".$password."',
                        teamManager = '".$tmManager."', webAdmin = '".$wbAdmin."', teamName = '".$tmName."' where id = '".$id."'";
        }
        return @mysql_query($qry);
    }

    function updatePassword ($password, $id){
        $qry = "update user set passwd = '$password' where id = '$id'";
        $stat = @mysql_query($qry);
        if ( ! $stat ) {
            echo '<ul class="err">';
            echo  "Delete on ".$table." failed with Error: ".mysql_error();
            echo '</ul>';
            $errmsg_arr[] = "Update on ".$table." failed with Error: ".mysql_error();
            exit();
        }
        return $stat;
    }

    function deleteUserById ($id) {
        $userProfileDAO = new userProfileDAO();
        $userProfileDAO->deleteUserProfileByUserId($id);
        $qry = "delete from user where id = '".$id."'";
        return @mysql_query($qry);
    }

    function deleteUserByCompanyId ($id) {
        $userProfileDAO = new userProfileDAO();
        $userProfileDAO->deleteUserProfileByCompanyId($id);
        $qry = "delete from user where companyId = '".$id."'";
        return @mysql_query($qry);
    }

    function deleteUserByTeamName ($tmName) {
        $userProfileDAO = new userProfileDAO();
        $userProfileDAO->deleteUserProfileByTeamName($tmName);
        $qry = "delete from user where teamName = '".$tmName."'";
        return @mysql_query($qry);
    }

}

?>