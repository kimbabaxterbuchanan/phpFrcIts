<?php

require_once dirname(__FILE__) . '/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) . '/../PHP-DAOs/EmailDAO.php';
require_once dirname(__FILE__) . '/../PHP-Mail/class.phpmailer.php';

function getLabel($lbl, $locale)
{
    return ucfirst($lbl);
}
function buildEmail($emailId, $tMail, $tName, $appMsg)
{
    global $isHTML, $smtpAuth, $smtpUsername, $smtpPassword, $port, $wordWrap, $defaultMailHost, $toMail;
    global $toName, $fromMail, $fromName, $replyMail, $replyName, $subject, $body, $altBody;
    global $notifyEmailId;

    $notifyEmailId = $emailId;
    $emailModel = new StdClass;
    $emailDAO = new EmailDAO();
    $sort = " where id = '" . $emailId . "'";
    $emailModel = $emailDAO->getEmailByID($id);
    $stat = false;
    if ($emailModel) {
        if ($emailModel->toMail == "") {
            $emailModel->toMail = $tMail;
            $emailModel->toName = $tName;
        }

        $emailModel->smtpAuth = false;
        if ($emailModel->smtpAuth == 'yes')
            $emailModel->smtpAuth = true;

        $emailModel->isHTML = true;
        if ($emailModel->isHTML == 'no')
            $emailModel->isHTML = false;
        if ($appMsg != "") {
            $emailModel->body .= " " . $appMsg;
            $emailModel->altBody .= " " . $appMsg;
        }
        $stat = new mailNotification(
            $emailModel->replyMail,
            $emailModel->replyName,
            $emailModel->mailHost,
            $emailModel->fromMail,
            $emailModel->fromName,
            $emailModel->smtpAuth,
            $emailModel->smtpUsername,
            $emailModel->smtpPassword,
            $emailModel->toMail,
            $emailModel->toName,
            $emailModel->ccMail,
            $emailModel->ccName,
            $emailModel->subject,
            $emailModel->body,
            $emailModel->altBody,
            $emailModel->isHTML,
            $port
        );
    }
    return $stat;
}

function rtnDate($dateModel)
{
    if ($dateModel == "") {
        return date("Y/m/d H:i:s");
    } else {
        foreach ($dateModel as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "hour":
                        if ($dateModel->hopr == "-") {
                            $hour = date("h") - $dateModel->hour;
                        } else {
                            $hour = date("h") + $dateModel->hour;
                        }
                        break;

                    case "minute":
                        if ($dateModel->iopr == "-") {
                            $minute = date("i") - $dateModel->iminute;
                        } else {
                            $minute = date("i") + $dateModel->iminute;
                        }
                        break;

                    case "second":
                        if ($dateModel->sopr == "-") {
                            $second = date("s") - $dateModel->second;
                        } else {
                            $second = date("s") + $dateModel->second;
                        }
                        break;

                    case "day":
                        if ($dateModel->dopr == "-") {
                            $day = date("d") - $dateModel->day;
                        } else {
                            $day = date("d") + $dateModel->day;
                        }
                        break;

                    case "month":
                        if ($dateModel->mopr == "-") {
                            $month = date("m") - $dateModel->month;
                        } else {
                            $month = date("m") + $dateModel->month;
                        }
                        break;

                    case "year":
                        if ($dateModel->yopr == "-") {
                            $year = date("y") - $dateModel->year;
                        } else {
                            $year = date("y") + $dateModel->year;
                        }
                        break;
                }
            } else {
                if (strpos($key, "opr") === false && strpos($key, "create") === false && strpos($key, "date") === false) {
                    $t = substr($key, 0, 1);
                    $$key = date($t);
                }
            }
        }
        $dateStr = "Y-m-d H:i:s";
        if ($dateModel->timeOrDate == "time") {
            $month = 0;
            $day = 0;
            $year = 0;
            $dateStr = "H:i:s";
        } else if ($dateModel->timeOrDate == "date") {
            $hour = 0;
            $minute = 0;
            $second = 0;
            $dateStr = "Y-m-d";
        }
        $createTimestamp = mktime($hour, $minute, $second, $month, $day, $year);
        $dateModel->create_date = date($dateStr, $createTimestamp);
    }
    return $dateModel;
}

function getDbInfo($sql)
{
    require_once dirname(__FILE__) . '/../PHP-DAOs/DbDAO.php';
    $omitTables = "XxXtablelinkXxXlanguageXxXconfigurationXxXstateXxXcountryXxXemailXxXnotificationXxXforwarderXxXreportXxX";
    $omitFields = "XxXcreate_dateXxXlast_modifiedXxX";
    $daofile = new DbDAO();
    $tables = $daofile->executeBaseQry($sql);
    if ($tables) {
        $array = array();
        $cnt = 0;
        while ($res = mysql_fetch_row($tables)) {
            if (strpos($omitTables, "XxX" . $res[0] . "XxX") === false && strpos($omitFields, "XxX" . $res[0] . "XxX") === false) {
                if (!strpos($res[0], '_') === false)
                    $res[0] = str_replace('_', '-', $res[0]);
                $array[$cnt] = $res[0];
                $cnt += 1;
            }
        }
        return $array;
    }
    return $tables;
}

function getDbFunctionsWithId($sql)
{
    require_once dirname(__FILE__) . '/../PHP-DAOs/DbDAO.php';
    $omitTables = "XxXtablelinkXxXlanguageXxXconfigurationXxXstateXxXcountryXxXemailXxXnotificationXxXforwarderXxXreportXxX";
    $omitFields = "XxXcreate_dateXxXlast_modifiedXxX";
    $daofile = new DbDAO();
    $tables = $daofile->executeBaseQry($sql);
    if ($tables) {
        $array = array();
        $cnt = 0;
        while ($res = mysql_fetch_row($tables)) {
            if (strpos($omitTables, "XxX" . $res[0] . "XxX") === false && strpos($omitFields, "XxX" . $res[0] . "XxX") === false) {
                if (!strpos($res[0], '_') === false)
                    $res[0] = str_replace('_', '-', $res[0]);
                $array[$cnt] = $res[0] . "_" . $res[0];
                $cnt += 1;
            }
        }
        return $array;
    }
    return $tables;
}

function getDbFunctions($sql)
{
    require_once dirname(__FILE__) . '/../PHP-DAOs/DbDAO.php';
    $omitTables = "XxXtablelinkXxXlanguageXxXconfigurationXxXstateXxXcountryXxXemailXxXnotificationXxXforwarderXxXreportXxX";
    $omitFields = "XxXidXxXcreate_dateXxXlast_modifiedXxX";
    $daofile = new DbDAO();
    $tables = $daofile->executeBaseQry($sql);
    if ($tables) {
        $array = array();
        $cnt = 0;
        while ($res = mysql_fetch_row($tables)) {
            if (strpos($omitTables, "XxX" . $res[0] . "XxX") === false && strpos($omitFields, "XxX" . $res[0] . "XxX") === false) {
                if (!strpos($res[0], '_') === false)
                    $res[0] = str_replace('_', '-', $res[0]);
                $array[$cnt] = $res[0] . "_" . $res[0];
                $cnt += 1;
            }
        }
        return $array;
    }
    return $tables;
}

function getSqlOptList($sql, $type)
{
    global $homeDr;
    $sqlAry = split(",", $sql);
    $tbl = $sqlAry[0];
    $sqltxt = $sqlAry[1];
    $daofile = dirname(__FILE__) . '/../PHP-DAOs/' . ucfirst($tbl) . 'DAO.php';
    $modelfile = dirname(__FILE__) . '/../PHP-models/' . ucfirst($tbl) . 'Model.php';
    $modelVar = ucfirst($tbl) . "Model";
    $daoVar = ucfirst($tbl) . "DAO";
    require_once $daofile;
    require_once $modelfile;

    $dao = new $daoVar;
    $model = new $modelVar;

    $qry = $sqltxt;
    $models = $dao->executeQry($qry);
    $models = convert2array($models);
    $array = array();
    $cnt = 0;
    if (!strpos($type, ",") === false) {
        $tmpAry = explode(",", $type);
        foreach ($models as $model) {
            $conj = "";
            for ($i = 0; $i < count($tmpAry); $i++) {
                $array[$cnt] .= $conj . $model->$tmpAry[$i];
                $conj = "_";
            }
            $cnt += 1;
        }
    } else {
        foreach ($models as $model) {
            $array[$cnt] = $model->$type;
            $cnt += 1;
        }
    }
    return $array;
}

function convert2array($obj)
{
    if (is_object($obj)) {
        $array = array();
        $array[0] = array2object($obj);
    } else {
        $array = $obj;
    }
    if (!$array)
        $array = array();
    return $array;
}

function array2object($array)
{

    if (is_array($array)) {
        $obj = new StdClass();

        foreach ($array as $key => $val) {
            if ($key != "" && !is_numeric($key))
                $obj->$key = $val;
        }
    } else {
        $obj = $array;
    }

    return $obj;
}

function shufflearray2object($array, $obj)
{

    if (is_array($array)) {
        $objt = new StdClass();

        foreach ($array as $key => $val) {
            if (!is_array($val)) {
                foreach ($obj as $ky => $vl) {
                    if ($ky == $key && !is_numeric($key)) {
                        if ($val != $vl) {
                            $obj->$ky = $val;
                        } else {
                            $obj->$ky = $vl;
                        }
                    }
                }
            }
        }
    } else {
        $obj = $array;
    }

    return $obj;
}

function shufflearray2array($array, $ary)
{

    if (is_array($array)) {

        foreach ($array as $key => $val) {
            foreach ($ary as $ky => $vl) {
                if ($ky == $key && !is_numeric($key)) {
                    $ary[$key] = $val;
                }
            }
        }
    } else {
        $ary = $array;
    }

    return $ary;
}

function object2array($object)
{
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    } else {
        $array = $object;
    }
    return $array;
}

function shuffleobject2array($object, $array)
{
    global $val;
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            foreach ($ary as $ky => $vl) {
                if ($ky == $key && !is_numeric($key)) {
                    $ary[$key] = $val;
                }
            }
            $array[$key] = $value;
        }
    } else {
        $array = $object;
    }
    return $array;
}

function shuffleobject2object($object, $obj)
{
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            foreach ($obj as $ky => $vl) {
                if ($ky == $key && !is_numeric($key)) {
                    $obj->$key = $value;
                }
            }
        }
    } else {
        $obj = $object;
    }
    return $obj;
}

function checkEmailFormat($email)
{
    $indx1 = strpos($email, "@");
    $indx2 = strpos($email, ".");
    $correct = false;
    if ($indx1 > 0 && $indx1 < $indx2)
        $correct = "true";
    return $correct;
}

function generatePassword($length, $strength)
{
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    }
    if ($strength & 2) {
        $vowels .= "AEUY";
    }
    if ($strength & 4) {
        $consonants .= '23456789';
        $vowels .= '37269485';
    }
    if ($strength & 8) {
        $consonants .= '@#$%';
    }

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}


function doUrl($hdrLocation, $paramString, $section, $sub_section)
{
    global $homeURL, $homeLoc;
    $url = $hdrLocation;
    $paramDelimiter = '?';
    if ($hdrLocation == "") {
        $url = "http://" . $homeURL . "/" . $homeLoc;
    }

    if ($section != "") {
        $str = $paramDelimiter . "section=";
        $url .= $paramDelimiter . "section=" . $section;
        $paramDelimiter = '&';
    }

    if ($sub_section != "") {
        $url .= $paramDelimiter . "sub_section=" . $sub_section;
    }
    if ($paramString != "") {
        $url .= $paramDelimiter . $paramString;
        $paramDelimiter = '&';
    }

    if ($hdrLocation == "") {
        echo "<script language=\"Javascript\">
                top.location=\"" . $url . "\";
                </script>";
    } else {
        header("location: " . $url);
    }
    exit();
}

function clean($str)
{
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}
;

function html_entity_decode_for_php4_compatibility($string)
{
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    $ret = strtr($string, $trans_tbl);
    return preg_replace(
        '/\&\#([0-9]+)\;/me',
        "chr('\\1')",
        $ret
    );
}
function htmldecode($str)
{
    if (is_string($str)) {
        if (get_magic_quotes_gpc())
            return stripslashes(html_entity_decode_for_php4_compatibility($str));
        else
            return html_entity_decode($str);
    } else
        return $str;
}

function randomPassword($length)
{
    list($usec, $sec) = explode(' ', microtime());
    srand((float) $sec + ((float) $usec * 100000));
    $possible_characters = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";
    // note that 0 and O, 1 and l (that's zero and 'oh', one and 'el') 
    // have been omitted as they are easily confused
    $passstring = "";
    while (strlen($passstring) < $length) {
        $passstring .= substr($possible_characters, rand(0, strlen($possible_characters)), 1);
    }
    return ($passstring);
}

function checkPassword($password)
{
    //Makes it easy to implement grammar rules.
//Array to store validation errors
    session_start();

    $errmsg_arr = array();
    $errmsg_arr = $_SESSION['ERRMSG_ARR'];
    //Validation error flag
    $errflag = false;

    $strlen = strlen($password);

    if ($strlen <= 5) {
        $errmsg_arr[] = "Password is too short";
        $errflag = true;
    }

    $count_chars = count_chars($password, 3);

    $password = trim($password);
    /* Check if username is not alphanumeric */
    if (!eregi("([a-z])", $password) || !eregi("([0-9])", $password)) {
        $errmsg_arr[] = "Password is not alphanumeric";
        $errflag = true;
    }

    if ($errflag) {
        $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
        session_write_close();
    }

    return ($errflag);
}

function DateRange($string, $range)
{
    $jb0 = cal_to_jd(CAL_GREGORIAN, date("m"), date("d"), date("Y"));
    $jb1 = $jb0 + $range + 1;
    $jb2 = cal_from_jd($jb1, CAL_GREGORIAN);
    $jb0 .= " 00:00:00";
    $jb2 .= " 00:00:00";
    $drange = "('" . $jb0 . "','" . $jb2 . "')";
    return $drange;
}

function phpDateToMysqlDate($pdate)
{
    $tmpDt = split("/", $pdate);
    $mdate = $tmpDt[2] . "-" . $tmpDt[0] . "-" . $tmpDt[1] . " 00:00:00";
    return $mdate;

}
function mysqlDateToPhpDate($mydate)
{

    $tmpDt = split("-", $mydate);
    if (strpos($mydate, " ") > 0) {
        $mdate = split(" ", $mydate);
        $tmpDt = split("-", $mdate[0]);
    }
    $pdate = $tmpDt[1] . "/" . $tmpDt[2] . "/" . $tmpDt[0];
    return $pdate;
}

function mkDirectory($dest)
{
    if (!file_exists($dest)) {
        mkdir($dest, 0777);
    }
}

function rmDirectory($dest)
{
    removeRessource($dest);
}

function removeRessource($_target)
{

    //file?
    if (is_file($_target)) {
        if (is_writable($_target)) {
            if (@unlink($_target)) {
                return true;
            }
        }

        return false;
    }

    //dir?
    if (is_dir($_target)) {
        if (is_writeable($_target)) {
            foreach (new DirectoryIterator($_target) as $_res) {
                if ($_res->isDot()) {
                    unset($_res);
                    continue;
                }

                if ($_res->isFile()) {
                    removeRessource($_res->getPathName());
                } elseif ($_res->isDir()) {
                    removeRessource($_res->getRealPath());
                }

                unset($_res);
            }

            if (@rmdir($_target)) {
                return true;
            }
        }

        return false;
    }
}

// File Manager Functions

// +--------------------------------------------------
// | Internationalization
// +--------------------------------------------------
function et($tag)
{
    global $lang;
    // English
    $en['Version'] = 'Version';
    $en['DocRoot'] = 'Document Root';
    $en['FLRoot'] = 'File Manager Root';
    $en['Name'] = 'Name';
    $en['And'] = 'and';
    $en['Enter'] = 'Enter';
    $en['Send'] = 'Send';
    $en['Refresh'] = 'Refresh';
    $en['SaveConfig'] = 'Save Configurations';
    $en['SavePass'] = 'Save Password';
    $en['SaveFile'] = 'Save File';
    $en['Save'] = 'Save';
    $en['Leave'] = 'Leave';
    $en['Edit'] = 'Edit';
    $en['View'] = 'View';
    $en['Config'] = 'Config';
    $en['Ren'] = 'Rename';
    $en['Rem'] = 'Delete';
    $en['Compress'] = 'Compress';
    $en['Decompress'] = 'Decompress';
    $en['ResolveIDs'] = 'Resolve IDs';
    $en['Move'] = 'Move';
    $en['Copy'] = 'Copy';
    $en['ServerInfo'] = 'Server Info';
    $en['CreateDir'] = 'Create Directory';
    $en['CreateArq'] = 'Create File';
    $en['ExecCmd'] = 'Execute Command';
    $en['Upload'] = 'Upload';
    $en['UploadEnd'] = 'Upload Finished';
    $en['Perms'] = 'Perm';
    $en['Owner'] = 'Owner';
    $en['Group'] = 'Group';
    $en['Other'] = 'Other';
    $en['Size'] = 'Size';
    $en['Date'] = 'Date';
    $en['Type'] = 'Type';
    $en['Free'] = 'free';
    $en['Shell'] = 'Shell';
    $en['Read'] = 'Read';
    $en['Write'] = 'Write';
    $en['Exec'] = 'Execute';
    $en['Apply'] = 'Apply';
    $en['StickyBit'] = 'Sticky Bit';
    $en['Pass'] = 'Password';
    $en['Lang'] = 'Language';
    $en['File'] = 'File';
    $en['File_s'] = 'file(s)';
    $en['Dir_s'] = 'directory(s)';
    $en['To'] = 'to';
    $en['Destination'] = 'Destination';
    $en['Configurations'] = 'Configurations';
    $en['JSError'] = 'JavaScript Error';
    $en['NoSel'] = 'There are no selected itens';
    $en['SelDir'] = 'Select the destination directory on the left tree';
    $en['TypeDir'] = 'Enter the directory name';
    $en['TypeArq'] = 'Enter the file name';
    $en['TypeCmd'] = 'Enter the command';
    $en['TypeArqComp'] = 'Enter the file name.\\nThe extension will define the compression type.\\nEx:\\nnome.zip\\nnome.tar\\nnome.bzip\\nnome.gzip';
    $en['RemSel'] = 'DELETE selected itens';
    $en['NoDestDir'] = 'There is no selected destination directory';
    $en['DestEqOrig'] = 'Origin and destination directories are equal';
    $en['InvalidDest'] = 'Destination directory is invalid';
    $en['NoNewPerm'] = 'New permission not set';
    $en['CopyTo'] = 'COPY to';
    $en['MoveTo'] = 'MOVE to';
    $en['AlterPermTo'] = 'CHANGE PERMISSIONS to';
    $en['ConfExec'] = 'Confirm EXECUTE';
    $en['ConfRem'] = 'Confirm DELETE';
    $en['EmptyDir'] = 'Empty directory';
    $en['IOError'] = 'I/O Error';
    $en['FileMan'] = 'PHP File Manager';
    $en['TypePass'] = 'Enter the password';
    $en['InvPass'] = 'Invalid Password';
    $en['ReadDenied'] = 'Read Access Denied';
    $en['FileNotFound'] = 'File not found';
    $en['AutoClose'] = 'Close on Complete';
    $en['OutDocRoot'] = 'File beyond DOCUMENT_ROOT';
    $en['NoCmd'] = 'Error: Command not informed';
    $en['ConfTrySave'] = 'File without write permisson.\\nTry to save anyway';
    $en['ConfSaved'] = 'Configurations saved';
    $en['PassSaved'] = 'Password saved';
    $en['FileDirExists'] = 'File or directory already exists';
    $en['NoPhpinfo'] = 'Function phpinfo disabled';
    $en['NoReturn'] = 'no return';
    $en['FileSent'] = 'File sent';
    $en['SpaceLimReached'] = 'Space limit reached';
    $en['InvExt'] = 'Invalid extension';
    $en['FileNoOverw'] = 'File could not be overwritten';
    $en['FileOverw'] = 'File overwritten';
    $en['FileIgnored'] = 'File ignored';
    $en['ChkVer'] = 'Check sf.net for new version';
    $en['ChkVerAvailable'] = 'New version, click here to begin download!!';
    $en['ChkVerNotAvailable'] = 'No new version available. :(';
    $en['ChkVerError'] = 'Connection Error.';
    $en['Website'] = 'Website';
    $en['SendingForm'] = 'Sending files, please wait';
    $en['NoFileSel'] = 'No file selected';
    $en['SelAll'] = 'All';
    $en['SelNone'] = 'None';
    $en['SelInverse'] = 'Inverse';
    $en['Selected_s'] = 'selected';
    $en['Total'] = 'total';
    $en['Partition'] = 'Partition';
    $en['RenderTime'] = 'Time to render this page';
    $en['Seconds'] = 'sec';
    $en['ErrorReport'] = 'Error Reporting';

    // Portuguese
    $pt['Version'] = 'Vers�o';
    $pt['DocRoot'] = 'Document Root';
    $pt['FLRoot'] = 'File Manager Root';
    $pt['Name'] = 'Nome';
    $pt['And'] = 'e';
    $pt['Enter'] = 'Entrar';
    $pt['Send'] = 'Enviar';
    $pt['Refresh'] = 'Atualizar';
    $pt['SaveConfig'] = 'Salvar Configura��es';
    $pt['SavePass'] = 'Salvar Senha';
    $pt['SaveFile'] = 'Salvar Arquivo';
    $pt['Save'] = 'Salvar';
    $pt['Leave'] = 'Sair';
    $pt['Edit'] = 'Editar';
    $pt['View'] = 'Visualizar';
    $pt['Config'] = 'Config';
    $pt['Ren'] = 'Renomear';
    $pt['Rem'] = 'Apagar';
    $pt['Compress'] = 'Compactar';
    $pt['Decompress'] = 'Descompactar';
    $pt['ResolveIDs'] = 'Resolver IDs';
    $pt['Move'] = 'Mover';
    $pt['Copy'] = 'Copiar';
    $pt['ServerInfo'] = 'Server Info';
    $pt['CreateDir'] = 'Criar Diret�rio';
    $pt['CreateArq'] = 'Criar Arquivo';
    $pt['ExecCmd'] = 'Executar Comando';
    $pt['Upload'] = 'Upload';
    $pt['UploadEnd'] = 'Upload Terminado';
    $pt['Perms'] = 'Permiss�es';
    $pt['Owner'] = 'Dono';
    $pt['Group'] = 'Grupo';
    $pt['Other'] = 'Outros';
    $pt['Size'] = 'Tamanho';
    $pt['Date'] = 'Data';
    $pt['Type'] = 'Tipo';
    $pt['Free'] = 'livre';
    $pt['Shell'] = 'Shell';
    $pt['Read'] = 'Ler';
    $pt['Write'] = 'Escrever';
    $pt['Exec'] = 'Executar';
    $pt['Apply'] = 'Aplicar';
    $pt['StickyBit'] = 'Sticky Bit';
    $pt['Pass'] = 'Senha';
    $pt['Lang'] = 'Idioma';
    $pt['File'] = 'Arquivo';
    $pt['File_s'] = 'arquivo(s)';
    $pt['Dir_s'] = 'diretorio(s)';
    $pt['To'] = 'para';
    $pt['Destination'] = 'Destino';
    $pt['Configurations'] = 'Configura��es';
    $pt['JSError'] = 'Erro de JavaScript';
    $pt['NoSel'] = 'N�o h� itens selecionados';
    $pt['SelDir'] = 'Selecione o diret�rio de destino na �rvore a esquerda';
    $pt['TypeDir'] = 'Digite o nome do diret�rio';
    $pt['TypeArq'] = 'Digite o nome do arquivo';
    $pt['TypeCmd'] = 'Digite o commando';
    $pt['TypeArqComp'] = 'Digite o nome do arquivo.\\nA extens�o determina o tipo de compacta��o.\\nEx:\\nnome.zip\\nnome.tar\\nnome.bzip\\nnome.gzip';
    $pt['RemSel'] = 'APAGAR itens selecionados';
    $pt['NoDestDir'] = 'N�o h� um diret�rio de destino selecionado';
    $pt['DestEqOrig'] = 'Diret�rio de origem e destino iguais';
    $pt['InvalidDest'] = 'Diret�rio de destino inv�lido';
    $pt['NoNewPerm'] = 'Nova permiss�o n�o foi setada';
    $pt['CopyTo'] = 'COPIAR para';
    $pt['MoveTo'] = 'MOVER para';
    $pt['AlterPermTo'] = 'ALTERAR PERMISS�ES para';
    $pt['ConfExec'] = 'Confirma EXECUTAR';
    $pt['ConfRem'] = 'Confirma APAGAR';
    $pt['EmptyDir'] = 'Diret�rio vazio';
    $pt['IOError'] = 'Erro de E/S';
    $pt['FileMan'] = 'PHP File Manager';
    $pt['TypePass'] = 'Digite a senha';
    $pt['InvPass'] = 'Senha Inv�lida';
    $pt['ReadDenied'] = 'Acesso de leitura negado';
    $pt['FileNotFound'] = 'Arquivo n�o encontrado';
    $pt['AutoClose'] = 'Fechar Automaticamente';
    $pt['OutDocRoot'] = 'Arquivo fora do DOCUMENT_ROOT';
    $pt['NoCmd'] = 'Erro: Comando n�o informado';
    $pt['ConfTrySave'] = 'Arquivo sem permiss�o de escrita.\\nTentar salvar assim mesmo';
    $pt['ConfSaved'] = 'Configura��es salvas';
    $pt['PassSaved'] = 'Senha salva';
    $pt['FileDirExists'] = 'Arquivo ou diret�rio j� existe';
    $pt['NoPhpinfo'] = 'Fun��o phpinfo desabilitada';
    $pt['NoReturn'] = 'sem retorno';
    $pt['FileSent'] = 'Arquivo enviado';
    $pt['SpaceLimReached'] = 'Limite de espa�o alcan�ado';
    $pt['InvExt'] = 'Extens�o inv�lida';
    $pt['FileNoOverw'] = 'Arquivo n�o pode ser sobreescrito';
    $pt['FileOverw'] = 'Arquivo sobreescrito';
    $pt['FileIgnored'] = 'Arquivo omitido';
    $pt['ChkVer'] = 'Verificar sf.net por nova vers�o';
    $pt['ChkVerAvailable'] = 'Nova vers�o, clique aqui para iniciar download!!';
    $pt['ChkVerNotAvailable'] = 'N�o h� nova vers�o dispon�vel :(';
    $pt['ChkVerError'] = 'Erro de conex�o.';
    $pt['Website'] = 'Website';
    $pt['SendingForm'] = 'Enviando arquivos, aguarde';
    $pt['NoFileSel'] = 'Nenhum arquivo selecionado';
    $pt['SelAll'] = 'Tudo';
    $pt['SelNone'] = 'Nada';
    $pt['SelInverse'] = 'Inverso';
    $pt['Selected_s'] = 'selecionado(s)';
    $pt['Total'] = 'total';
    $pt['Partition'] = 'Parti��o';
    $pt['RenderTime'] = 'Tempo para gerar esta p�gina';
    $pt['Seconds'] = 'seg';
    $pt['ErrorReport'] = 'Error Reporting';

    $lang_ = $$lang;
    if (isset($lang_[$tag]))
        return htmlencode($lang_[$tag]);
    else
        return "undefined";
}
// +--------------------------------------------------
// | File System
// +--------------------------------------------------
function total_size($arg)
{
    $total = 0;
    if (file_exists($arg)) {
        if (is_dir($arg)) {
            $handle = opendir($arg);
            while ($aux = readdir($handle)) {
                if ($aux != "." && $aux != "..")
                    $total += total_size($arg . "/" . $aux);
            }
            closedir($handle);
        } else
            $total = filesize($arg);
    }
    return $total;
}
function total_delete($arg)
{
    if (file_exists($arg)) {
        chmod($arg, 0777);
        if (is_dir($arg)) {
            $handle = opendir($arg);
            while ($aux = readdir($handle)) {
                if ($aux != "." && $aux != "..")
                    total_delete($arg . "/" . $aux);
            }
            closedir($handle);
            rmdir($arg);
        } else
            unlink($arg);
    }
}
function total_copy($orig, $dest)
{
    $ok = true;
    if (file_exists($orig)) {
        if (is_dir($orig)) {
            mkDirectory($dest);
            $handle = opendir($orig);
            while (($aux = readdir($handle)) && ($ok)) {
                if ($aux != "." && $aux != "..")
                    $ok = total_copy($orig . "/" . $aux, $dest . "/" . $aux);
            }
            closedir($handle);
        } else
            $ok = copy((string) $orig, (string) $dest);
    }
    return $ok;
}
function total_move($orig, $dest)
{
    // Just why doesn't it has a MOVE alias?!
    return rename((string) $orig, (string) $dest);
}
function download()
{
    global $dir_atual, $filename;
    $file = $dir_atual . $filename;
    if (file_exists($file)) {
        $is_proibido = false;
        foreach ($download_ext_filter as $key => $ext) {
            if (eregi($ext, $filename)) {
                $is_proibido = true;
                break;
            }
        }
        if (!$is_proibido) {
            $size = filesize($file);
            header("Content-Type: application/save");
            header("Content-Length: $size");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Transfer-Encoding: binary");
            if ($fh = fopen("$file", "rb")) {
                fpassthru($fh);
                fclose($fh);
            } else
                alert(et('ReadDenied') . ": " . $file);
        } else
            alert(et('ReadDenied') . ": " . $file);
    } else
        alert(et('FileNotFound') . ": " . $file);
}
function execute()
{
    global $cmd;
    header("Content-type: text/plain");
    if (strlen($cmd)) {
        echo "# " . $cmd . "\n";
        exec($cmd, $mat);
        if (count($mat))
            echo trim(implode("\n", $mat));
        else
            echo "exec(\"$cmd\") " . et('NoReturn') . "...";
    } else
        echo et('NoCmd');
}
function save_upload($temp_file, $filename, $dir_dest)
{
    global $upload_ext_filter;
    $filename = remove_acentos($filename);
    $file = $dir_dest . $filename;
    $filesize = filesize($temp_file);
    $is_proibido = false;
    foreach ($upload_ext_filter as $key => $ext) {
        if (eregi($ext, $filename)) {
            $is_proibido = true;
            break;
        }
    }
    if (!$is_proibido) {
        if (!limite($filesize)) {
            if (file_exists($file)) {
                if (unlink($file)) {
                    if (copy($temp_file, $file)) {
                        chmod($file, 0777);
                        $out = 6;
                    } else
                        $out = 2;
                } else
                    $out = 5;
            } else {
                if (copy($temp_file, $file)) {
                    chmod($file, 0777);
                    $out = 1;
                } else
                    $out = 2;
            }
        } else
            $out = 3;
    } else
        $out = 4;
    return $out;
}
function zip_extract()
{
    global $cmd_arg, $dir_atual, $islinux;
    $zip = zip_open($dir_atual . $cmd_arg);
    if ($zip) {
        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_filesize($zip_entry)) {
                $complete_path = $path . dirname(zip_entry_name($zip_entry));
                $complete_name = $path . zip_entry_name($zip_entry);
                if (!file_exists($complete_path)) {
                    $tmp = '';
                    $kAry = explode('/', $complete_path);
                    foreach ($kAry as $k) {
                        $tmp .= $k . '/';
                        if (!file_exists($tmp)) {
                            mkDirectory($dir_atual . $tmp);
                        }
                    }
                }
                if (zip_entry_open($zip, $zip_entry, "r")) {
                    if ($fd = fopen($dir_atual . $complete_name, 'w')) {
                        fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                        fclose($fd);
                    } else
                        echo "fopen($dir_atual.$complete_name) error<br>";
                    zip_entry_close($zip_entry);
                } else
                    echo "zip_entry_open($zip,$zip_entry) error<br>";
            }
        }
        zip_close($zip);
    }
}
// +--------------------------------------------------
// | Data Formating
// +--------------------------------------------------
function htmlencode($str)
{
    return htmlentities($str);
}
// html_entity_decode() replacement
function rep($x, $y)
{
    if ($x) {
        $aux = "";
        for ($a = 1; $a <= $x; $a++)
            $aux .= $y;
        return $aux;
    } else
        return "";
}
function strzero($arg1, $arg2)
{
    if (strstr($arg1, "-") == false) {
        $aux = intval($arg2) - strlen($arg1);
        if ($aux)
            return rep($aux, "0") . $arg1;
        else
            return $arg1;
    } else {
        return "[$arg1]";
    }
}
function replace_double($sub, $str)
{
    $out = str_replace($sub . $sub, $sub, $str);
    while (strlen($out) != strlen($str)) {
        $str = $out;
        $out = str_replace($sub . $sub, $sub, $str);
    }
    return $out;
}
function remove_acentos($str)
{
    $str = trim($str);
    $str = strtr(
        $str,
        "��������������������������������������������������������������!@#%&*()[]{}+=?",
        "YuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy_______________"
    );
    $str = str_replace("..", "", str_replace("/", "", str_replace("\\", "", str_replace("\$", "", $str))));
    return $str;
}
function formatpath($str)
{
    global $islinux;
    $str = trim($str);
    $str = str_replace("..", "", str_replace("\\", "/", str_replace("\$", "", $str)));
    $done = false;
    while (!$done) {
        $str2 = str_replace("//", "/", $str);
        if (strlen($str) == strlen($str2))
            $done = true;
        else
            $str = $str2;
    }
    $tam = strlen($str);
    if ($tam) {
        $last_char = $tam - 1;
        if ($str[$last_char] != "/")
            $str .= "/";
        if (!$islinux)
            $str = ucfirst($str);
    }
    return $str;
}
function array_csort()
{
    $args = func_get_args();
    $marray = array_shift($args);
    $msortline = "return(array_multisort(";
    foreach ($args as $arg) {
        $i++;
        if (is_string($arg)) {
            foreach ($marray as $row) {
                $sortarr[$i][] = $row[$arg];
            }
        } else {
            $sortarr[$i] = $arg;
        }
        $msortline .= "\$sortarr[" . $i . "],";
    }
    $msortline .= "\$marray));";
    eval ($msortline);
    return $marray;
}
function show_perms($in_Perms)
{
    $sP = "<b>";
    if ($in_Perms & 0x1000)
        $sP .= 'p';            // FIFO pipe
    elseif ($in_Perms & 0x2000)
        $sP .= 'c';        // Character special
    elseif ($in_Perms & 0x4000)
        $sP .= 'd';        // Directory
    elseif ($in_Perms & 0x6000)
        $sP .= 'b';        // Block special
    elseif ($in_Perms & 0x8000)
        $sP .= '&minus;';    // Regular
    elseif ($in_Perms & 0xA000)
        $sP .= 'l';        // Symbolic Link
    elseif ($in_Perms & 0xC000)
        $sP .= 's';        // Socket
    else
        $sP .= 'u';                              // UNKNOWN
    $sP .= "</b>";
    // owner - group - others
    $sP .= (($in_Perms & 0x0100) ? 'r' : '&minus;') . (($in_Perms & 0x0080) ? 'w' : '&minus;') . (($in_Perms & 0x0040) ? (($in_Perms & 0x0800) ? 's' : 'x') : (($in_Perms & 0x0800) ? 'S' : '&minus;'));
    $sP .= (($in_Perms & 0x0020) ? 'r' : '&minus;') . (($in_Perms & 0x0010) ? 'w' : '&minus;') . (($in_Perms & 0x0008) ? (($in_Perms & 0x0400) ? 's' : 'x') : (($in_Perms & 0x0400) ? 'S' : '&minus;'));
    $sP .= (($in_Perms & 0x0004) ? 'r' : '&minus;') . (($in_Perms & 0x0002) ? 'w' : '&minus;') . (($in_Perms & 0x0001) ? (($in_Perms & 0x0200) ? 't' : 'x') : (($in_Perms & 0x0200) ? 'T' : '&minus;'));
    return $sP;
}
function formatsize($arg)
{
    if ($arg > 0) {
        $j = 0;
        $ext = array(" bytes", " Kb", " Mb", " Gb", " Tb");
        while ($arg >= pow(1024, $j))
            ++$j;
        return round($arg / pow(1024, $j - 1) * 100) / 100 . $ext[$j - 1];
    } else
        return "0 Mb";
}
function getsize($file)
{
    return formatsize(filesize($file));
}
function limite($new_filesize = 0)
{
    global $fm_root_atual;
    global $quota_mb;
    if ($quota_mb) {
        $total = total_size($fm_root_atual);
        if (floor(($total + $new_filesize) / (1024 * 1024)) > $quota_mb)
            return true;
    }
    return false;
}
function getuser($arg)
{
    global $mat_passwd;
    $aux = "x:" . trim($arg) . ":";
    for ($x = 0; $x < count($mat_passwd); $x++) {
        if (strstr($mat_passwd[$x], $aux)) {
            $mat = explode(":", $mat_passwd[$x]);
            return $mat[0];
        }
    }
    return $arg;
}
function getgroup($arg)
{
    global $mat_group;
    $aux = "x:" . trim($arg) . ":";
    for ($x = 0; $x < count($mat_group); $x++) {
        if (strstr($mat_group[$x], $aux)) {
            $mat = explode(":", $mat_group[$x]);
            return $mat[0];
        }
    }
    return $arg;
}

function reloadframe($ref, $frame_number, $plus = "")
{
    global $dir_atual, $path_info;
    echo "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        " . $ref . ".frame" . $frame_number . ".location.href='" . $path_info["basename"] . "?frame=" . $frame_number . "&dir_atual=" . $dir_atual . $plus . "';
        //-->
        </script>
        ";
}
function alert($arg)
{
    echo "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        alert('$arg');
        //-->
        </script>
        ";
}
function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}
function upload_form()
{
    global $_FILES, $dir_atual, $dir_dest, $fechar, $quota_mb, $path_info;
    $num_uploads = 5;
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">";
    if (count($_FILES) == 0) {
        echo "
                <table height=\"100%\" border=0 cellspacing=0 cellpadding=2 align=center>
                <form name=\"upload_form\" action=\"" . $path_info["basename"] . "\" method=\"post\" ENCTYPE=\"multipart/form-data\">
                <input type=hidden name=dir_dest value=\"$dir_atual\">
                <input type=hidden name=action value=10>
                <tr><th colspan=2>" . et('Upload') . "</th></tr>
                <tr><td align=right><b>" . et('Destination') . ":<td><b><nobr>$dir_atual</nobr>";
        for ($x = 0; $x < $num_uploads; $x++) {
            echo "<tr><td width=1 align=right><b>" . et('File') . ":<td><nobr><input type=\"file\" name=\"file$x\"></nobr>";
            $test_js .= "(document.upload_form.file$x.value.length>0)||";
        }
        echo "
                <input type=button value=\"" . et('Send') . "\" onclick=\"test_upload_form()\"></nobr>
                <tr><td>�<td><input type=checkbox name=fechar value=\"1\"> <a href=\"JavaScript:troca();\">" . et('AutoClose') . "</a>
                <tr><td colspan=2>�</td></tr>
                </form>
                </table>
                <script language=\"Javascript\" type=\"text/javascript\">
                <!--
                function troca(){
                if(document.upload_form.fechar.checked){document.upload_form.fechar.checked=false;}else{document.upload_form.fechar.checked=true;}
                }
                foi = false;
                function test_upload_form(){
                if(" . substr($test_js, 0, strlen($test_js) - 2) . "){
                if (foi) alert('" . et('SendingForm') . "...');
                else {
                foi = true;
                document.upload_form.submit();
                }
                } else alert('" . et('NoFileSel') . ".');
                }
                window.moveTo((window.screen.width-400)/2,((window.screen.height-200)/2)-20);
                //-->
                </script>";
    } else {
        $out = "<tr><th colspan=2>" . et('UploadEnd') . "</th></tr>
                <tr><th colspan=2><nobr>" . et('Destination') . ": $dir_dest</nobr>";
        $upload_num = $num_uploads;

        for ($x = 0; $x < $num_uploads; $x++) {
            $temp_file = $_FILES["file" . $x]["tmp_name"];
            $filename = $_FILES["file" . $x]["name"];
            if (strlen($filename))
                $resul = save_upload($temp_file, $filename, $dir_dest);
            else
                $resul = 7;
            switch ($resul) {
                case 1:
                    // Kimba database upload entry
                    $out .= "<tr><td><b>" . strzero($x + 1, 3) . ".<font color=green><b> " . et('FileSent') . ":</font><td>" . $filename . "</td></tr>\n";
                    break;
                case 2:
                    $out .= "<tr><td colspan=2><font color=red><b>" . et('IOError') . "</font></td></tr>\n";
                    $x = $upload_num;
                    break;
                case 3:
                    $out .= "<tr><td colspan=2><font color=red><b>" . et('SpaceLimReached') . " ($quota_mb Mb)</font></td></tr>\n";
                    $x = $upload_num;
                    break;
                case 4:
                    $out .= "<tr><td><b>" . strzero($x + 1, 3) . ".<font color=red><b> " . et('InvExt') . ":</font><td>" . $filename . "</td></tr>\n";
                    break;
                case 5:
                    $out .= "<tr><td><b>" . strzero($x + 1, 3) . ".<font color=red><b> " . et('FileNoOverw') . "</font><td>" . $filename . "</td></tr>\n";
                    break;
                case 6:
                    $out .= "<tr><td><b>" . strzero($x + 1, 3) . ".<font color=green><b> " . et('FileOverw') . ":</font><td>" . $filename . "</td></tr>\n";
                    break;
                case 7:
                    $out .= "<tr><td colspan=2><b>" . strzero($x + 1, 3) . ".<font color=red><b> " . et('FileIgnored') . "</font></td></tr>\n";
            }
        }
        if ($fechar) {
            echo "
                        <script language=\"Javascript\" type=\"text/javascript\">
                        <!--
                        window.close();
                        //-->
                        </script>
                        ";
        } else {
            echo "
                        <table height=\"100%\" border=0 cellspacing=0 cellpadding=2 align=center>
                        $out
                        <tr><td colspan=2>�</td></tr>
                        </table>
                        <script language=\"Javascript\" type=\"text/javascript\">
                        <!--
                        window.focus();
                        //-->
                        </script>
                        ";
        }
    }
    echo "</body>\n</html>";
}
function chmod_form()
{
    global $fm_root_atual, $dir_atual, $quota_mb, $resolveIDs, $order_dir_list_by, $islinux, $cmd_name, $ip, $is_reachable, $path_info, $fm_color;
    global $curDir, $companyDir, $awardWorkDir, $webAdmin, $teamManager;
    $aux = "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        function octalchange()
        {
        var val = document.chmod_form.t_total.value;
        var stickybin = parseInt(val.charAt(0)).toString(2);
        var ownerbin = parseInt(val.charAt(1)).toString(2);
        while (ownerbin.length<3) { ownerbin=\"0\"+ownerbin; };
        var groupbin = parseInt(val.charAt(2)).toString(2);
        while (groupbin.length<3) { groupbin=\"0\"+groupbin; };
        var otherbin = parseInt(val.charAt(3)).toString(2);
        while (otherbin.length<3) { otherbin=\"0\"+otherbin; };
        document.chmod_form.sticky.checked = parseInt(stickybin.charAt(0));
        document.chmod_form.owner4.checked = parseInt(ownerbin.charAt(0));
        document.chmod_form.owner2.checked = parseInt(ownerbin.charAt(1));
        document.chmod_form.owner1.checked = parseInt(ownerbin.charAt(2));
        document.chmod_form.group4.checked = parseInt(groupbin.charAt(0));
        document.chmod_form.group2.checked = parseInt(groupbin.charAt(1));
        document.chmod_form.group1.checked = parseInt(groupbin.charAt(2));
        document.chmod_form.other4.checked = parseInt(otherbin.charAt(0));
        document.chmod_form.other2.checked = parseInt(otherbin.charAt(1));
        document.chmod_form.other1.checked = parseInt(otherbin.charAt(2));
        calc_chmod(1);
        };
        
        function calc_chmod(nototals)
        {
        var users = new Array(\"owner\", \"group\", \"other\");
        var totals = new Array(\"\",\"\",\"\");
        var syms = new Array(\"\",\"\",\"\");
        
        for (var i=0; i<users.length; i++)
        {
        var user=users[i];
        var field4 = user + \"4\";
        var field2 = user + \"2\";
        var field1 = user + \"1\";
        var symbolic = \"sym_\" + user;
        var number = 0;
        var sym_string = \"\";
        var sticky = \"0\";
        var sticky_sym = \" \";
        if (document.chmod_form.sticky.checked){
        sticky = \"1\";
        sticky_sym = \"t\";
        }
        if (document.chmod_form[field4].checked == true) { number += 4; }
        if (document.chmod_form[field2].checked == true) { number += 2; }
        if (document.chmod_form[field1].checked == true) { number += 1; }
        
        if (document.chmod_form[field4].checked == true) {
        sym_string += \"r\";
        } else {
        sym_string += \"-\";
        }
        if (document.chmod_form[field2].checked == true) {
        sym_string += \"w\";
        } else {
        sym_string += \"-\";
        }
        if (document.chmod_form[field1].checked == true) {
        sym_string += \"x\";
        } else {
        sym_string += \"-\";
        }
        
        totals[i] = totals[i]+number;
        syms[i] =  syms[i]+sym_string;
        
        };
        if (!nototals) document.chmod_form.t_total.value = sticky + totals[0] + totals[1] + totals[2];
        document.chmod_form.sym_total.value = syms[0] + syms[1] + syms[2] + sticky_sym;
        }
        function troca(){
        if(document.chmod_form.sticky.checked){document.chmod_form.sticky.checked=false;}else{document.chmod_form.sticky.checked=true;}
        }
        
        window.onload=octalchange
        window.moveTo((window.screen.width-400)/2,((window.screen.height-200)/2)-20);
        //-->
        </script>
        ";
    html_header($aux);
    echo "<body marginwidth=\"0\" marginheight=\"0\">
        <form name=\"chmod_form\">
        <TABLE BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"4\" ALIGN=CENTER  bgcolor=\"#" . $fm_color['Bg'] . "\">
        <tr  bgcolor=\"#" . $fm_color['Bg'] . "\"><th colspan=4  bgcolor=\"#" . $fm_color['Bg'] . "\">" . et('Perms') . "</th></tr>
        <TR ALIGN=\"LEFT\" VALIGN=\"MIDDLE\"  bgcolor=\"#" . $fm_color['Bg'] . "\">
        <TD  bgcolor=\"#" . $fm_color['Bg'] . "\"><input type=\"text\" name=\"t_total\" value=\"0777\" size=\"4\" onKeyUp=\"octalchange()\"> </TD>
        <TD  bgcolor=\"#" . $fm_color['Bg'] . "\"><input type=\"text\" name=\"sym_total\" value=\"\" size=\"12\" READONLY=\"1\"></TD>
        </TR>
        </TABLE>
        <table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" ALIGN=CENTER  bgcolor=\"#" . $fm_color['Bg'] . "\">
        <tr  bgcolor=\"#" . $fm_color['Bg'] . "\">
        <td WIDTH=\"60\" align=\"left\"  bgcolor=\"#" . $fm_color['Bg'] . "\"> </td>
        <td WIDTH=\"55\" align=\"center\" style=\"color:#" . $fm_color['Text'] . "\"><b>" . et('Owner') . "
        </b></td>
        <td WIDTH=\"55\" align=\"center\" style=\"color:#" . $fm_color['Text'] . "\"><b>" . et('Group') . "
        </b></td>
        <td WIDTH=\"55\" align=\"center\" style=\"color:#" . $fm_color['Text'] . "\"><b>" . et('Other') . "
        <b></td>
        </tr>
        <tr bgcolor=\"#" . $fm_color['Bg'] . "\">
        <td WIDTH=\"60\" align=\"left\" nowrap BGCOLOR=\"#" . $fm_color['Bg'] . "\">" . et('Read') . "</td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\">
        <input type=\"checkbox\" name=\"owner4\" value=\"4\" onclick=\"calc_chmod()\">
        </td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\"><input type=\"checkbox\" name=\"group4\" value=\"4\" onclick=\"calc_chmod()\">
        </td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\">
        <input type=\"checkbox\" name=\"other4\" value=\"4\" onclick=\"calc_chmod()\">
        </td>
        </tr>
        <tr bgcolor=\"#" . $fm_color['Bg'] . "\">
        <td WIDTH=\"60\" align=\"left\" nowrap BGCOLOR=\"#" . $fm_color['Bg'] . "\">" . et('Write') . "</td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\">
        <input type=\"checkbox\" name=\"owner2\" value=\"2\" onclick=\"calc_chmod()\"></td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\"><input type=\"checkbox\" name=\"group2\" value=\"2\" onclick=\"calc_chmod()\">
        </td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\">
        <input type=\"checkbox\" name=\"other2\" value=\"2\" onclick=\"calc_chmod()\">
        </td>
        </tr>
        <tr bgcolor=\"#" . $fm_color['Bg'] . "\">
        <td WIDTH=\"60\" align=\"left\" nowrap BGCOLOR=\"#" . $fm_color['Bg'] . "\">" . et('Exec') . "</td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\">
        <input type=\"checkbox\" name=\"owner1\" value=\"1\" onclick=\"calc_chmod()\">
        </td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\"><input type=\"checkbox\" name=\"group1\" value=\"1\" onclick=\"calc_chmod()\">
        </td>
        <td WIDTH=\"55\" align=\"center\" bgcolor=\"#" . $fm_color['Bg'] . "\">
        <input type=\"checkbox\" name=\"other1\" value=\"1\" onclick=\"calc_chmod()\">
        </td>
        </tr>
        </TABLE>
        <TABLE BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"4\" ALIGN=CENTER>
        <tr><td colspan=2><input type=checkbox name=sticky value=\"1\" onclick=\"calc_chmod()\"> <a href=\"JavaScript:troca();\">" . et('StickyBit') . "</a><td colspan=2 align=right><input type=button value=\"" . et('Apply') . "\" onClick=\"window.opener.set_chmod_arg(document.chmod_form.t_total.value); window.close();\"></tr>
        </table>
        </form>
        </body>\n</html>";
}
function view()
{
    global $doc_root, $path_info, $url_info, $dir_atual, $islinux, $filename, $is_reachable;
    //    html_header();
    html_popup_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">\n";
    if ($is_reachable) {
        //        $url = "http://".$_server["SERVER_NAME"]."/".$filename;
        $url = $url_info["scheme"] . "://" . $url_info["host"];
        if ($url == "://") {
            $url = "http://" . $_SERVER["HTTP_HOST"];
            $url .= str_replace($doc_root, "", $dir_atual) . $filename;
        } else {
            if (strlen($url_info["port"]))
                $url .= ":" . $url_info["port"];
            // Malditas variaveis de sistema!! No windows doc_root � sempre em lowercase... cad� o str_ireplace() ??
            $url .= str_replace($doc_root, "", $dir_atual) . $filename;
        }
        echo "
                <script language=\"Javascript\" type=\"text/javascript\">
                <!--
                window.location='" . $url . "';
                //-->
                </script>";
    } else {
        echo "<script language=\"Javascript\" type=\"text/javascript\">
                <!--
                alert('" . et('OutDocRoot') . ":\\n" . $doc_root . "\\n');
                window.close();
                //-->
                </script>";
    }
    echo "
        </body>\n</html>";
}
function edit_file_form()
{
    global $dir_atual, $filename, $file_data, $save_file, $path_info;
    $file = $dir_atual . $filename;
    if ($save_file) {
        $fh = fopen($file, "w");
        fputs($fh, $file_data, strlen($file_data));
        fclose($fh);
    }
    $fh = fopen($file, "r");
    $file_data = fread($fh, filesize($file));
    fclose($fh);
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">
        <table border=0 cellspacing=0 cellpadding=5 align=center>
        <form name=\"edit_form\" action=\"" . $path_info["basename"] . "\" method=\"post\">
        <input type=hidden name=action value=\"7\">
        <input type=hidden name=save_file value=\"1\">
        <input type=hidden name=dir_atual value=\"$dir_atual\">
        <input type=hidden name=filename value=\"$filename\">
        <tr><th  bgcolor=\"#" . $fm_color['Bg'] . "\" colspan=2>" . $file . "</th></tr>
        <tr><td  bgcolor=\"#" . $fm_color['Bg'] . "\" colspan=2><textarea  bgcolor=\"#" . $fm_color['Text'] . "\" name=file_data rows=33 cols=105>" . htmlencode($file_data) . "</textarea></td></tr>
        <tr><td  bgcolor=\"#" . $fm_color['Bg'] . "\"><input type=button value=\"" . et('Refresh') . "\" onclick=\"document.edit_form_refresh.submit()\"></td>
        <td  bgcolor=\"#" . $fm_color['Bg'] . "\" align=right><input type=button value=\"" . et('SaveFile') . "\" onclick=\"go_save()\"></td></tr>
        </form>
        <form name=\"edit_form_refresh\" action=\"" . $path_info["basename"] . "\" method=\"post\">
        <input type=hidden name=action value=\"7\">
        <input type=hidden name=dir_atual value=\"$dir_atual\">
        <input type=hidden name=filename value=\"$filename\">
        </form>
        </table>
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
        function go_save(){";
    if (is_writable($file)) {
        echo "
                document.edit_form.submit();";
    } else {
        echo "
                if(confirm('" . et('ConfTrySave') . " ?')) document.edit_form.submit();";
    }
    echo "
        }
        //-->
        </script>
        </body>\n</html>";
}
function config_form()
{
    global $curDir, $companyDir, $awardWorkDir, $webAdmin, $teamManager;
    global $cfg;
    global $dir_atual, $script_filename, $doc_root, $path_info, $fm_root_atual, $lang, $error_reporting, $version;
    global $config_action, $newsenha, $newlang, $newerror, $newfm_root;
    $Warning = "";
    $ChkVerWarning = "<tr><td align=right>�";

    switch ($config_action) {
        case 1:
            if ($fh = fopen("http://phpfm.sf.net/latest.php", "r")) {
                $data = "";
                while (!feof($fh))
                    $data .= fread($fh, 1024);
                fclose($fh);
                $data = unserialize($data);

                if (is_array($data) && count($data)) {
                    // sf.net logo
                    $ChkVerWarning .= "<a href=\"JavaScript:open_win('http://sourceforge.net')\"><img src=\"http://sourceforge.net/sflogo.php?group_id=114392&type=1\" width=\"88\" height=\"31\" border=\"0\" alt=\"SourceForge.net Logo\" /></a>";
                    if (str_replace(".", "", $data['version']) > str_replace(".", "", $cfg->data['version']))
                        $ChkVerWarning .= "<td><a href=\"JavaScript:open_win('http://prdownloads.sourceforge.net/phpfm/phpFileManager-" . $data['version'] . ".zip?download')\"><font color=green>" . et('ChkVerAvailable') . "</font></a>";
                    else
                        $ChkVerWarning .= "<td><font color=red>" . et('ChkVerNotAvailable') . "</font>";
                } else {
                    $ChkVerWarning .= "<td><font color=red>" . et('ChkVerError') . "</font>";
                }
            } else {
                $ChkVerWarning .= "<td><font color=red>" . et('ChkVerError') . "</font>";
            }
            break;
        case 2:
            $reload = false;
            if ($cfg->data['lang'] != $newlang) {
                $cfg->data['lang'] = $newlang;
                $lang = $newlang;
                $reload = true;
            }
            if ($cfg->data['error_reporting'] != $newerror) {
                $cfg->data['error_reporting'] = $newerror;
                $error_reporting = $newerror;
                $reload = true;
            }
            $newfm_root = formatpath($newfm_root);
            if ($cfg->data['fm_root'] != $newfm_root) {
                $cfg->data['fm_root'] = $newfm_root;
                if (strlen($newfm_root))
                    $dir_atual = $newfm_root;
                else
                    $dir_atual = $path_info["dirname"] . "/";
                setcookie("fm_root_atual", $newfm_root, 0, "/");
                $reload = true;
            }
            $cfg->save();
            if ($reload) {
                reloadframe("window.opener.parent", 2);
                reloadframe("window.opener.parent", 3);
            }
            $Warning1 = et('ConfSaved') . "...";
            break;
        case 3:
            if ($cfg->data['auth_pass'] != md5($newsenha)) {
                $cfg->data['auth_pass'] = md5($newsenha);
                setcookie("loggedon", md5($newsenha), 0, "/");
            }
            $cfg->save();
            $Warning2 = et('PassSaved') . "...";
            break;
    }
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">\n";
    echo "
        <table border=0 cellspacing=0 cellpadding=5 align=center width=\"100%\">
        <form name=\"config_form\" action=\"" . $path_info["basename"] . "\" method=\"post\">
        <input type=hidden name=action value=2>
        <input type=hidden name=config_action value=0>
        <tr><td colspan=2 align=center><b>" . strtoupper(et('Configurations')) . "</b></td></tr>
        </table>
        <table border=0 cellspacing=0 cellpadding=5 align=center width=\"100%\">
        <tr><td align=right width=\"1%\">" . et('Version') . ":<td>$version</td></tr>
        <tr><td align=right>" . et('Size') . ":<td>" . getsize($script_filename) . "</td></tr>
        <tr><td align=right>" . et('Website') . ":<td><a href=\"JavaScript:open_win('http://phpfm.sf.net')\">http://phpfm.sf.net</a></td></tr>";
    if (strlen($ChkVerWarning))
        echo $ChkVerWarning . $data['warnings'];
    else
        echo "<tr><td align=right>�<td><input type=button value=\"" . et('ChkVer') . "\" onclick=\"test_config_form(1)\">";
    echo "
        <tr><td align=right width=1><nobr>" . et('DocRoot') . ":</nobr><td>" . $doc_root . "</td></tr>
        <tr><td align=right><nobr>" . et('FLRoot') . ":</nobr><td><input type=text size=60 name=newfm_root value=\"" . $cfg->data['fm_root'] . "\" onkeypress=\"enterSubmit(event,'test_config_form(2)')\"></td></tr>
        <tr><td align=right>" . et('Lang') . ":<td><select name=newlang><option value=en>English<option value=pt>Portugu�s</select></td></tr>
        <tr><td align=right>" . et('ErrorReport') . ":<td><select name=newerror><option value=\"\">NONE<option value=\"" . E_ALL . "\">E_ALL<option value=\"" . E_ERROR . "\">E_ERROR<option value=\"" . (E_ERROR | E_WARNING) . "\">E_ERROR & E_WARNING<option value=\"" . (E_ERROR | E_WARNING | E_NOTICE) . "\">E_ERROR & E_WARNING & E_NOTICE</select></td></tr>
        <tr><td>�<td><input type=button value=\"" . et('SaveConfig') . "\" onclick=\"test_config_form(2)\">";
    if (strlen($Warning1))
        echo " <font color=red>$Warning1</font>";
    echo "
        <tr><td align=right>" . et('Pass') . ":<td><input type=text size=30 name=newsenha value=\"\" onkeypress=\"enterSubmit(event,'test_config_form(3)')\"></td></tr>
        <tr><td>�<td><input type=button value=\"" . et('SavePass') . "\" onclick=\"test_config_form(3)\">";
    if (strlen($Warning2))
        echo " <font color=red>$Warning2</font>";
    echo "</td></tr>";
    echo "
        </form>
        </table>
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        function set_select(sel,val){
        for(var x=0;x<sel.length;x++){
        if(sel.options[x].value==val){
        sel.options[x].selected=true;
        break;
        }
        }
        }
        set_select(document.config_form.newlang,'" . $cfg->data['lang'] . "');
        set_select(document.config_form.newerror,'" . $cfg->data['error_reporting'] . "');
        function test_config_form(arg){
        document.config_form.config_action.value = arg;
        document.config_form.submit();
        }
        function open_win(url){
        var w = 800;
        var h = 600;
        window.open(url, '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=yes,toolbar=yes,menubar=yes,location=yes');
        }
        window.moveTo((window.screen.width-600)/2,((window.screen.height-400)/2)-20);
        window.focus();
        //-->
        </script>
        ";
    echo "</body>\n</html>";
}
function shell_form()
{
    global $dir_atual, $shell_form, $cmd_arg, $path_info;
    $data_out = "";
    if (strlen($cmd_arg)) {
        exec($cmd_arg, $mat);
        if (count($mat))
            $data_out = trim(implode("\n", $mat));
    }
    switch ($shell_form) {
        case 1:
            html_header();
            echo "
        <body marginwidth=\"0\" marginheight=\"0\">
        <table border=0 cellspacing=0 cellpadding=0 align=center>
        <form name=\"data_form\">
        <tr><td><textarea name=data_out rows=36 cols=105 READONLY=\"1\"></textarea></td></tr>
        </form>
        </table>
        </body></html>";
            break;
        case 2:
            html_header();
            echo "
        <body marginwidth=\"0\" marginheight=\"0\">
        <table border=0 cellspacing=0 cellpadding=0 align=center>
        <form name=\"shell_form\" action=\"" . $path_info["basename"] . "\" method=\"post\">
        <input type=hidden name=dir_atual value=\"$dir_atual\">
        <input type=hidden name=action value=\"9\">
        <input type=hidden name=shell_form value=\"2\">
        <tr><td align=center><input type=text size=90 name=cmd_arg></td></tr>
        </form>";
            echo "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--";
            if (strlen($data_out))
                echo "
        var val = '# " . htmlencode($cmd_arg) . "\\n" . htmlencode(str_replace("<", "[", str_replace(">", "]", str_replace("\n", "\\n", str_replace("\\", "\\\\", $data_out))))) . "\\n';
        parent.frame1.document.data_form.data_out.value += val;";
            echo "
        document.shell_form.cmd_arg.focus();
        //-->
        </script>
        ";
            echo "
        </table>
        </body></html>";
            break;
        default:
            $aux = "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
        //-->
        </script>";
            html_header($aux);
            echo "
        <!--frameset rows=\"570,*\" framespacing=\"0\" frameborder=no>
        <frame src=\"" . $path_info["basename"] . "?action=9&shell_form=1\" name=frame1 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
        <frame src=\"" . $path_info["basename"] . "?action=9&shell_form=2\" name=frame2 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
        </frameset-->
        </html>";
    }
}
function server_info()
{
    if (!@phpinfo())
        echo et('NoPhpinfo') . "...";
    echo "<br><br>";
    $a = ini_get_all();
    $output = "<table border=1 cellspacing=0 cellpadding=4 align=center>";
    while (list($key, $value) = each($a)) {
        list($k, $v) = each($a[$key]);
        $output .= "<tr><td align=right>$key</td><td>$v</td></tr>";
    }
    $output .= "</table>";
    echo $output;
    echo "<br><br><table border=1 cellspacing=0 cellpadding=4 align=center>";
    $safe_mode = trim(ini_get("safe_mode"));
    if ((strlen($safe_mode) == 0) || ($safe_mode == 0))
        $safe_mode = false;
    else
        $safe_mode = true;
    $is_windows_server = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    echo "<tr><td colspan=2>" . php_uname();
    echo "<tr><td>safe_mode<td>" . ($safe_mode ? "on" : "off");
    if ($is_windows_server)
        echo "<tr><td>sisop<td>Windows<br>";
    else
        echo "<tr><td>sisop<td>Linux<br>";
    echo "</table><br><br><table border=1 cellspacing=0 cellpadding=4 align=center>";
    $display_errors = ini_get("display_errors");
    $ignore_user_abort = ignore_user_abort();
    $max_execution_time = ini_get("max_execution_time");
    $upload_max_filesize = ini_get("upload_max_filesize");
    $memory_limit = ini_get("memory_limit");
    $output_buffering = ini_get("output_buffering");
    $default_socket_timeout = ini_get("default_socket_timeout");
    $allow_url_fopen = ini_get("allow_url_fopen");
    $magic_quotes_gpc = ini_get("magic_quotes_gpc");
    ignore_user_abort(true);
    ini_set("display_errors", 0);
    ini_set("max_execution_time", 0);
    ini_set("upload_max_filesize", "10M");
    ini_set("memory_limit", "20M");
    ini_set("output_buffering", 0);
    ini_set("default_socket_timeout", 30);
    ini_set("allow_url_fopen", 1);
    ini_set("magic_quotes_gpc", 0);
    echo "<tr><td>�<td>Get<td>Set<td>Get";
    echo "<tr><td>display_errors<td>$display_errors<td>0<td>" . ini_get("display_errors");
    echo "<tr><td>ignore_user_abort<td>" . ($ignore_user_abort ? "on" : "off") . "<td>on<td>" . (ignore_user_abort() ? "on" : "off");
    echo "<tr><td>max_execution_time<td>$max_execution_time<td>0<td>" . ini_get("max_execution_time");
    echo "<tr><td>upload_max_filesize<td>$upload_max_filesize<td>10M<td>" . ini_get("upload_max_filesize");
    echo "<tr><td>memory_limit<td>$memory_limit<td>20M<td>" . ini_get("memory_limit");
    echo "<tr><td>output_buffering<td>$output_buffering<td>0<td>" . ini_get("output_buffering");
    echo "<tr><td>default_socket_timeout<td>$default_socket_timeout<td>30<td>" . ini_get("default_socket_timeout");
    echo "<tr><td>allow_url_fopen<td>$allow_url_fopen<td>1<td>" . ini_get("allow_url_fopen");
    echo "<tr><td>magic_quotes_gpc<td>$magic_quotes_gpc<td>0<td>" . ini_get("magic_quotes_gpc");
    echo "</table><br><br>";
    echo "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
        window.focus();
        //-->
        </script>";
    echo "</body>\n</html>";
}
// +--------------------------------------------------
// | Session
// +--------------------------------------------------
function logout()
{
    setcookie("loggedon", 0, 0, "/");
    form_login();
}
function login()
{
    global $senha, $auth_pass, $path_info;
    if (md5(trim($senha)) == $auth_pass) {
        setcookie("loggedon", $auth_pass, 0, "/");
        header("Location: " . $path_info["basename"] . "");
    } else
        header("Location: " . $path_info["basename"] . "?erro=1");
}
function form_login()
{
    global $erro, $auth_pass, $path_info;
    html_header();
    echo "<body onLoad=\"if(parent.location.href != self.location.href){ parent.location.href = self.location.href } return true;\">\n";
    if ($auth_pass != md5("")) {
        echo "
                <table border=0 cellspacing=0 cellpadding=5>
                <form name=\"login_form\" action=\"" . $path_info["basename"] . "\" method=\"post\">
                <tr>
                <td><b>" . et('FileMan') . "</b>
                </tr>
                <tr>
                <td align=left><font size=4>" . et('TypePass') . ".</font>
                </tr>
                <tr>
                <td><input name=senha type=password size=10> <input type=submit value=\"" . et('Send') . "\">
                </tr>
                ";
        if (strlen($erro))
            echo "
                        <tr>
                        <td align=left><font color=red size=4>" . et('InvPass') . ".</font>
                        </tr>
                        ";
        echo "
                </form>
                </table>
                <script language=\"Javascript\" type=\"text/javascript\">
                <!--
                document.login_form.senha.focus();
                //-->
                </script>
                ";
    } else {
        echo "
                <table border=0 cellspacing=0 cellpadding=5>
                <form name=\"login_form\" action=\"" . $path_info["basename"] . "\" method=\"post\">
                <input type=hidden name=frame value=3>
                <input type=hidden name=senha value=\"\">
                <tr>
                <td><b>" . et('FileMan') . "</b>
                </tr>
                <tr>
                <td><input type=submit value=\"" . et('Enter') . "\">
                </tr>
                </form>
                </table>
                ";
    }
    echo "</body>\n</html>";
}

function tree($dir_antes, $dir_corrente, $indice)
{
    global $fm_root_atual, $dir_atual, $islinux;
    global $expanded_dir_list;
    $indice++;
    $num_dir = 0;
    $dir_name = str_replace($dir_antes, "", $dir_corrente);
    $dir_corrente = str_replace("//", "/", $dir_corrente);
    $is_proibido = false;
    if ($islinux) {
        $proibidos = "/proc#/dev";
        $mat = explode("#", $proibidos);
        foreach ($mat as $key => $val) {
            if ($dir_corrente == $val) {
                $is_proibido = true;
                break;
            }
        }
        unset($mat);
    }
    if (!$is_proibido) {
        if ($handle = @opendir($dir_corrente)) {
            // Permitido
            while ($file = readdir($handle)) {
                if ($file != "." && $file != ".." && is_dir("$dir_corrente/$file"))
                    $mat_dir[] = $file;
            }
            closedir($handle);
            if (count($mat_dir)) {
                sort($mat_dir, SORT_STRING);
                // Com Sub-dir
                if ($indice != 0) {
                    for ($aux = 1; $aux < $indice; $aux++)
                        echo "����";
                    echo "�";
                }
                if ($dir_antes != $dir_corrente) {
                    if (strstr($expanded_dir_list, ":$dir_corrente/$dir_name"))
                        $op_str = "[�]";
                    else
                        $op_str = "[+]";
                    echo "�<a href=\"JavaScript:go_dir('$dir_corrente/$dir_name')\"><b>$op_str</b></a>�<a href=\"JavaScript:go('$dir_corrente')\"><b>$dir_name</b></a><br>\n";
                } else {
                    echo "<a href=\"JavaScript:go('$dir_corrente')\"><b>$fm_root_atual</b></a><br>\n";
                }
                for ($x = 0; $x < count($mat_dir); $x++) {
                    if (($dir_antes == $dir_corrente) || (strstr($expanded_dir_list, ":$dir_corrente/$dir_name"))) {
                        tree($dir_corrente . "/", $dir_corrente . "/" . $mat_dir[$x], $indice);
                    } else
                        flush();
                }
            } else {
                // Sem Sub-dir
                if ($dir_antes != $dir_corrente) {
                    for ($aux = 1; $aux < $indice; $aux++)
                        echo "����";
                    echo "�";
                    echo "<a href=\"JavaScript:go('$dir_corrente')\"> <b>$dir_name</b></a><br>\n";
                } else {
                    echo "<a href=\"JavaScript:go('$dir_corrente')\"> <b>$fm_root_atual</b></a><br>\n";
                }
            }
        } else {
            // Negado
            if ($dir_antes != $dir_corrente) {
                for ($aux = 1; $aux < $indice; $aux++)
                    echo "����";
                echo "�";
                echo "<a href=\"JavaScript:go('$dir_corrente')\"><font color=red> <b>$dir_name</b></font></a><br>\n";
            } else {
                echo "<a href=\"JavaScript:go('$dir_corrente')\"><font color=red> <b>$fm_root_atual</b></font></a><br>\n";
            }

        }
    } else {
        // Proibido
        if ($dir_antes != $dir_corrente) {
            for ($aux = 1; $aux < $indice; $aux++)
                echo "����";
            echo "�";
            echo "<a href=\"JavaScript:go('$dir_corrente')\"><font color=red> <b>$dir_name</b></font></a><br>\n";
        } else {
            echo "<a href=\"JavaScript:go('$dir_corrente')\"><font color=red> <b>$fm_root_atual</b></font></a><br>\n";
        }
    }
}
function show_tree()
{
    global $dir_atual, $filename;
    global $fm_root_atual, $path_info, $setflag, $islinux, $awardDir, $companyDir, $curDir, $awardWorkDir;
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">\n";
    echo "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        // Disable text selection
        function disableTextSelection(e){return false}
        function enableTextSelection(){return true}
        if (is.ie) document.onselectstart=new Function('return false')
        else {
        document.onmousedown=disableTextSelection
        document.onclick=enableTextSelection
        }
        var flag = " . (($setflag) ? "true" : "false") . "
        function set_flag(arg) {
        flag = arg;
        }
        function go_dir(arg) {
        var setflag;
        setflag = (flag)?1:0;
        document.location.href='" . $path_info["basename"] . "?frame=2&setflag='+setflag+'&dir_atual=$dir_atual&ec_dir='+arg;
        }
        function go(arg) {
        if (flag) {
        parent.frame3.set_dir_dest(arg+'/');
        flag = false;
        } else {
        parent.frame3.location.href='" . $path_info["basename"] . "?frame=3&dir_atual='+arg+'/';
        }
        }
        function set_fm_root_atual(arg){
        document.location.href='" . $path_info["basename"] . "?frame=2&set_fm_root_atual='+escape(arg);
        }
        function atualizar(){
        document.location.href='" . $path_info["basename"] . "?frame=2';
        }
        //-->
        </script>
        ";
    echo "<table width=\"100%\" height=\"100%\" border=0 cellspacing=0 cellpadding=5>\n";
    echo "<form><tr valign=top height=10><td>";
    if (!$islinux) {
        echo "<select name=drive onchange=\"set_fm_root_atual(this.value)\">";
        $aux = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for ($x = 0; $x < strlen($aux); $x++) {
            if (strstr(strtoupper($fm_root_atual), $aux[$x] . ":/"))
                $is_sel = "selected";
            else
                $is_sel = "";
            echo "<option $is_sel value=\"" . $aux[$x] . ":/\">" . $aux[$x] . ":/";
        }
        echo "</select> ";
    }
    echo "<input type=button value=" . et('Refresh') . " onclick=\"atualizar()\"></tr></form>";
    //if (!$islinux) $aux=substr($fm_root_atual,0,strlen($fm_root_atual)-1);
    //else
    $aux = $fm_root_atual;
    echo "<tr valign=top><td>";
    clearstatcache();
    tree($aux, $aux, -1, 0);
    echo "</td></tr>";
    echo "
        <form name=\"login_form\" action=\"" . $path_info["basename"] . "\" method=\"post\" target=\"_parent\">
        <input type=hidden name=action value=1>
        <tr>
        <td height=10 colspan=2><input type=submit value=\"" . et('Leave') . "\">
        </tr>
        </form>
        ";
    echo "</table>\n";
    echo "</body>\n</html>";
}

function getDirectoryList($dir_atual)
{
    global $fm_root, $fm_root_atual, $dir_atual, $quota_mb, $resolveIDs, $order_dir_list_by, $islinux, $cmd_name, $ip, $is_reachable, $path_info, $fm_color;
    global $curDir, $companyDir, $awardWorkDir, $webAdmin, $teamManager, $libDir, $libWorkDir, $awardDir, $isAwared, $teamName;
    global $entry_count, $file_count, $dir_count, $total_size, $uplink, $entry_list, $highlight_cols, $dirExists, $teamWorkDir;
    if ($opdir = @opendir($dir_atual)) {
        $dirExists = true;

        $entry_count = 0;
        $file_count = 0;
        $dir_count = 0;
        $total_size = 0;
        $uplink = "";
        $entry_list = array();
        $highlight_cols = 0;

        if (strrpos($dir_atual, "/") < (strlen($dir_atual) - 1)) {
            $dir_atual .= "/";
        }

        while ($file = readdir($opdir)) {
            $loadFile = true;
            if (strpos($dir_atual . $file, "PHP-") === true && $dir_atual == $fm_root_atual) {
                $loadFile = false;
            }
            if (strpos($dir_atual . $file, $libWorkDir) === false && strpos($dir_atual . $file, $awardWorkDir) === false && strpos($dir_atual . $file, $teamWorkDir) === false) {
                $loadFile = false;
            }
            //                    if ( $fm_root_atual != "" && $dir_atual != $fm_root_atual &&  strpos($dir_atual.$file,$awardDir) === false &&  strpos($dir_atual.$file,$libDir) === false &&  strpos($dir_atual.$file,$teamDir) === false ) {
            //                            $loadFile = false;
            //                        }
            if ($dir_atual == $fm_root_atual && strpos(".php#.htm#.html#.css#.js#.jsp#", $dir_entry["ext"] . "#") === true) {
                $loadFile = false;
            }
            if ($webAdmin == "yes") {
                $loadFile = true;
            }
            if (($file == ".") || ($file == "..")) {
                $loadFile = false;
            }
            if ($isAwarded == "na" && strpos($dir_atual . $file, "library") === false) {
                $loadFile = false;
            }
            if ($loadFile == true) {
                if (is_file($dir_atual . $file)) {
                    $ext = strtolower(strrchr($file, "."));
                    $entry_list[$entry_count]["type"] = "file";
                    // Fun��o filetype() returns only "file"...
                    $entry_list[$entry_count]["size"] = filesize($dir_atual . $file);
                    $entry_list[$entry_count]["sizet"] = getsize($dir_atual . $file);
                    if (strstr($ext, ".")) {
                        $entry_list[$entry_count]["ext"] = $ext;
                        $entry_list[$entry_count]["extt"] = $ext;
                    } else {
                        $entry_list[$entry_count]["ext"] = "";
                        $entry_list[$entry_count]["extt"] = "�";
                    }
                    $file_count++;
                } elseif (is_dir($dir_atual . $file)) {
                    // Recursive directory size disabled
                    // $entry_list[$entry_count]["size"] = total_size($dir_atual.$file);
                    $entry_list[$entry_count]["size"] = 0;
                    $entry_list[$entry_count]["sizet"] = 0;
                    $entry_list[$entry_count]["type"] = "dir";
                    $dir_count++;
                }
                $entry_list[$entry_count]["name"] = $file;
                $entry_list[$entry_count]["date"] = date("Ymd", filemtime($dir_atual . $file));
                $entry_list[$entry_count]["time"] = date("his", filemtime($dir_atual . $file));
                $entry_list[$entry_count]["datet"] = date("d/m/Y h:i:s", filemtime($dir_atual . $file));
                if ($islinux && $resolveIDs) {
                    $entry_list[$entry_count]["p"] = show_perms(fileperms($dir_atual . $file));
                    $entry_list[$entry_count]["u"] = getuser(fileowner($dir_atual . $file));
                    $entry_list[$entry_count]["g"] = getgroup(filegroup($dir_atual . $file));
                } else {
                    $entry_list[$entry_count]["p"] = base_convert(fileperms($dir_atual . $file), 10, 8);
                    $entry_list[$entry_count]["p"] = substr($entry_list[$entry_count]["p"], strlen($entry_list[$entry_count]["p"]) - 3);
                    $entry_list[$entry_count]["u"] = fileowner($dir_atual . $file);
                    $entry_list[$entry_count]["g"] = filegroup($dir_atual . $file);
                }
                $total_size += $entry_list[$entry_count]["size"];
                $entry_count++;
            }
        }
        closedir($opdir);
    } else {

        $dirExists = false;
    }
}
?>