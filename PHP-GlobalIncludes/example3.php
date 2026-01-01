<?
include("./AES128.php");
$aes=new AES128();
$key=$aes->makeKey("0123456789abcdef");
$data=$HTTP_POST_VARS['data'];
$dec=$HTTP_POST_VARS['decryption'];
//$key=$HTTP_POST_VARS['pwd'];
$e='';
if ($dec=='fals' && $data != "")
{
    $e=$aes->blockEncrypt($data, $key);
	echo '<h2> Result= '.$e.'</h2><br/><br/>';
}
else if ( $dec != "" )
{
    $d=$aes->blockDecrypt($dec, $key);
	echo '<h2> Result= '.$d.'</h2><br/><br/>';
}
?>
<html>
	<head>
		<title>Encryption</title>
	</head>
	<body>

		<h2>Encryption</h2><br/>

		<form method="post" action="example3.php">
		<input type="hidden" name="decryption" value="fals"/>
			<input type="text" name="data" value="<?=$d?>"/>&nbsp;&nbsp;Data
			<br/>
			<input type="text" name="pwd" value="<?=$key?>"/>&nbsp;&nbsp;Key
			<br/>
			<input type="submit" value="Encrypt">
		</form>

		<br/><br/>

		<h2>Decryption</h2><br/>

		<form method="post" action="example3.php">
		<input type="hidden" name="data" value="fals"/>
			<input type="text" name="decryption" value="<?=$e?>"/>&nbsp;&nbsp;Data
			<br/>
			<input type="text" name="pwd" value="<?=$key?>"/>&nbsp;&nbsp;Key
			<br/>
			<input type="submit" value="Decrypt">
		</form>
	</body>
</html>
