<?php
class zip_file extends archive
{
    function zip_file($name)
        {
            $this->archive($name);
            $this->options['type'] = "zip";
        }

    function create_zip()
        {
            $files = 0;
            $offset = 0;
            $central = "";
        
            if(!empty($this->options['sfx']))
                {
                    if($fp = @fopen($this->options['sfx'],"rb"))
                        {
                            $temp = fread($fp,filesize($this->options['sfx']));
                            fclose($fp);
                            $this->add_data($temp);
                            $offset += strlen($temp);
                            unset($temp);
                        }
                        else
                        {
                            $this->error[] = "Could not open sfx module from {$this->options['sfx']}.";
                        }
                }
        
            $pwd = getcwd();
            chdir($this->options['basedir']);
        
            foreach($this->files as $current)
                {
                    if($current['name'] == $this->options['name'])
                        {
                            continue;
                        }
                    $translate =  array('�'=>pack("C",128),'�'=>pack("C",129),'�'=>pack("C",130),'�'=>pack("C",131),'�'=>pack("C",132),
                        '�'=>pack("C",133),'�'=>pack("C",134),'�'=>pack("C",135),'�'=>pack("C",136),'�'=>pack("C",137),
                        '�'=>pack("C",138),'�'=>pack("C",139),'�'=>pack("C",140),'�'=>pack("C",141),'�'=>pack("C",142),
                        '�'=>pack("C",143),'�'=>pack("C",144),'�'=>pack("C",145),'�'=>pack("C",146),'�'=>pack("C",147),
                        '�'=>pack("C",148),'�'=>pack("C",149),'�'=>pack("C",150),'�'=>pack("C",151),'_'=>pack("C",152),
                        '�'=>pack("C",153),'�'=>pack("C",154),'�'=>pack("C",156),'�'=>pack("C",157),'_'=>pack("C",158),
                        '�'=>pack("C",159),'�'=>pack("C",160),'�'=>pack("C",161),'�'=>pack("C",162),'�'=>pack("C",163),
                        '�'=>pack("C",164),'�'=>pack("C",165));
                    $current['name2'] = strtr($current['name2'],$translate);
                
                    $timedate = explode(" ",date("Y n j G i s",$current['stat'][9]));
                    $timedate = ($timedate[0] - 1980 << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) |
                        ($timedate[3] << 11) | ($timedate[4] << 5) | ($timedate[5]);
                
                    $block = pack("VvvvV",0x04034b50,0x000A,0x0000,(isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate);
                
                    if($current['stat'][7] == 0 && $current['type'] == 5)
                        {
                            $block .= pack("VVVvv",0x00000000,0x00000000,0x00000000,strlen($current['name2']) + 1,0x0000);
                            $block .= $current['name2'] . "/";
                            $this->add_data($block);
                            $central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
                                (isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
                                0x00000000,0x00000000,0x00000000,strlen($current['name2']) + 1,0x0000,0x0000,0x0000,0x0000,$current['type'] == 5? 0x00000010 : 0x00000000,$offset);
                            $central .= $current['name2'] . "/";
                            $files++;
                            $offset += (31 + strlen($current['name2']));
                        }
                            else if($current['stat'][7] == 0)
                                {
                                    $block .= pack("VVVvv",0x00000000,0x00000000,0x00000000,strlen($current['name2']),0x0000);
                                    $block .= $current['name2'];
                                    $this->add_data($block);
                                    $central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
                                        (isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
                                        0x00000000,0x00000000,0x00000000,strlen($current['name2']),0x0000,0x0000,0x0000,0x0000,$current['type'] == 5? 0x00000010 : 0x00000000,$offset);
                                    $central .= $current['name2'];
                                    $files++;
                                    $offset += (30 + strlen($current['name2']));
                                }
                                    else if($fp = @fopen($current['name'],"rb"))
                                        {
                                            $temp = fread($fp,$current['stat'][7]);
                                            fclose($fp);
                                            $crc32 = crc32($temp);
                                            if(!isset($current['method']) && $this->options['method'] == 1)
                                                {
                                                    $temp = gzcompress($temp,$this->options['level']);
                                                    $size = strlen($temp) - 6;
                                                    $temp = substr($temp,2,$size);
                                                }
                                                else
                                                {
                                                    $size = strlen($temp);
                                                }
                                            $block .= pack("VVVvv",$crc32,$size,$current['stat'][7],strlen($current['name2']),0x0000);
                                            $block .= $current['name2'];
                                            $this->add_data($block);
                                            $this->add_data($temp);
                                            unset($temp);
                                            $central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
                                                (isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
                                                $crc32,$size,$current['stat'][7],strlen($current['name2']),0x0000,0x0000,0x0000,0x0000,0x00000000,$offset);
                                            $central .= $current['name2'];
                                            $files++;
                                            $offset += (30 + strlen($current['name2']) + $size);
                                        }
                                        else
                                        {
                                            $this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
                                        }
                }
        
            $this->add_data($central);
        
            $this->add_data(pack("VvvvvVVv",0x06054b50,0x0000,0x0000,$files,$files,strlen($central),$offset,
                !empty($this->options['comment'])? strlen($this->options['comment']) : 0x0000));
        
            if(!empty($this->options['comment']))
                {
                    $this->add_data($this->options['comment']);
                }
        
            chdir($pwd);
        
            return 1;
        }
}
?>
