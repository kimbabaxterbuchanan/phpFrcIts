<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';

###############################################################
# File Download 1.3
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
###############################################################
# Sample call:
#    download.php?f=phptutorial.zip
#
# Sample call (browser will try to save with new file name):
#    download.php?f=phptutorial.zip&fc=php123tutorial.zip
###############################################################

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', '');
$cquery = $_GET['cquery'];
$displaytype = $_GET['displaytype'];
// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
if( !$dbi = mysql_select_db(DB_DATABASE)) {
    echo "Database Selection  Error";
}
$cquery = urldecode($cquery);
$qid = mysql_query(stripslashes($cquery));

$rptCnt = mysql_num_fields($qid);
if ( $displaytype == "" ) $displaytype="list";
$saveas = tempnam(dirname(__FILE__)."/sqldocs","Fil");
$renamedFile = str_replace("tmp","csv",$saveas);
$savename=$saveas;
$fp = fopen($savename, "w+");
$hdrline = "";
if ( $displaytype == "list" ){
    for ($i=0; $i<$rptCnt; $i++)
    {
        $hdr = mysql_field_name($qid,$i);
        $hdr = getLabel($hdr,$locale);
        if (!$hdr) {
            print ("No Information available<br>\n");
            continue;
        }
        $hdrline .= $hdr.",";
    }
    $hdrline .= "\r\n";
    fputs($fp, $hdrline);

    while ($row = mysql_fetch_row($qid))
    {
        $bdyline = "";
        for ($i=0; $i< mysql_num_fields($qid); $i++)
        {
            $bdyline .= $row[$i].",";
        }
        $bdyline .= "\r\n";
        fputs($fp, $bdyline);
    }
} else {
    $i = 0;
    while ($row = mysql_fetch_row($qid))
    {
        for ($i=0; $i< mysql_num_fields($qid); $i++)
        {
            $hdr = mysql_field_name($qid,$i);
            $hdr = getLabel($hdr,$locale);

            $bdyline = $hdr.",";
            $bdyline .= $row[$i].",";
            $bdyline .= "\r\n";
            fputs($fp, $bdyline);
        }
    }
}
fclose($fp);
$basesavename = basename($savename);
$dfilename = basename($renamedFile);

mysql_free_result($qid);
mysql_close($link);
$_GET['f'] = $basesavename;
$_GET['d'] = $dfilename;

define('BASE_DIR','temp');
//$savename = dirname(__FILE__) . "/sqldocs/".$_GET['f'];
$downloadloadFile = dirname(__FILE__) . "/sqldocs/".$_GET['f'];
$tmpDownloadFile = dirname(__FILE__) . "/".BASE_DIR."/".$_GET['d'];
$savename = $tmpDownloadFile;

$fres = rename($downloadloadFile,$tmpDownloadFile);
//unlink($downloadloadFile);


// log downloads?  true/false
define('LOG_DOWNLOADS',true);

// log file name
define('LOG_FILE','downloads.log');

// Allowed extensions list in format 'extension' => 'mime type'
// If myme type is set to empty string then script will try to detect mime type 
// itself, which would only work if you have Mimetype or Fileinfo extensions
// installed on server.
$allowed_ext = array (

    // archives
  'zip' => 'application/zip',

    // documents
  'pdf' => 'application/pdf',
  'doc' => 'application/msword',
  'docx' => 'application/msword',
  'txt' => 'application/text',
  'csv' => 'application/text',
  'tmp' => 'application/text',
  'rtf' => 'application/octet-stream'
);



####################################################################
###  DO NOT CHANGE BELOW
####################################################################

// If hotlinking not allowed then make hackers think there are some server problems
if (ALLOWED_REFERRER !== ''
    && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
) {
    die("Internal server error. Please contact system administrator.");
}

// Make sure program execution doesn't time out
// Set maximum script execution time in seconds (0 means no limit)
set_time_limit(0);

if (!isset($savename) || empty($savename)) {
    die("Please specify file name for download.");
}
// Get real file name.
// Remove any path info to avoid hacking by adding relative path, etc.
$fname = basename($savename);

// Check if the file exists
// Check in subfolders too
function find_file ($dirname, $fname, &$file_path) {

    $dir = opendir($dirname);

    while ($file = readdir($dir)) {
        if (empty($file_path) && $file != '.' && $file != '..') {
            if (is_dir($dirname.'/'.$file)) {
                find_file($dirname.'/'.$file, $fname, $file_path);
            }
            else {
                if (file_exists($dirname.'/'.$fname)) {
                    $file_path = $dirname.'/'.$fname;
                    return;
                }
            }
        }
    }

} // find_file

// get full file path (including subfolders)
$file_path = '';
find_file(BASE_DIR, $fname, $file_path);

if (!is_file($file_path)) {
    die("File does not exist. Make sure you specified correct file name.");
}

// file size in bytes
$fsize = filesize($file_path); 

// file extension
$fary = explode(".",$fname);
$fext = strtolower($fary[1]);
$ftmp = strtolower($fary[0]).".tmp";

// check if allowed extension
if (!array_key_exists($fext, $allowed_ext)) {
    die("Not allowed file type.");
}

// get mime type
if ($allowed_ext[$fext] == '') {
    $mtype = '';
    // mime type is not set, get from server settings
    if (function_exists('mime_content_type')) {
        $mtype = mime_content_type($file_path);
    }
    else if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME); // return mime type
        $mtype = finfo_file($finfo, $file_path);
        finfo_close($finfo);
    }
    if ($mtype == '') {
        $mtype = "application/force-download";
    }
}
else {
    // get mime type defined by admin
    $mtype = $allowed_ext[$fext];
}
// Browser will try to save file with this filename, regardless original filename.
// You can override it if needed.

if (!isset($_GET['fc']) || empty($_GET['fc'])) {
    $asfname = $fname;
}
else {
    // remove some bad chars
    $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
    if ($asfname === '') $asfname = 'NoName';
}
// set headers
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: download; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);

// download
// @readfile($file_path);
$file = @fopen($file_path,"rb");
if ($file) {
    while(!feof($file)) {
        print(fread($file, 1024*8));
        flush();
        if (connection_status()!=0) {
            @fclose($file);
            //      die();
        }
    }
    @fclose($file);
}


unlink($tmpDownloadFile);
?>
<script>
    history.go(-1);
</script>