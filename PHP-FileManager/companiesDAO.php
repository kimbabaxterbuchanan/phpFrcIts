<?php
//Start session
session_start();
//Include database connection details
require_once('config.php');

    foreach ($_GET as $key => $val) $$key=htmldecode($val);
        //Array to store validation errors


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

$postForm = clean($_POST['postForm']);

if ( isset($postForm) && $postForm != "" ) {
//Sanitize the POST values
    $rtnPage = clean($_POST['rtnPage']);
    $section = clean($_POST['section']);
    $sub_section = clean($_POST['sub_section']);
    $formAction = clean($_POST['formAction']);

    $cancel = clean($_POST['cancel']);
    if ( $cancel != ""){
            $url = "http://".$homeURL."/TeamFRC.php?section=".$section;
            if ( isset($sub_section) && $sub_section != "" ) {
                    $url .= "&sub_section=".$sub_section;
                }
            echo "<script language=\"Javascript\">
                top.location=\"".$url."\";
                </script>";
            exit();
        }

    $id = clean($_POST['id']);
    $name = clean($_POST['name']);

    $createTeamDirAry = Array();
    $createTeamDirAry[] = $libWorkDir;
    $createTeamDirAry[] = $proposalWorkDir;
    $createTeamDirAry[] = $teamWorkDir;
    $createTeamDirAry[] = $finalWorkDir;

    $createWorkDirAry = Array();
    $createWorkDirAry[] = $proposalDir;
    $createWorkDirAry[] = $toAwardDir;
    $createWorkDirAry[] = $toRFQsDir;

    if ( $formAction != "delete") {
            $companyNum = clean($_POST['companyNum']);
            $logo = clean($_POST['logo']);
            $displayname = clean($_POST['displayname']);
            $website = clean($_POST['website']);
            $tmName = clean($_POST['tmName']);

                //Input Validations
            if($name == '') {
                    $errmsg_arr[] = 'Company name missing';
                    $errflag = true;
                }
            if($logo == '') {
                    $errmsg_arr[] = 'Company Logo Image File name missing';
                    $errflag = true;
                }
            if($displayname == '') {
                    $errmsg_arr[] = 'Company Display Name missing';
                    $errflag = true;
                }
            if($website == '') {
                    $errmsg_arr[] = 'Company website missing';
                    $errflag = true;
                }
                //If there are input validations, redirect back to the registration form

                //Create INSERT query
            if ( !$errflag ) {
                    if ( $id == '') {
                            $cDate = date("Y/m/d H:i:s");
                            $qry = "INSERT INTO companies(companyNum,name,logo,displayname,website,teamName, create_dt) VALUES ('$companyNum','$name','$logo','$displayname','$website','$tmName','$cDate')";
                        } else {
                            $qry = "update companies set companyNum = '".$companyNum."', name = '".$name."', logo = '".$logo."', displayname = '".$displayname."', website = '".$website."', teamName = '".$tmName."' where id = '".$id."'";
                        }
                    $result = @mysql_query($qry);

                        //Check whether the query was successful or not
                    if($result) {
                            foreach( $createTeamDirAry as $createTeamDir ) {
                                    if ( $createTeamDir != $libWorkDir ) {
                                        mkDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/".$name."/");
                                        foreach( $createWorkDirAry as $createWorkDir ) {
                                                if ( ( $createTeamDir != $proposalWorkDir && $createWorkDir != $proposalDir) || $createTeamDir == $finalWorkDir )  {
                                                        mkDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/".$name."/".$createWorkDir."/");
                                                }
                                        }
                                    }
                                }
                            if ( $teamManager == "yes" ) {
                                    if ( $id == '') {
                                            $qry = "select * from companies where companyNum = '$companyNum' and teamName = '$tmName'";
                                            $result = @mysql_query($qry);
                                            $res = mysql_fetch_assoc($result);

                                            header("location: companyProfileForm.php?id=".$res['id']."&section=".$section."&sub_section=".$sub_section."&formAction=");
                                        } else {
                                            $url = "http://".$homeURL."/TeamFRC.php?section=".$section;
                                            if ( isset($sub_section) && $sub_section != "" ) {
                                                    $url .= "&sub_section=".$sub_section;
                                                }
                                            echo "<script language=\"Javascript\">
                                                top.location=\"".$url."\";
                                                </script>";
                                            exit();
                                        }
                                } else {
                                    echo "<script language=\"Javascript\">
                                        top.location=\"http://".$homeURL."/TeamFRC.php?section=homePage\";
                                        </script>";
                                }
                            exit();
                        } else {
                            $errmsg_arr[] = 'Unable to Save or Update Company.';
                            $errflag = true;
                        }
                }
        } else {
            $qry = "delete from  companyprofile where companyId = '".$id."'";
            $result = @mysql_query($qry);
                //Check whether the query was successful or not
            if($result) {
                    $qry = "delete from companies where id = '".$id."'";
                    $result = @mysql_query($qry);
                    if($result) {
                            $qry = "select * from userprofile where companyId = '".$id."'";
                            $result = @mysql_query($qry);
                            while($res = mysql_fetch_array($result)){
                                $qry = "delete from user where id = '".$res['userId']."'";
                                $result = @mysql_query($qry);
                            }
                            $qry = "delete from userprofile where companyId = '".$id."'";
                            $result = @mysql_query($qry);
                            $qry = "delete from proposal where companyId = '".$id."'";
                            $result = @mysql_query($qry);
                            $qry = "delete from toawards where companyId = '".$id."'";
                            $result = @mysql_query($qry);
                            $qry = "delete from torfqs where companyId = '".$id."'";
                            $result = @mysql_query($qry);

                            foreach( $createTeamDirAry as $createTeamDir ) {
                                rmDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/".$name."/");
                            }
                            $url = "http://".$homeURL."/TeamFRC.php?section=".$section;
                            if ( isset($sub_section) && $sub_section != "" ) {
                                    $url .= "&sub_section=".$sub_section;
                                }
                            echo "<script language=\"Javascript\">
                                top.location=\"".$url."\";
                                </script>";
                            exit();
                        } else {
                            $errmsg_arr[] = 'Unable to delete company.';
                            $errflag = true;
                        }
                } else {
                    $errmsg_arr[] = 'Unable to delete company\'s profile.';
                    $errflag = true;
                }
        }
}
//Create INSERT query
if ( $sub_section == "listCompany" && ! isset($formAction) ) {
    if ( $webAdmin == "yes") {
            $qry = "select * from companies order by teamName, name";
        } else {
            $qry = "select * from companies where teamName = 'All' or teamName = '$teamName'";
        }
} else {
    $qry = "select * from companies where id = '$id'";
}
$result = @mysql_query($qry);
$qry = "select * from isawarded where teamName = '$teamName' order by teamName";

if ( $webAdmin == "yes" ) {
$qry = "select * from isawarded order by teamName";
}
$teamResult = @mysql_query($qry);

//Check whether the query was successful or not
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
        //            header("location: userForm.php?id=".$id);
        //            exit();
}

//if($errflag) {
//    require_once('SQLErrorInclude.php');
//}

?>