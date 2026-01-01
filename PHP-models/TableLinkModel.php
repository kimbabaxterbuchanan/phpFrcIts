<?php

require_once dirname(__FILE__) .'/../PHP-models/BaseModel.php';

class TableLinkModel extends BaseModel
{
    var $primarytable = "";
    var $primaryfield = "";
    var $linktable = "";
    var $linkfield = "";
}
?>
