<?
/***********************************************************
* Class:        Encrypt                                    *
* Version:      1.1                                        *
* Date:         September 2004                             *
* Author:       Agus Hariyanto                             *
* Copyright:    � Agus H                                   *
* Licence :     Free for non-commercial use                *
* email :       iam_emc2@yahoo.com                         *
************************************************************/
class encdec
{
	private $charx;
	private $char;

	public function decrypt($data,$salted)
	{
//		$salted=md5($salted);
        $data = $data;
		$x=0;
		for ($i=0;$i<strlen($data);$i++)
		{
			if ($x==strlen($salted)) $x=0;
			$this->char.=substr($salted,$x,1);
			$x++;	
		}
		for ($i=0;$i<strlen($data);$i++)
		{
			if (ord(substr($data,$i,1))<ord(substr($this->char,$i,1)))
			{
				$this->charx.=chr((ord(substr($data,$i,1))+256)-ord(substr($this->char,$i,1)));	
			}
			else 
			{
				$this->charx.=chr(ord(substr($data,$i,1))-ord(substr($this->char,$i,1)));
			}
		}
		return base64_decode($this->charx);
	}
	
	public function encrypt($data,$salted)
	{
//		$salted=md5($salted);
		$data=base64_encode($data);
		$x=0;
		for ($i=0;$i<strlen($data);$i++)
		{
			if ($x==strlen($salted)) $x=0;
			$this->char.=substr($salted,$x,1);
			$x++;	
		}
		for ($i=0;$i<strlen($data);$i++)
		{
			$this->charx.=chr(ord(substr($data,$i,1))+(ord(substr($this->char,$i,1)))%256);	
		}
		return $this->charx;
	}
}
?>