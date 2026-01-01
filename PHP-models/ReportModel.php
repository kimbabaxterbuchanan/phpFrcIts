<?php
require_once dirname(__FILE__) .'/../PHP-models/BaseModel.php';


class ReportModel  extends BaseModel
{
    var $reportname= "";
    var $reportdescription= "";
    var $reporttitle= "";
    var $selected_tables= "";
    var $reportsql= "";
    var $displaytype= "";
}
?>
