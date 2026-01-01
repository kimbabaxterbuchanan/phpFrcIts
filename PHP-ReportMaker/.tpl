<html><head>
<link rel="stylesheet" href="lib/main.css">
</head>
<? $ME = ".php" ; ?>
<? include("match.php") ; ?>
<body>
<form enctype="multipart/form-data" method="post" action="<?=$ME?>">
<table border="0" cellpadding="4" cellspacing="3" width="450">

<? while ($r = mysql_fetch_array($qid)) {  ?><tr><td class="name" colspan="2">Fields marked with * must be filled out!</td></tr>
<input type="hidden" name="mode" value="<?echo $newmode; ?>">
<input type="hidden" name="code" value="1">
<tr><td class="name" colspan="2" align="center"><input type="submit" name="SubBut" value=<?echo $caption; ?> >&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset"></td></tr>
<? $pgid = $r["id"]; } ?>
</table><p>
<a href="<?=$ME?>?mode=insert">Add a record</a> |
<a href="<?=$ME?>?mode=update">Update a record</a> |
<a href="<?=$ME?>?mode=delete">Delete a record</a> |
<a href="<?=$ME?>?mode=navigate">Navigate</a> |
<a href="<?=$ME?>?mode=search">Search</a> |
<a href="<?=$ME?>?mode=grid">Grid</a> |
<a href="<?=$ME?>?mode=gridsrch">GridSearch</a>|
ID = <? echo $pgid; ?>
</p></form>
</body></html>