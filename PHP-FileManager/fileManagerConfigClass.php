<?php
class config {
    var $data;
    var $filename;
    function config(){
            global $script_filename;
            $this->data = array(
                'lang'=>'en',
                'auth_pass'=>md5(''),
                'quota_mb'=>0,
                'upload_ext_filter'=>array(),
                'download_ext_filter'=>array(),
                'error_reporting'=>'',
                'fm_root'=>'',
                'cookie_cache_time'=>time()+60*60*24*30, // 30 Dias
                'version'=>'0.9.3'
                );
            $data = false;
            $this->filename = $script_filename;
            if (file_exists($this->filename)){
                    $mat = file($this->filename);
                    $objdata = trim(substr($mat[1],2));
                        if (strlen($objdata)) $data = unserialize($objdata);
                }
                if (is_array($data)&&count($data)==count($this->data)) $this->data = $data;
                    else $this->save();
        }
    function save(){
            global $curDir, $companyDir, $workDir, $webAdmin, $teamManager,$teamName;
            if ( $webAdmin == "yes") {
                    $objdata = "<?".chr(13).chr(10)."//".serialize($this->data).chr(13).chr(10);
                    if (strlen($objdata)){
                            if (file_exists($this->filename)){
                                    $mat = file($this->filename);
                                    if ($fh = @fopen($this->filename, "w")){
                                            @fputs($fh,$objdata,strlen($objdata));
                                            for ($x=2;$x<count($mat);$x++) {
                                                    @fputs($fh,$mat[$x],strlen($mat[$x]));
                                                }
                                            @fclose($fh);
                                        }
                                }
                        }
                }
        }
    function load(){
            global $isAwarded, $awardDir,$curDir, $companyDir, $workDir, $webAdmin;
            global $teamManager,$teamName,$directoryHome, $section, $dir_atual, $teamDir;
            foreach ($this->data as $key => $val) {
                    switch($key){
                            case "lang": break;
                            case "auth_pass": break;
                            case "quota_mb": break;
                            case "upload_ext_filter": break;
                            case "download_ext_filter": break;
                            case "error_reporting": break;
                        case "fm_root":
                        $val=$directoryHome;
                        if ( $teamManager == "yes" ) {
                            if ( $isAwarded == "na" && $section == "library" ) {
                                $val .=  "library/".$teamName;
                            }
                        } else if ( $section == "library" || $isAwarded == "na"  ) {
                            $val .= "library/".$teamName;
                        } else  if ( $section == "library" ) {
                            $val .= $section."/".$teamName."/".$companyDir."/".$section;
                        } else {
                            $val .= $teamDir."/".$teamName."/".$companyDir."/".$section;
                        }
                        break;
                            case "cookie_cache_time": break;
                            default:;
                        }
                    $GLOBALS[$key] = $val;
                }
        }
}
?>