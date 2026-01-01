<?
include("lib/webapp.php");
global $SESSION;
include("lib/class.Validator.php");
$check = new Validator();

$tabname = "";
$prid = "";
$sql_select = "select * from  where ='{$SESSION["asp"]->apid} order by '";
$sql_delete = "delete from    where ='{$SESSION["asp"]->apid}'";
if($mode  == "gridupdate"){
$SESSION["asp"]->apid = $;
$sql_select = "select * from  where ='{$SESSION["asp"]->apid} order by '";
}

if($mode=="" ){
$newmode = "navigate";
$caption = "Navigate";
}

if($mode=="grid" ){
$newmode = "grid";
$caption = "Grid";
}


if($mode=="gridupdate"){
$newmode = "update";
$caption = "Update";
$msg     = "Update ";
$qid = mysql_query($sql_select);
include("$tabname.tpl");
}


if($mode=="update"){
$newmode = "update";
$caption = "Update";
$qid = mysql_query($sql_select);
include("$tabname.tpl");

if ($SubBut) {$sql_update = "update  set where ='{$SESSION["asp"]->apid}'";
if ( empty($check->ERROR) ) {
$qid =  mysql_query($sql_update);
} 
      }
}

if($mode=="delete"){
$confirm = "";
$fid = $SESSION["asp"]->apid;
$tmpl = $tabname."_conf.tpl";
include($tmpl);
}

if($mode=="delconf"){
if( stripslashes($confirm == "Yes" )) 
{
$qid =  mysql_query($sql_delete);
echo "Data Deleted";
} else {
echo "No data deleted";
       }
$mode = "navigate";
$newmode = "navigate";
$caption = "Navigate";
}

if($mode=="search" || $mode=="gridsrch" ){
$newmode = "search";
$caption = "Search";
$srchfn = $tabname . "_search.tpl";
include("$srchfn");
if($f2 != "" ){
$sql = "select * from $tabname where $f1 like '%$f2%' ";
if ($mode=="search" )
{
$mode = "srchnav";
$caption = "Srchgate";
}
if ($mode=="gridsrch" )
$mode = "gridsnav";
             }
}

//Navigation program
      
if($mode=="navigate" || $mode=="srchnav" || $mode=="grid" || $mode=="gridsnav"){

      $rows_per_page = 1;
      if($mode=="grid" || $mode=="gridsnav"  )
      $rows_per_page = 5;

      if (!isset($screen))
      $screen = 0;

      $start = $screen *$rows_per_page;

  if ( $mode  == "srchnav" || $mode=="gridsnav" ){
     if ($f1 == "" || $f2 == ""){
      $f1 = $SESSION["asp"]->vars1;
      $f2 = $SESSION["asp"]->vars2;
    }

    $sql = "select * from $tabname where $f1 like '%$f2%' ";
      $qid = mysql_query($sql);
      $total = mysql_num_rows($qid);
      $pages = ceil($total/$rows_per_page);
    $sql_select= "SELECT * FROM $tabname where $f1 like '%$f2%' LIMIT $start, $rows_per_page";
     if ($mode=="srchnav")
      {
       $mode = "srchnav";
       $caption = "Srchgate";
      }
     if ($mode=="gridsnav" )
       $mode = "gridsnav";

  } else { 
    $sql = "SELECT * FROM $tabname";
      $qid = mysql_query($sql);
      $total = mysql_num_rows($qid);
      $pages = ceil($total/$rows_per_page);
    $sql_select= "SELECT * FROM $tabname order by $prid  LIMIT $start, $rows_per_page"; 

     if ($mode=="navigate")
      {
       $mode = "navigate";
       $caption = "Navigate";
      }
     if ($mode=="grid" )
       $mode = "grid";
  }


      $qid = mysql_query($sql_select);
      $row = mysql_fetch_array($qid);
      $jid = $row["$prid"];
      $id2= " ";
      $SESSION["asp"]->add($jid,$f1,$f2,$id2);
      $qid = mysql_query($sql_select);

      if($mode=="navigate" || $mode=="srchnav" )
      include("$tabname.tpl");
      elseif ($mode=="grid" || $mode=="gridsnav"){
      $tmpl = $tabname."_grid.tpl";
      include($tmpl);
      }
      echo "<hr>\n";

//      if($screen == 0) {
      $varx = 0;
      $url = "$tabname.php?mode=$mode&screen=".$varx;
      echo "<a href=\"$url\">First</a>\n";
//      }

      if($screen == 0 || $screen > 0 ) {
      $varx = $screen-1;
      if ($varx < 0 )$varx = 0;
      $url = "$tabname.php?mode=$mode&screen=".$varx;
      echo "<a href=\"$url\">Previous</a>\n";
      }

      for ($i=0; $i< $pages; $i++) {
      $url = "$tabname.php?mode=$mode&screen=".$i;
      }

      if($screen < $pages-1 || $screen == $pages-1 ) {
      $varx = $screen+1;
      if($varx == $pages) $varx = $pages-1;
      $url = "$tabname.php?mode=$mode&screen=".$varx;
      echo "<a href=\"$url\">Next</a>\n";
      }

//      if($screen == $pages-1 ) {
      $varx = $pages-1;
      $url = "$tabname.php?mode=$mode&screen=".$varx;
      echo "<a href=\"$url\">Last</a>\n";
      $startc = $start+1;
      echo "($startc out  of Total $total)\n";
//      }
 }

?>
<?

//Insert Data//

if($mode=="insert"){

$newmode = "insert";
$caption = "Submit";
$table = $tabname;
$addname = $table."_add";
include("$addname.tpl");
if ($SubBut) {$tabname = "";
$jid = mysql_insert_id();
$sql_insert = "insert into  () values ()";

if ( empty($check->ERROR) ) {
if(!$qid = mysql_query($sql_insert)){
echo "MySql Error: No data inserted";
 } else {
 echo "MySql Data Inserted";
        }
 }
             }
}
?>
