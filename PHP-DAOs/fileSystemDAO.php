<?php
//Start session
session_start();
//Include database connection details
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/config.php';
class fileSystemDAO {

    function fileSystemDAO() {
        
        }

    function mkDirectoryByTeamName ($tmName) {
            global $homeURL, $homeLoc, $hdrLocation, $paramString, $login, $loginId, $isLogedIn, $isAwarded;
            global $webAdmin, $teamManager, $userCompanyId, $createTeamDirAry, $createWorkDirAry;
            global $curDir, $companyDir, $awardDir, $awardWorkDir, $libDir, $libWorkDir, $directoryWorkHome, $directoryHome;
        
            mkDirectory($directoryWorkHome.$libWorkDir."/");
            mkDirectory($directoryWorkHome.$libWorkDir."/".$tmName."/");
            foreach( $createTeamDirAry as $createTeamDir ) {
                    if ( $createTeamDir != $libWorkDir ) {
                            mkDirectory($directoryWorkHome.$createTeamDir."/");
                            mkDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/");
                        }
                }
        }

    function delDirectoryByTeamName($tmName) {
            global $homeURL, $homeLoc, $hdrLocation, $paramString, $login, $loginId, $isLogedIn, $isAwarded;
            global $webAdmin, $teamManager, $userCompanyId, $createTeamDirAry, $createWorkDirAry;
            global $curDir, $companyDir, $awardDir, $awardWorkDir, $libDir, $libWorkDir, $directoryWorkHome, $directoryHome;
            foreach( $createTeamDirAry as $createTeamDir ) {
                    rmDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/");
                }
        }

    function mkDirectoryByCompanyName ($tmName, $name) {
            global $homeURL, $homeLoc, $hdrLocation, $paramString, $login, $loginId, $isLogedIn, $isAwarded;
            global $webAdmin, $teamManager, $userCompanyId, $createTeamDirAry, $createWorkDirAry, $proposalWorkDir, $proposalDir;
            global $curDir, $companyDir, $awardDir, $awardWorkDir, $libDir, $libWorkDir, $directoryWorkHome, $directoryHome;
            foreach( $createTeamDirAry as $createTeamDir ) {
                    if ( $createTeamDir != $libWorkDir  ) {
                            mkDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/".$name."/");
                            foreach( $createWorkDirAry as $createWorkDir ) {
                                    if ( ( $createTeamDir != $proposalWorkDir && $createWorkDir != $proposalDir) || $createTeamDir == $finalWorkDir )  {
                                            mkDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/".$name."/".$createWorkDir."/");
                                        }
                                }
                        }
                }
        }

    function delDirectoryByCompanyName($tmName,$name) {
            global $homeURL, $homeLoc, $hdrLocation, $paramString, $login, $loginId, $isLogedIn, $isAwarded;
            global $webAdmin, $teamManager, $userCompanyId, $createTeamDirAry, $createWorkDirAry;
            global $curDir, $companyDir, $awardDir, $awardWorkDir, $libDir, $libWorkDir, $directoryWorkHome, $directoryHome;
            foreach( $createTeamDirAry as $createTeamDir ) {
                    rmDirectory($directoryWorkHome.$createTeamDir."/".$tmName."/".$name."/");
                }
        }


}
?>