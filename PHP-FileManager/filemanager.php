<?
//a:9:{s:4:"lang";s:2:"en";s:9:"auth_pass";s:32:"d41d8cd98f00b204e9800998ecf8427e";s:8:"quota_mb";i:0;s:17:"upload_ext_filter";a:0:{}s:19:"download_ext_filter";a:0:{}s:15:"error_reporting";s:0:"";s:7:"fm_root";s:0:"";s:17:"cookie_cache_time";i:1227471352;s:7:"version";s:5:"0.9.3";}

require_once '../PHP-GlobalIncludes/auth.php';

require_once '../PHP-FileManager/fileManagerConfigClass.php';

require_once '../PHP-FileManager/fileManagerArchiveClass.php';

require_once '../PHP-FileManager/fileManagerTarClass.php';

require_once '../PHP-FileManager/fileManagerGzipClass.php';

require_once '../PHP-FileManager/fileManagerBzipClass.php';

require_once '../PHP-FileManager/fileManagerZipClass.php';

/*--------------------------------------------------
| PHP FILE MANAGER
+--------------------------------------------------
| phpFileManager 0.9.3
| By Fabr�cio Seger Kolling
| Copyright (c) 2004 Fabr�cio Seger Kolling
| E-mail: dulldusk@nho.com.br
| URL: http://phpfm.sf.net
| Last Changed: 2004-09-02
+--------------------------------------------------
| OPEN SOURCE CONTRIBUTIONS
+--------------------------------------------------
| TAR/GZIP/BZIP2/ZIP ARCHIVE CLASSES 2.0
| By Devin Doucette
| Copyright (c) 2004 Devin Doucette
| E-mail: darksnoopy@shaw.ca
| URL: http://www.phpclasses.org
+--------------------------------------------------
| It is the AUTHOR'S REQUEST that you keep intact the above header information
| and notify him if you conceive BUGFIXES or any IMPROVEMENTS to this program.
+--------------------------------------------------
| LICENCE - GPL [ GNU General Public License ]
+--------------------------------------------------
| This program is FREE SOFTWARE; you can REDISTRIBUTE it and/or MODIFY
| it under the terms of the GNU General Public License as published by
| the Free Software Foundation; either version 2 of the License, or
| (at your option) any later version.
| This program is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
| GNU General Public License for more details.
| You should have gotten a copy of the GPL license with this program.
| If not, it can found at http://www.gnu.org/copyleft/gpl.html
+--------------------------------------------------
| CONFIGURATION AND INSTALATION NOTES
+--------------------------------------------------
| This program does not include any instalation or configuration
| notes because it simply does not require them.
| Just throw this file anywhere in your webserver and enjoy !!
+--------------------------------------------------
*/
// +--------------------------------------------------
// | Header and Globals
// +--------------------------------------------------
//    header("Pragma: no-cache");
//    header("Cache-Control: no-store");
    foreach ($_GET as $key => $val) $$key=htmldecode($val);
    foreach ($_POST as $key => $val) $$key=htmldecode($val);
    foreach ($_COOKIE as $key => $val) $$key=htmldecode($val);
    if (empty($_SERVER["HTTP_X_FORWARDED_FOR"])) $ip = $_SERVER["REMOTE_ADDR"]; //nao usa proxy
    else $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; //usa proxy
$islinux = !(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
$url_info = parse_url($_SERVER["HTTP_REFERER"]);
$doc_root = ($islinux) ? $_SERVER["DOCUMENT_ROOT"] : ucfirst($_SERVER["DOCUMENT_ROOT"]);
$script_filename = $doc_root.$_SERVER["PHP_SELF"];
$path_info = pathinfo($script_filename);
// +--------------------------------------------------
// | Config
// +--------------------------------------------------
$cfg = new config();
$cfg->load();
ini_set("display_errors",1);
ini_set("error_reporting",$error_reporting);
if ( strrpos($fm_root,"/") < ( strlen($fm_root)-1) )
    $fm_root .="/";
if (!isset($dir_atual)){
    $dir_atual = $path_info["dirname"]."/";
        if (!$islinux) $dir_atual = ucfirst($dir_atual);
    if ( $dir_atual != $fm_root ) {
            $dir_atual = $fm_root;
            $fm_root_atual = $fm_root;
            $set_fm_root_atual = $fm_root;
        }
    @chmod($dir_atual,0777);
} else {
    $dir_atual = formatpath($dir_atual);
    $set_fm_root_atual = $fm_root;
}
$is_reachable = (stristr($dir_atual,$doc_root)!==false);
// Auto Expand Local Path
if (!isset($expanded_dir_list)){
    $expanded_dir_list = "";
    $mat = explode("/",$path_info["dirname"]);
        for ($x=0;$x<count($mat);$x++) $expanded_dir_list .= ":".$mat[$x];
    setcookie("expanded_dir_list", $expanded_dir_list, 0, "/");
}
if (!isset($fm_root_atual)){
        if (strlen($fm_root)) $fm_root_atual = $fm_root;
                //        else {
                //            if (!$islinux) $fm_root_atual = ucfirst($path_info["dirname"]."/");
                //            else $fm_root_atual = $doc_root."/";
                //        }
    setcookie("fm_root_atual", $fm_root_atual, 0, "/");
} elseif (isset($set_fm_root_atual)) {
        if (!$islinux) $fm_root_atual = ucfirst($set_fm_root_atual);
    setcookie("fm_root_atual", $fm_root_atual, 0, "/");
}
if (!isset($resolveIDs)){
    setcookie("resolveIDs", 0, $cookie_cache_time, "/");
} elseif (isset($set_resolveIDs)){
    $resolveIDs=($resolveIDs)?0:1;
    setcookie("resolveIDs", $resolveIDs, $cookie_cache_time, "/");
}
if ($resolveIDs){
    exec("cat /etc/passwd",$mat_passwd);
    exec("cat /etc/group",$mat_group);
}
$fm_color['Bg'] = "ffffff";
$fm_color['Button'] = "FFFFFF";
$fm_color['Text'] = "000000";
$fm_color['Link'] = "777777";
$fm_color['Mark'] = "A7D2E4";
$fm_color['Dir'] = "ffffff";
$fm_color['File'] = "ffffff";
$fm_color['FileFirstCell'] = "ffffff";
foreach($fm_color as $tag=>$color){
    $fm_color[$tag]=strtolower($color);
}
// +--------------------------------------------------
// | File Manager Actions
// +--------------------------------------------------
//if ($loggedon==$auth_pass){
switch ($frame){
    case 1: break; // Empty Frame
case 2: frame2(); break;
case 3: frame3(); break;
default:
    switch($action){
        case 1: logout(); break;
        case 2: config_form(); break;
        case 3: download(); break;
        case 4: view(); break;
        case 5: server_info(); break;
        case 6: execute(); break;
        case 7: edit_file_form(); break;
        case 8: chmod_form(); break;
        case 9: shell_form(); break;
        case 10: upload_form(); break;
            default: frame3();
        }
}
//} else {
//    if (isset($senha)) login();
//    else form_login();
//}
// +--------------------------------------------------
// | Config Class
// +--------------------------------------------------

// +--------------------------------------------------
// | Interface
// +--------------------------------------------------
function html_header($plus=""){
    global $fm_color;
    echo "
        <html>
        <head>
        <title>...:::: ".et('FileMan')."</title>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
        $plus
        </head>
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        function Is(){
        this.appname = navigator.appName;
        this.appversion = navigator.appVersion;
        this.platform = navigator.platform;
        this.useragent = navigator.userAgent.toLowerCase();
        this.ie = ( this.appname == 'Microsoft Internet Explorer' );
        if (( this.useragent.indexOf( 'mac' ) != -1 ) || ( this.platform.indexOf( 'mac' ) != -1 )){
        this.sisop = 'mac';
        } else if (( this.useragent.indexOf( 'windows' ) != -1 ) || ( this.platform.indexOf( 'win32' ) != -1 )){
        this.sisop = 'windows';
        } else if (( this.useragent.indexOf( 'inux' ) != -1 ) || ( this.platform.indexOf( 'linux' ) != -1 )){
        this.sisop = 'linux';
        }
        }
        var is = new Is();
        function enterSubmit(keypressEvent,submitFunc){
        var kCode = (is.ie) ? keypressEvent.keyCode : keypressEvent.which
        if( kCode == 13) eval(submitFunc);
        }
        var W = screen.width;
        var H = screen.height;
        var FONTSIZE = 0;
        switch (W){
        case 640:
        FONTSIZE = 8;
        break;
        case 800:
        FONTSIZE = 10;
        break;
        case 1024:
        FONTSIZE = 12;
        break;
        default:
        FONTSIZE = 14;
        break;
        }
        ";
    echo replace_double(" ",str_replace(chr(13),"",str_replace(chr(10),"","
        document.writeln('
        <style>
        body {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Bg'].";
        }
        table {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        cursor: default;
        }
        select {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Link'].";
        }
        input {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Link'].";
        }
        textarea {
        font-family : Courier;
        font-size: 12px;
        font-weight : normal;
        color: ".$fm_color['Text'].";
        background-color: ".$fm_color['Link'].";
        }
        A {
        font-family : Arial;
        font-size : '+FONTSIZE+'px;
        font-weight : bold;
        text-decoration: none;
        color: ".$fm_color['Text'].";
        }
        A:link {
        color: ".$fm_color['Text'].";
        }
        A:visited {
        color: ".$fm_color['Text'].";
        }
        A:hover {
        color: ".$fm_color['Link'].";
        }
        A:active {
        color: ".$fm_color['Text'].";
        }
        </style>
        ');
        ")));
    echo "
        //-->
        </script>
        ";
}
// +--------------------------------------------------
// | Interface
// +--------------------------------------------------
function html_popup_header($plus=""){
    global $fm_color;
    echo "
        <html>
        <head>
        <title>...:::: ".et('FileMan')."</title>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
        $plus
        </head>
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        function Is(){
        this.appname = navigator.appName;
        this.appversion = navigator.appVersion;
        this.platform = navigator.platform;
        this.useragent = navigator.userAgent.toLowerCase();
        this.ie = ( this.appname == 'Microsoft Internet Explorer' );
        if (( this.useragent.indexOf( 'mac' ) != -1 ) || ( this.platform.indexOf( 'mac' ) != -1 )){
        this.sisop = 'mac';
        } else if (( this.useragent.indexOf( 'windows' ) != -1 ) || ( this.platform.indexOf( 'win32' ) != -1 )){
        this.sisop = 'windows';
        } else if (( this.useragent.indexOf( 'inux' ) != -1 ) || ( this.platform.indexOf( 'linux' ) != -1 )){
        this.sisop = 'linux';
        }
        }
        var is = new Is();
        function enterSubmit(keypressEvent,submitFunc){
        var kCode = (is.ie) ? keypressEvent.keyCode : keypressEvent.which
        if( kCode == 13) eval(submitFunc);
        }
        var W = screen.width;
        var H = screen.height;
        var FONTSIZE = 0;
        switch (W){
        case 640:
        FONTSIZE = 8;
        break;
        case 800:
        FONTSIZE = 10;
        break;
        case 1024:
        FONTSIZE = 12;
        break;
        default:
        FONTSIZE = 14;
        break;
        }
        ";
    echo replace_double(" ",str_replace(chr(13),"",str_replace(chr(10),"","
        document.writeln('
        <style>
        body {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: #ffffff;
        background-color:  #000000;
        }
        table {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: #ffffff;
        cursor: default;
        }
        select {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: #ffffff;
        background-color: #000000;
        }
        input {
        font-family : Arial;
        font-size: '+FONTSIZE+'px;
        font-weight : normal;
        color: #ffffff;
        background-color:  #000000;
        }
        textarea {
        font-family : Courier;
        font-size: 12px;
        font-weight : normal;
        color: #ffffff;
        background-color:  #000000;
        }
        A {
        font-family : Arial;
        font-size : '+FONTSIZE+'px;
        font-weight : bold;
        text-decoration: none;
        color: #ffffff;
        }
        A:link {
        color: #ffffff;
        }
        A:visited {
        color: #ffffff;
        }
        A:hover {
        color: #ffffff;
        }
        A:active {
        color: #ffffff;
        }
        </style>
        ');
        ")));
    echo "
        //-->
        </script>
        ";
}
function dir_list_form() {
    global $fm_root_atual,$dir_atual,$quota_mb,$resolveIDs,$order_dir_list_by,$islinux,$cmd_name,$ip,$is_reachable,$path_info,$fm_color;
    global $curDir, $companyDir, $workDir, $webAdmin, $teamManager, $libDir, $libWorkDir, $awardDir, $teamName, $section;
    global $entry_count, $file_count, $dir_count, $total_size, $uplink, $entry_list, $highlight_cols, $dirExists;
    $ti = getmicrotime();
    clearstatcache();
    $out = "<table border=0 cellspacing=1 cellpadding=4 width=\"100%\" bgcolor=\"#000000\">\n";
    $entry_count = 0;
    $file_count = 0;
    $dir_count = 0;
    $total_size = 0;
    $uplink = "";
    $entry_list = array();
    $highlight_cols = 0;
    getDirectoryList($dir_atual);
    if ( $dirExists == true ) {
                if ($file_count) $highlight_cols = ($islinux)?7:5;
                    else $highlight_cols = ($islinux)?6:4;

            if($entry_count){
                    $or1="1A";
                    $or2="2D";
                    $or3="3A";
                    $or4="4A";
                    $or5="5A";
                    $or6="6D";
                    $or7="7D";
                    switch($order_dir_list_by){
                        case "1A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_ASC); $or1="1D"; break;
                        case "1D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_DESC); $or1="1A"; break;
                        case "2A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"p",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_ASC); $or2="2D"; break;
                        case "2D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"p",SORT_STRING,SORT_DESC,"g",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_ASC); $or2="2A"; break;
                        case "3A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_ASC); $or3="3D"; break;
                        case "3D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_DESC,"g",SORT_STRING,SORT_ASC); $or3="3A"; break;
                        case "4A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_DESC); $or4="4D"; break;
                        case "4D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_DESC,"u",SORT_STRING,SORT_DESC); $or4="4A"; break;
                        case "5A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"size",SORT_NUMERIC,SORT_ASC); $or5="5D"; break;
                        case "5D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"size",SORT_NUMERIC,SORT_DESC); $or5="5A"; break;
                        case "6A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"date",SORT_STRING,SORT_ASC,"time",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_ASC); $or6="6D"; break;
                        case "6D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"date",SORT_STRING,SORT_DESC,"time",SORT_STRING,SORT_DESC,"name",SORT_STRING,SORT_ASC); $or6="6A"; break;
                        case "7A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"ext",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_ASC); $or7="7D"; break;
                        case "7D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"ext",SORT_STRING,SORT_DESC,"name",SORT_STRING,SORT_ASC); $or7="7A"; break;
                        }
                }
            $out .= "
                <script language=\"Javascript\" type=\"text/javascript\">
                <!--
                function getCookieVal (offset) {
                var endstr = document.cookie.indexOf (';', offset);
                if (endstr == -1) endstr = document.cookie.length;
                return unescape(document.cookie.substring(offset, endstr));
                }
                function getCookie (name) {
                var arg = name + '=';
                var alen = arg.length;
                var clen = document.cookie.length;
                var i = 0;
                while (i < clen) {
                var j = i + alen;
                if (document.cookie.substring(i, j) == arg) return getCookieVal (j);
                i = document.cookie.indexOf(' ', i) + 1;
                if (i == 0) break;
                }
                return null;
                }
                function setCookie (name, value) {
                var argv = SetCookie.arguments;
                var argc = SetCookie.arguments.length;
                var expires = (argc > 2) ? argv[2] : null;
                var path = (argc > 3) ? argv[3] : null;
                var domain = (argc > 4) ? argv[4] : null;
                var secure = (argc > 5) ? argv[5] : false;
                document.cookie = name + '=' + escape (value) +
                ((expires == null) ? '' : ('; expires=' + expires.toGMTString())) +
                ((path == null) ? '' : ('; path=' + path)) +
                ((domain == null) ? '' : ('; domain=' + domain)) +
                ((secure == true) ? '; secure' : '');
                }
                function delCookie (name) {
                var exp = new Date();
                exp.setTime (exp.getTime() - 1);
                var cval = GetCookie (name);
                document.cookie = name + '=' + cval + '; expires=' + exp.toGMTString();
                }
                function go(arg) {
                document.location.href='".$path_info["basename"]."?frame=3&dir_atual=$dir_atual'+arg+'/';
                }
                function resolveIDs() {
                document.location.href='".$path_info["basename"]."?frame=3&set_resolveIDs=1&dir_atual=$dir_atual';
                }
                var entry_list = new Array();
                // Custom object constructor
                function entry(name, type, size, selected){
                this.name = name;
                this.type = type;
                this.size = size;
                this.selected = false;
                }
                // Declare entry_list for selection procedures";
            foreach ($entry_list as $i=>$data){
                    $out .= "\nentry_list['entry$i'] = new entry('".$data["name"]."', '".$data["type"]."', ".$data["size"].", false);";
                }
            $out .= "
                // Select/Unselect Rows OnClick/OnMouseOver
                var lastRows = new Array(null,null);
                function selectEntry(Row, Action){
                var MarkColor = '#".$fm_color['Mark']."';
                var Cells = Row.getElementsByTagName('td');
                if (multipleSelection){
                // Avoid repeated onmouseover events from same Row ( cell transition )
                if (Row != lastRows[0]){
                if (Action == 'over') {
                if (entry_list[Row.id].selected){
                if (entry_list[Row.id].type == 'dir') DefaultColor = '#".$fm_color['Dir']."';
                else DefaultColor = '#".$fm_color['File']."';
                if (unselect(entry_list[Row.id])) {
                for (var c=0; c < ".(integer)$highlight_cols."; c++) {
                if (c == 0 && entry_list[Row.id].type=='file' && !entry_list[Row.id].selected) Cells[c].style.backgroundColor = '#".$fm_color['FileFirstCell']."';
                else Cells[c].style.backgroundColor = DefaultColor;
                }
                }
                // Change the last Row when you change the movement orientation
                if (lastRows[0] != null && lastRows[1] != null){
                var LastRowID = lastRows[0].id;
                var LastRowDefaultColor;
                if (entry_list[LastRowID].type == 'dir') LastRowDefaultColor = '#".$fm_color['Dir']."';
                else LastRowDefaultColor = '#".$fm_color['File']."';
                if (Row.id == lastRows[1].id){
                var LastRowCells = lastRows[0].getElementsByTagName('td');
                if (unselect(entry_list[LastRowID])) {
                for (var c=0; c < ".(integer)$highlight_cols."; c++) {
                if (c == 0 && entry_list[LastRowID].type=='file' && !entry_list[LastRowID].selected) LastRowCells[c].style.backgroundColor = '#".$fm_color['FileFirstCell']."';
                else LastRowCells[c].style.backgroundColor = LastRowDefaultColor;
                }
                }
                }
                }
                } else {
                if (select(entry_list[Row.id])){
                for (var c=0; c < ".(integer)$highlight_cols."; c++) {
                if (c == 0 && entry_list[Row.id].type=='file' && !entry_list[Row.id].selected) Cells[c].style.backgroundColor = '#".$fm_color['FileFirstCell']."';
                else Cells[c].style.backgroundColor = MarkColor;
                }
                }
                // Change the last Row when you change the movement orientation
                if (lastRows[0] != null && lastRows[1] != null){
                var LastRowID = lastRows[0].id;
                if (Row.id == lastRows[1].id){
                var LastRowCells = lastRows[0].getElementsByTagName('td');
                if (select(entry_list[LastRowID])) {
                for (var c=0; c < ".(integer)$highlight_cols."; c++) {
                if (c == 0 && entry_list[LastRowID].type=='file' && !entry_list[LastRowID].selected) LastRowCells[c].style.backgroundColor = '#".$fm_color['FileFirstCell']."';
                else LastRowCells[c].style.backgroundColor = MarkColor;
                }
                }
                }
                }
                }
                lastRows[1] = lastRows[0];
                lastRows[0] = Row;
                }
                }
                } else {
                if (Action == 'click') {
                var newColor = null;
                if (entry_list[Row.id].selected){
                var DefaultColor;
                if (entry_list[Row.id].type == 'dir') DefaultColor = '#".$fm_color['Dir']."';
                else DefaultColor = '#".$fm_color['File']."';
                if (unselect(entry_list[Row.id])) newColor = DefaultColor;
                } else {
                if (select(entry_list[Row.id])) newColor = MarkColor;
                }
                if (newColor) {
                lastRows[0] = lastRows[1] = Row;
                for (var c=0; c < ".(integer)$highlight_cols."; c++) {
                if (c == 0 && entry_list[Row.id].type=='file' && !entry_list[Row.id].selected) Cells[c].style.backgroundColor = '#".$fm_color['FileFirstCell']."';
                else Cells[c].style.backgroundColor = newColor;
                }
                }
                }
                }
                return true;
                }
                // Disable text selection and bind multiple selection flag
                var multipleSelection = false;
                if (is.ie) {
                document.onselectstart=new Function('return false');
                document.onmousedown=switch_flag_on;
                document.onmouseup=switch_flag_off;
                // Event mouseup is not generated over scrollbar.. curiously, mousedown is.. go figure.
                window.onscroll=new Function('multipleSelection=false');
                } else {
                if (document.layers) window.captureEvents(Event.MOUSEDOWN);
                if (document.layers) window.captureEvents(Event.MOUSEUP);
                window.onmousedown=switch_flag_on;
                window.onmouseup=switch_flag_off;
                }
                // Using same function and a ternary operator couses bug on double click
                function switch_flag_on(e) {
                lastRows[0] = lastRows[1] = null;
                if (is.ie){
                multipleSelection = (event.button == 1);
                } else {
                multipleSelection = (e.which == 1);
                }
                return false;
                }
                function switch_flag_off(e) {
                if (is.ie){
                multipleSelection = (event.button != 1);
                } else {
                multipleSelection = (e.which != 1);
                }
                return false;
                }
                var total_dirs_selected = 0;
                var total_files_selected = 0;
                function unselect(Entry){
                if (!Entry.selected) return false;
                Entry.selected = false;
                sel_totalsize -= Entry.size;
                if (Entry.type == 'dir') total_dirs_selected--;
                else total_files_selected--;
                update_sel_status();
                return true;
                }
                function select(Entry){
                if(Entry.selected) return false;
                Entry.selected = true;
                sel_totalsize += Entry.size;
                if(Entry.type == 'dir') total_dirs_selected++;
                else total_files_selected++;
                update_sel_status();
                return true;
                }
                function is_anything_selected(){
                var selected_dir_list = new Array();
                var selected_file_list = new Array();
                for(var x=0;x<".(integer)count($entry_list).";x++){
                if(entry_list['entry'+x].selected){
                if(entry_list['entry'+x].type == 'dir') selected_dir_list.push(entry_list['entry'+x].name);
                else selected_file_list.push(entry_list['entry'+x].name);
                }
                }
                document.form_action.selected_dir_list.value = selected_dir_list.join('<|*|>');
                document.form_action.selected_file_list.value = selected_file_list.join('<|*|>');
                return (total_dirs_selected>0 || total_files_selected>0);
                }
                function formatsize (arg) {
                var resul = '';
                if (arg>0){
                var j = 0;
                var ext = new Array(' bytes',' Kb',' Mb',' Gb',' Tb');
                while (arg >= Math.pow(1024,j)) ++j;
                resul = (Math.round(arg/Math.pow(1024,j-1)*100)/100) + ext[j-1];
                } else resul = '0 Mb';
                return resul;
                }
                var sel_totalsize = 0;
                function update_sel_status(){
                var t = total_dirs_selected+' ".et('Dir_s')." ".et('And')." '+total_files_selected+' ".et('File_s')." ".et('Selected_s')." = '+formatsize(sel_totalsize);
                document.getElementById(\"sel_status\").innerHTML = t;
                }
                // Select all/none/inverse
                function selectANI(Butt){
                var MarkColor = '#".$fm_color['Mark']."';
                for(var x=0;x<". (integer)count($entry_list).";x++){
                if (entry_list['entry'+x].type == 'dir'){
                var DefaultColor = '#".$fm_color['Dir']."';
                } else {
                var DefaultColor = '#".$fm_color['File']."';
                }
                var Row = document.getElementById('entry'+x);
                var Cells = Row.getElementsByTagName('td');
                var newColor = null;
                switch (Butt.value){
                case '".et('SelAll')."':
                if (select(entry_list[Row.id])) newColor = MarkColor;
                break;
                case '".et('SelNone')."':
                if (unselect(entry_list[Row.id])) newColor = DefaultColor;
                break;
                case '".et('SelInverse')."':
                if (entry_list[Row.id].selected){
                if (unselect(entry_list[Row.id])) newColor = DefaultColor;
                } else {
                if (select(entry_list[Row.id])) newColor = MarkColor;
                }
                break;
                }
                if (newColor) {
                for (var c=0; c < ".(integer)$highlight_cols."; c++) {
                if (entry_list[Row.id].type=='file' && c==0 && !entry_list[Row.id].selected) Cells[c].style.backgroundColor = '#". $fm_color['FileFirstCell']."';
                else Cells[c].style.backgroundColor = newColor;
                }
                }
                }
                if (Butt.value == '".et('SelAll')."'){
                Butt.value = '".et('SelNone')."';
                } else if (Butt.value == '".et('SelNone')."'){
                Butt.value = '".et('SelAll')."';
                }
                return true;
                }
                function download(arg){
                //                parent.frame1.location.href='".$path_info["basename"]."?action=3&dir_atual=$dir_atual&filename='+escape(arg);
                parent.frame3.location.href='".$path_info["basename"]."?action=3&dir_atual=$dir_atual&filename='+escape(arg);
                }
                function upload(){
                var w = 400;
                var h = 200;
                window.open('".$path_info["basename"]."?action=10&dir_atual=$dir_atual', '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                function execute(){
                document.form_action.cmd_arg.value = prompt('".et('TypeCmd').".');
                if(document.form_action.cmd_arg.value.length>0){
                if(confirm('".et('ConfExec')." \\' '+document.form_action.cmd_arg.value+' \\' ?')) {
                var w = 800;
                var h = 600;
                window.open('".$path_info["basename"]."?action=6&dir_atual=$dir_atual&cmd='+escape(document.form_action.cmd_arg.value), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                }
                }
                function decompress(arg){
                if(confirm('".strtoupper(et('Decompress'))." \\' '+arg+' \\' ?')) {
                document.form_action.action.value = 72;
                document.form_action.cmd_arg.value = arg;
                document.form_action.submit();
                }
                }
                function edit_file(arg){
                var w = 800;
                var h = 600;
                if(confirm('".strtoupper(et('Edit'))." \\' '+arg+' \\' ?')) window.open('".$path_info["basename"]."?action=7&dir_atual=$dir_atual&filename='+escape(arg), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                function config(){
                var w = 600;
                var h = 400;
                window.open('".$path_info["basename"]."?action=2', 'win_config', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                function server_info(arg){
                var w = 800;
                var h = 600;
                window.open('".$path_info["basename"]."?action=5', 'win_serverinfo', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                function shell(){
                var w = 800;
                var h = 600;
                window.open('".$path_info["basename"]."?action=9', '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                function view(arg){
                var w = 800;
                var h = 600;
                if(confirm('".strtoupper(et('View'))." \\' '+arg+' \\' ?')) window.open('".$path_info["basename"]."?action=4&dir_atual=$dir_atual&filename='+escape(arg), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=yes,toolbar=no,menubar=no,location=yes');
                }
                function rename(arg){
                var nome = '';
                if (nome = prompt('".strtoupper(et('Ren'))." \\' '+arg+' \\' ".et('To')." ...')) document.location.href='".$path_info["basename"]."?frame=3&action=3&dir_atual=$dir_atual&old_name='+escape(arg)+'&new_name='+escape(nome);
                }
                function set_dir_dest(arg){
                document.form_action.dir_dest.value=arg;
                if (document.form_action.action.value.length>0) test(document.form_action.action.value);
                else alert('".et('JSError').".');
                }
                function sel_dir(arg){
                document.form_action.action.value = arg;
                document.form_action.dir_dest.value='';
                if (!is_anything_selected()) alert('".et('NoSel').".');
                else {
                if (!getCookie('sel_dir_warn')) {
                alert('".et('SelDir').".');
                document.cookie='sel_dir_warn'+'='+escape('true')+';';
                }
                parent.frame2.set_flag(true);
                }
                }
                function set_chmod_arg(arg){
                document.form_action.chmod_arg.value=arg;
                if (document.form_action.action.value.length>0) test(document.form_action.action.value);
                else alert('".et('JSError')."');
                }
                function chmod(arg){
                document.form_action.action.value = arg;
                document.form_action.dir_dest.value='';
                document.form_action.chmod_arg.value='';
                if (!is_anything_selected()) alert('".et('NoSel').".');
                else {
                var w = 280;
                var h = 230;
                window.open('".$path_info["basename"]."?action=8', '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
                }
                function test_action(){
                if (document.form_action.action.value != 0) return true;
                else return false;
                }
                function test_prompt(arg){
                var erro='';
                var conf='';
                if (arg == 1){
                document.form_action.cmd_arg.value = prompt('".et('TypeDir').".','');
                } else if (arg == 2){
                document.form_action.cmd_arg.value = prompt('".et('TypeArq').".','');
                } else if (arg == 71){
                if (!is_anything_selected()) erro = '".et('NoSel').".';
                else document.form_action.cmd_arg.value = prompt('".et('TypeArqComp')."','');
                }
                if (erro!=''){
                document.form_action.cmd_arg.focus();
                alert(erro);
                } else if(document.form_action.cmd_arg.value != null  &&
                document.form_action.cmd_arg.value != 'null'  &&
                document.form_action.cmd_arg.value != ''  &&
                document.form_action.cmd_arg.value.length>0) {
                document.form_action.action.value = arg;
                document.form_action.submit();
                }
                }
                function strstr(haystack,needle){
                var index = haystack.indexOf(needle);
                return (index==-1)?false:index;
                }
                function valid_dest(dest,orig){
                return (strstr(dest,orig)==false)?true:false;
                }
                // ArrayAlert - Selection debug only
                function aa(){
                var str = 'selected_dir_list:\\n';
                for (x=0;x<selected_dir_list.length;x++){
                str += selected_dir_list[x]+'\\n';
                }
                str += '\\nselected_file_list:\\n';
                for (x=0;x<selected_file_list.length;x++){
                str += selected_file_list[x]+'\\n';
                }
                alert(str);
                }
                function test(arg){
                var erro='';
                var conf='';
                if (arg == 4){
                if (!is_anything_selected()) erro = '".et('NoSel').".\\n';
                conf = '".et('RemSel')." ?\\n';
                } else if (arg == 5){
                if (!is_anything_selected()) erro = '".et('NoSel').".\\n';
                else if(document.form_action.dir_dest.value.length == 0) erro = '".et('NoDestDir').".';
                else if(document.form_action.dir_dest.value == document.form_action.dir_atual.value) erro = '".et('DestEqOrig').".';
                else if(!valid_dest(document.form_action.dir_dest.value,document.form_action.dir_atual.value)) erro = '".et('InvalidDest').".';
                conf = '".et('CopyTo')." \\' '+document.form_action.dir_dest.value+' \\' ?\\n';
                } else if (arg == 6){
                if (!is_anything_selected()) erro = '".et('NoSel').".';
                else if(document.form_action.dir_dest.value.length == 0) erro = '".et('NoDestDir').".';
                else if(document.form_action.dir_dest.value == document.form_action.dir_atual.value) erro = '".et('DestEqOrig').".';
                else if(!valid_dest(document.form_action.dir_dest.value,document.form_action.dir_atual.value)) erro = '".et('InvalidDest').".';
                conf = '".et('MoveTo')." \\' '+document.form_action.dir_dest.value+' \\' ?\\n';
                } else if (arg == 9){
                if (!is_anything_selected()) erro = '".et('NoSel').".';
                else if(document.form_action.chmod_arg.value.length == 0) erro = '".et('NoNewPerm').".';
                conf = '".et('AlterPermTo')." \\' '+document.form_action.chmod_arg.value+' \\' ?\\n';
                }
                if (erro!=''){
                document.form_action.cmd_arg.focus();
                alert(erro);
                } else if(conf!='') {
                if(confirm(conf)) {
                document.form_action.action.value = arg;
                document.form_action.submit();
                }
                } else {
                document.form_action.action.value = arg;
                document.form_action.submit();
                }
                }
                //-->
                </script>";
            $out .= "
                <form name=\"form_action\" action=\"".$path_info["basename"]."\" method=\"post\" onsubmit=\"return test_action();\">
                <input type=hidden name=\"frame\" value=3>
                <input type=hidden name=\"action\" value=0>
                <input type=hidden name=\"dir_dest\" value=\"\">
                <input type=hidden name=\"chmod_arg\" value=\"\">
                <input type=hidden name=\"cmd_arg\" value=\"\">
                <input type=hidden name=\"dir_atual\" value=\"$dir_atual\">
                <input type=hidden name=\"dir_antes\" value=\"$dir_antes\">
                <input type=hidden name=\"selected_dir_list\" value=\"\">
                <input type=hidden name=\"selected_file_list\" value=\"\">";
            $out .= "<tr>
                <td bgcolor=\"#".$fm_color['Bg']."\" colspan=20><nobr>";
            if ($section != "library"){
                $out .= "
                    <input type=button onclick=\"upload()\" value=\"".et('Upload')."\">";
            }
            $out .= "
                </nobr>";
            if ($dir_atual != $fm_root_atual){
                    $mat = explode("/",$dir_atual);
                    $dir_antes = "";
                    for($x=0;$x<(count($mat)-2);$x++) {
                            $dir_antes .= $mat[$x]."/";
                        }
                    $uplink = "<a href=\"".$path_info["basename"]."?frame=3&dir_atual=$dir_antes\"><< </a> ";
                }
            if($entry_count){
                    $mat = explode("/",$dir_atual);
                    $matRoot = explode("/",$fm_root_atual);
                    $wrkDir = "";
                    for($x=(count($matRoot)-1);$x<(count($mat)-1);$x++) {
                            $wrkDir .= $mat[$x]."/";
                        }
                    if ( $webAdmin == "yes" ) {
                        $wrkDir = $dir_atual;
                    }
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20><nobr>&nbsp;&nbsp;<font size='3' bold>".strtoupper($teamName)." Team Library</font></nobr></td></tr>";
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20>&nbsp;</td></tr>";
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20><nobr>$uplink <a href=\"".$path_info["basename"]."?frame=3&dir_atual=$dir_atual\">Refresh</a>&nbsp;&nbsp;<font size='3' bold>".$wrkDir."</font></nobr></td></tr>";
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20><DIV ID=\"sel_status\"></DIV></td></tr>";
                    $dir_out="";
                    $file_out="";
                    $dir_out .= "
                        <tr>
                        <td bgcolor=\"#".$fm_color['Bg']."\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or1&dir_atual=$dir_atual\">".et('Name')."</a>
                        <!--td bgcolor=\"#".$fm_color['Bg']."\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or2&dir_atual=$dir_atual\">".et('Perms')."</a-->";
                        if ($islinux) $dir_out .= "<td bgcolor=\"#DDDDDD\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or3&dir_atual=$dir_atual\">".et('Owner')."</a><td bgcolor=\"#DDDDDD\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or4&dir_atual=$dir_atual\">".et('Group')."</a>";
                    $dir_out .= "
                        <td bgcolor=\"#".$fm_color['Bg']."\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or5&dir_atual=$dir_atual\">".et('Size')."</a>
                        <td bgcolor=\"#".$fm_color['Bg']."\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or6&dir_atual=$dir_atual\">".et('Date')."</a>";
                        if ($file_count) $dir_out .= "
                                <td bgcolor=\"#".$fm_color['Bg']."\"><a href=\"".$path_info["basename"]."?frame=3&or_by=$or7&dir_atual=$dir_atual\">".et('Type')."</a>";
                    $dir_out .= "
                        <td bgcolor=\"#".$fm_color['Bg']."\" colspan=20></td></tr>";
                    foreach ($entry_list as $ind=>$dir_entry) {
                            $file = $dir_entry["name"];
                            if ($dir_entry["type"]=="dir" ) {
                                    $displayDir = true;

                                    if ( $displayDir == true ) {
                                            $dir_out .= "
                                                <tr ID=\"entry$ind\" onmouseover=\"selectEntry(this, 'over');\" onmousedown=\"selectEntry(this, 'click');\">
                                                <td align=left bgcolor=\"#".$fm_color['Bg']."\"><nobr><a href=\"JavaScript:go('$file')\"><img src='/PHP-images/folder_icon.gif'> ".$file."</a></nobr>";

                                                if ($islinux) $dir_out .= "<td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["p"]."</td>
                                                        <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["u"]."<td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["g"];
                                            $dir_out .= "
                                                <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["sizet"]."
                                                <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["datet"];
                                                if ($file_count)  $dir_out .= "
                                                        <td bgcolor=\"#".$fm_color['Bg']."\" align=center>";
                                                        // Op��es de diret�rio
                                            $dir_out .= "
                                                <td bgcolor=\"#".$fm_color['Bg']."\" align=center>
                                                <td bgcolor=\"#".$fm_color['Bg']."\" align=center>";
                                        }
                                    $dir_out .= "
                                        </tr>";
                                } else {

                                    $displayDir = true;

                                    if ( $displayDir == true ) {
                                            $file_out .= "
                                                <tr ID=\"entry$ind\" onmouseover=\"selectEntry(this, 'over');\" onmousedown=\"selectEntry(this, 'click');\">
                                                <td align=left bgcolor=\"#".$fm_color['File']."\"><nobr><a href=\"JavaScript:download('$file')\"><img src='/PHP-images/file_icon.gif'> ".$file."</a></td>";

                                                if ($islinux) $file_out .= "<td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["p"]."</td>
                                                        <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["u"]."<td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["g"];
                                            $file_out .= "
                                                <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["sizet"]."
                                                <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["datet"]."
                                                <td bgcolor=\"#".$fm_color['Bg']."\">".$dir_entry["extt"];
                                                // Op��es de arquivo
                                            $file_out .= "
                                                <td bgcolor=\"#".$fm_color['Bg']."\" align=center>
                                                <td bgcolor=\"#".$fm_color['Bg']."\" align=center>
                                                <td bgcolor=\"#".$fm_color['Bg']."\" align=center>";

                                            $file_out .= "</tr>";
                                        }
                                }
                        }
                    $out .= $dir_out;
                    $out .= $file_out;
                    $out .= "
                        </form>";
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20>$dir_count ".et('Dir_s')." ".et('And')." $file_count ".et('File_s')." = ".formatsize($total_size)."</td></tr>";
                    if ($quota_mb) {
                            $out .= "
                                <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20>".et('Partition').": ".formatsize(($quota_mb*1024*1024))." ".et('Total')." - ".formatsize(($quota_mb*1024*1024)-total_size($fm_root_atual))." ".et('Free')."</td></tr>";
                        } else {
                            $out .= "
                                <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20>".et('Partition').": ".formatsize(disk_total_space($dir_atual))." ".et('Total')." - ".formatsize(disk_free_space($fm_root_atual))." ".et('Free')."</td></tr>";
                        }
                    $tf = getmicrotime();
                    $tt = ($tf - $ti);
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20>".et('RenderTime').": ".substr($tt,0,strrpos($tt,".")+5)." ".et('Seconds')."</td></tr>";
                    $out .= "
                        <script language=\"Javascript\" type=\"text/javascript\">
                        <!--
                        update_sel_status();
                        //-->
                        </script>";
                } else {
                    $out .= "
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20><nobr>$uplink <a href=\"".$path_info["basename"]."?frame=3&dir_atual=$dir_atual\">Refresh</a>&nbsp;&nbsp;$dir_atual</nobr></td></tr>
                        </tr>
                        <tr><td bgcolor=\"#".$fm_color['Bg']."\" colspan=20>".et('EmptyDir').".</tr>";
                }
            } else $out .= "<tr><td><font color=red>".et('IOError').".<br>$dir_atual</font>";
    $out .= "</table>";
    echo $out;
}
function frame3(){
    global $islinux,$cmd_arg,$chmod_arg,$zip_dir,$fm_root_atual;
    global $dir_dest,$dir_atual,$dir_antes;
    global $selected_file_list,$selected_dir_list,$old_name,$new_name;
    global $action,$or_by,$order_dir_list_by;
    if (!isset($order_dir_list_by)){
            $order_dir_list_by = "1A";
            setcookie("order_dir_list_by", $order_dir_list_by , $cookie_cache_time , "/");
        } elseif (strlen($or_by)){
            $order_dir_list_by = $or_by;
            setcookie("order_dir_list_by", $or_by , $cookie_cache_time , "/");
        }
    html_header();
    echo "<body>\n";
    if ($action){
            switch ($action){
                case 1: // create dir
                if (strlen($cmd_arg)){
                $cmd_arg = formatpath($dir_atual.$cmd_arg);
                if (!file_exists($cmd_arg)){
                mkdir($cmd_arg,0777);
                chmod($cmd_arg,0777);
                reloadframe("parent",2,"&ec_dir=".$cmd_arg);
                } else alert(et('FileDirExists').".");
                }
                break;
                case 2: // create arq
                if (strlen($cmd_arg)){
                $cmd_arg = $dir_atual.$cmd_arg;
                if (!file_exists($cmd_arg)){
                if ($fh = @fopen($cmd_arg, "w")){
                @fclose($fh);
                }
                chmod($cmd_arg,0666);
                } else alert(et('FileDirExists').".");
                }
                break;
                case 3: // rename arq ou dir
                if ((strlen($old_name))&&(strlen($new_name))){
                rename($dir_atual.$old_name,$dir_atual.$new_name);
                if (is_dir($dir_atual.$new_name)) reloadframe("parent",2);
                }
                break;
                case 4: // delete sel
                if(strstr($dir_atual,$fm_root_atual)){
                if (strlen($selected_file_list)){
                $selected_file_list = explode("<|*|>",$selected_file_list);
                if (count($selected_file_list)) {
                for($x=0;$x<count($selected_file_list);$x++) {
                $selected_file_list[$x] = trim($selected_file_list[$x]);
                if (strlen($selected_file_list[$x])) total_delete($dir_atual.$selected_file_list[$x],$dir_dest.$selected_file_list[$x]);
                }
                }
                }
                if (strlen($selected_dir_list)){
                $selected_dir_list = explode("<|*|>",$selected_dir_list);
                if (count($selected_dir_list)) {
                for($x=0;$x<count($selected_dir_list);$x++) {
                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                if (strlen($selected_dir_list[$x])) total_delete($dir_atual.$selected_dir_list[$x],$dir_dest.$selected_dir_list[$x]);
                }
                reloadframe("parent",2);
                }
                }
                }
                break;
                case 5: // copy sel
                if (strlen($dir_dest)){
                if(strtoupper($dir_dest) != strtoupper($dir_atual)){
                if (strlen($selected_file_list)){
                $selected_file_list = explode("<|*|>",$selected_file_list);
                if (count($selected_file_list)) {
                for($x=0;$x<count($selected_file_list);$x++) {
                $selected_file_list[$x] = trim($selected_file_list[$x]);
                if (strlen($selected_file_list[$x])) total_copy($dir_atual.$selected_file_list[$x],$dir_dest.$selected_file_list[$x]);
                }
                }
                }
                if (strlen($selected_dir_list)){
                $selected_dir_list = explode("<|*|>",$selected_dir_list);
                if (count($selected_dir_list)) {
                for($x=0;$x<count($selected_dir_list);$x++) {
                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                if (strlen($selected_dir_list[$x])) total_copy($dir_atual.$selected_dir_list[$x],$dir_dest.$selected_dir_list[$x]);
                }
                reloadframe("parent",2);
                }
                }
                $dir_atual = $dir_dest;
                }
                }
                break;
                case 6: // move sel
                if (strlen($dir_dest)){
                if(strtoupper($dir_dest) != strtoupper($dir_atual)){
                if (strlen($selected_file_list)){
                $selected_file_list = explode("<|*|>",$selected_file_list);
                if (count($selected_file_list)) {
                for($x=0;$x<count($selected_file_list);$x++) {
                $selected_file_list[$x] = trim($selected_file_list[$x]);
                if (strlen($selected_file_list[$x])) total_move($dir_atual.$selected_file_list[$x],$dir_dest.$selected_file_list[$x]);
                }
                }
                }
                if (strlen($selected_dir_list)){
                $selected_dir_list = explode("<|*|>",$selected_dir_list);
                if (count($selected_dir_list)) {
                for($x=0;$x<count($selected_dir_list);$x++) {
                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                if (strlen($selected_dir_list[$x])) total_move($dir_atual.$selected_dir_list[$x],$dir_dest.$selected_dir_list[$x]);
                }
                reloadframe("parent",2);
                }
                }
                $dir_atual = $dir_dest;
                }
                }
                break;
                case 71: // compress sel
                if (strlen($cmd_arg)){
                ignore_user_abort(true);
                ini_set("display_errors",0);
                ini_set("max_execution_time",0);
                $zipfile=false;
                if (strstr($cmd_arg,".tar")) $zipfile = new tar_file($cmd_arg);
                elseif (strstr($cmd_arg,".zip")) $zipfile = new zip_file($cmd_arg);
                elseif (strstr($cmd_arg,".bzip")) $zipfile = new bzip_file($cmd_arg);
                elseif (strstr($cmd_arg,".gzip")) $zipfile = new gzip_file($cmd_arg);
                if ($zipfile){
                $zipfile->set_options(array('basedir'=>$dir_atual,'overwrite'=>1,'level'=>3));
                if (strlen($selected_file_list)){
                $selected_file_list = explode("<|*|>",$selected_file_list);
                if (count($selected_file_list)) {
                for($x=0;$x<count($selected_file_list);$x++) {
                $selected_file_list[$x] = trim($selected_file_list[$x]);
                if (strlen($selected_file_list[$x])) $zipfile->add_files($selected_file_list[$x]);
                }
                }
                }
                if (strlen($selected_dir_list)){
                $selected_dir_list = explode("<|*|>",$selected_dir_list);
                if (count($selected_dir_list)) {
                for($x=0;$x<count($selected_dir_list);$x++) {
                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                if (strlen($selected_dir_list[$x])) $zipfile->add_files($selected_dir_list[$x]);
                }
                }
                }
                $zipfile->create_archive();
                }
                unset($zipfile);
                }
                break;
                case 72: // decompress arq
                if (strlen($cmd_arg)){
                if (file_exists($dir_atual.$cmd_arg)){
                $zipfile=false;
                if (strstr($cmd_arg,".zip")) zip_extract();
                elseif (strstr($cmd_arg,".bzip")||strstr($cmd_arg,".bz2")||strstr($cmd_arg,".tbz2")||strstr($cmd_arg,".bz")||strstr($cmd_arg,".tbz")) $zipfile = new bzip_file($cmd_arg);
                elseif (strstr($cmd_arg,".gzip")||strstr($cmd_arg,".gz")||strstr($cmd_arg,".tgz")) $zipfile = new gzip_file($cmd_arg);
                elseif (strstr($cmd_arg,".tar")) $zipfile = new tar_file($cmd_arg);
                if ($zipfile){
                $zipfile->set_options(array('basedir'=>$dir_atual,'overwrite'=>1));
                $zipfile->extract_files();
                }
                unset($zipfile);
                reloadframe("parent",2);
                }
                }
                break;
                case 8: // delete arq/dir
                if (strlen($cmd_arg)){
                if (file_exists($dir_atual.$cmd_arg)) total_delete($dir_atual.$cmd_arg);
                if (is_dir($dir_atual.$cmd_arg)) reloadframe("parent",2);
                }
                break;
                case 9: // CHMOD
                if((strlen($chmod_arg) == 4)&&(strlen($dir_atual))){
                if ($chmod_arg[0]=="1") $chmod_arg = "0".$chmod_arg;
                else $chmod_arg = "0".substr($chmod_arg,strlen($chmod_arg)-3);
                $new_mod = octdec($chmod_arg);
                $selected_file_list = explode("<|*|>",$selected_file_list);
                if (count($selected_file_list)) for($x=0;$x<count($selected_file_list);$x++) @chmod($dir_atual.$selected_file_list[$x],$new_mod);
                $selected_dir_list = explode("<|*|>",$selected_dir_list);
                if (count($selected_dir_list)) for($x=0;$x<count($selected_dir_list);$x++) @chmod($dir_atual.$selected_dir_list[$x],$new_mod);
                }
                break;
                }
                if ($action != 10) dir_list_form();
            } else dir_list_form();
    echo "</body>\n</html>";
}
function frame2(){
    global $expanded_dir_list,$ec_dir;
        if (!isset($expanded_dir_list)) $expanded_dir_list = "";
    if (strlen($ec_dir)){
                if (strstr($expanded_dir_list,":".$ec_dir)) $expanded_dir_list = str_replace(":".$ec_dir,"",$expanded_dir_list);
                    else $expanded_dir_list .= ":".$ec_dir;
            setcookie("expanded_dir_list", $expanded_dir_list , 0 , "/");
        }
    show_tree();
}
function frameset(){
    global $path_info;
    html_header();
    echo "
        <!--frameset cols=\"300,*\" framespacing=\"0\">
        <frameset rows=\"0,*\" framespacing=\"0\" frameborder=no>
        <frame src=\"".$path_info["basename"]."?frame=1\" name=frame1 border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=no>
        <frame src=\"".$path_info["basename"]."?frame=2\" name=frame2 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
        </frameset>
        <frame src=\"".$path_info["basename"]."?frame=3\" name=frame3 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
        </frameset-->
        ";
    echo "</html>";
}
// +--------------------------------------------------
// | Open Source Contributions
// +--------------------------------------------------
/*-------------------------------------------------
| TAR/GZIP/BZIP2/ZIP ARCHIVE CLASSES 2.0
| By Devin Doucette
| Copyright (c) 2004 Devin Doucette
| Email: darksnoopy@shaw.ca
+--------------------------------------------------
| Email bugs/suggestions to darksnoopy@shaw.ca
+--------------------------------------------------
| This script has been created and released under
| the GNU GPL and is free to use and redistribute
| only if this copyright statement is not removed
+--------------------------------------------------
| Limitations:
| - Only USTAR archives are officially supported for extraction, but others may work.
| - Extraction of bzip2 and gzip archives is limited to compatible tar files that have
| been compressed by either bzip2 or gzip.  For greater support, use the functions
| bzopen and gzopen respectively for bzip2 and gzip extraction.
| - Zip extraction is not supported due to the wide variety of algorithms that may be
| used for compression and newer features such as encryption.
+--------------------------------------------------
*/

// +--------------------------------------------------
// | THE END
// +--------------------------------------------------
?>