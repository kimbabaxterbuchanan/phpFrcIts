<?php
session_start();
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/../PHP-Actions/toAwardsFormAction.php';
$docNumber = "";
$title = "";
$customer = "";
$value = "";
$performancePeriod = "";
$received_dt = "";
$questionDue_dt = "";
$draftDue_dt = "";
$finalDue_dt = "";
$bid = "";
$results = "";
$notes = "";
$tmName = "";
$userId = "";
$posted = "";
if ( $id != "") {
    $res = mysql_fetch_assoc($result);
    $docNumber = $res['docNumber'];
    $title = $res['title'];
    $customer = $res['customer'];
    $value = $res['value'];
    $performancePeriod = $res['performancePeriod'];

    $received_dt = mysqlDateToPhpDate($res['received_dt']);

    $questionDue_dt = mysqlDateToPhpDate($res['questionDue_dt']);

    $draftDue_dt = mysqlDateToPhpDate($res['draftDue_dt']);

    $finalDue_dt = mysqlDateToPhpDate($res['finalDue_dt']);

    $bid = $res['bid'];
    $results = $res['results'];
    $notes = $res['notes'];
    $tmName = $res['teamName'];
    $userId = $res['userId'];
    $posted = $res['posted'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Login Form</title>
        <link href="<?=dirname(__FILE__) ?>/../PHP-css/loginModule.css" rel="stylesheet" type="text/css" />
        <script language="JavaScript" type="text/javascript" src="/PHP-jsScript/teamFRC.js"></script>
    </head>
    <body>
        <h1>To Award Form</h1>
        <?php
        require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/ProcessErrorsInclude.php';
        ?>
        <form id="toAwardsForm" name="toAwardsForm" method="post" action="toAwardsForm.php">
            <input type="hidden" name="id" id="id" value="<?= $id ?>" />
            <input type="hidden" name="section" id="section" value="<?= $section ?>"/>
            <input type="hidden" name="sub_section" id="sub_section" value="<?= $sub_section ?>"/>
            <input type="hidden" name="postForm" id="postForm" value="yes"/>
            <input type="hidden" name="companyId" id="companyId" value="<?= $companyId ?>" />
            <input type="hidden" name="homeURL" id="homeURL" value="<?= $homeURL ?>" />
            <input type="hidden" name="homeLoc" id="homeLoc" value="<?= $homeLoc ?>" />
            <?php if ( $teamManager == "no" ) { ?>
                <input type="hidden" name="userId" id="userId" value="<?= $userId ?>" />
                    <?php }?>
                    <?php if ( $webAdmin == "no" ) { ?>
                        <input type="hidden" name="tmName" id="tmName" value="<?= $teamName ?>"/>
                            <?php } ?>
                        <input type="hidden" name="formAction" id="formAction" value="<?= $formAction ?>"/>
                            <input type="hidden" name="webAdmin" id="webAdmin" value="<?= $webAdmin ?>" />
            <table width="100%">
                <tr>
                    <td>
                        <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                        <?php if ( $teamManager == "yes" && $formAction != "delete" && $formAction != "view" ) { ?>
                            <tr>
                                <th>Document Number</th>
                                <td><input name="docNumber" type="text" class="textfield" id="docNumber"  value="<?= $docNumber ?>"/></td>
                            </tr>
                            <tr>
                                <th>Title </th>
                                <td><input name="title" type="text" class="textfield" id="title"  value="<?=$title?>"/></td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td><input name="customer" type="text" class="textfield" id="customer"  value="<?=$customer?>"/></td>
                            </tr>
                            <tr>
                                <th>Value</th>
                                <td><input name="value" type="text" class="textfield" id="value"  value="<?=$value?>"/></td>
                            </tr>
                            <tr>
                                <th>Performance Period</th>
                                <td><input name="performancePeriod" type="text" class="textfield" id="performancePeriod"  value="<?=$performancePeriod?>"/></td>
                            </tr>
                            <tr>
                            <?php
                                $img = "<a href=\"javascript:popCal('received_dt','toAwardsForm')\"><img style=\"position:relative; top:3px;\" width=15px height=15px; src=\"../PHP-images/calendar.gif\" border=\"0\"></a>";
                                ?>
                                <th>Received Date</th>
                                <td nowrap><input name="received_dt" type="text" class="textfield" id="received_dt"  value="<?=$received_dt?>"/> <?=$img?></td>
                            </tr>
                            <tr>
                            <?php
                                $img = "<a href=\"javascript:popCal('questionDue_dt','toAwardsForm')\"><img style=\"position:relative; top:3px;\" width=15px height=15px; src=\"../PHP-images/calendar.gif\" border=\"0\"></a>";
                                ?>
                                <th>Questions Due Date</th>
                                <td nowrap><input name="questionDue_dt" type="text" class="textfield" id="questionDue_dt"  value="<?=$questionDue_dt?>"/> <?=$img?></td>
                            </tr>
                            <tr>
                            <?php
                                $img = "<a href=\"javascript:popCal('draftDue_dt','toAwardsForm')\"><img style=\"position:relative; top:3px;\" width=15px height=15px; src=\"../PHP-images/calendar.gif\" border=\"0\"></a>";
                                ?>
                                <th>Draft Due Date</th>
                                <td nowrap><input name="draftDue_dt" type="text" class="textfield" id="draftDue_dt"  value="<?=$draftDue_dt?>"/> <?=$img?></td>
                            </tr>
                            <tr>
                            <?php
                                $img = "<a href=\"javascript:popCal('finalDue_dt','toAwardsForm')\"><img style=\"position:relative; top:3px;\" width=15px height=15px; src=\"../PHP-images/calendar.gif\" border=\"0\"></a>";
                                ?>
                                <th>Final Due Date</th>
                                <td nowrap><input name="finalDue_dt" type="text" class="textfield" id="finalDue_dt"  value="<?=$finalDue_dt?>"/> <?=$img?></td>
                            </tr>
                            <tr>
                                <th>Bid</th>
                                <td><input name="bid" type="text" class="textfield" id="bid"  value="<?=$bid?>"/></td>
                            </tr>
                            <tr>
                                <th>Results</th>
                                <td><input name="results" type="text" class="textfield" id="results"  value="<?=$results?>"/></td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td><input name="notes" type="text" class="textfield" id="notes"  value="<?=$notes?>"/></td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <input type="hidden" name="docNumber" id="docNumber" value="<?= $docNumber ?>"/>
                                <th>Document Number</th>
                                <td><?= $docNumber ?></td>
                            </tr>
                            <tr>
                                <th>Title </th>
                                <td><?=$title?></td>
                            </tr>
                            <tr>
                                <th>Customer</th>
                                <td><?=$customer?></td>
                            </tr>
                            <tr>
                                <th>Value</th>
                                <td><?=$value?></td>
                            </tr>
                            <tr>
                                <th>Performance Period</th>
                                <td><?=$performancePeriod?></td>
                            </tr>
                            <tr>
                                <th>Received Date</th>
                                <td><?=$received_dt?></td>
                            </tr>
                            <tr>
                                <th>Questions Due Date</th>
                                <td><?=$questionDue_dt?></td>
                            </tr>
                            <tr>
                                <th>Draft Due Date</th>
                                <td><?=$draftDue_dt?></td>
                            </tr>
                            <tr>
                                <th>Final Due Date</th>
                                <td><?=$finalDue_dt?></td>
                            </tr>
                            <tr>
                                <th>Bid</th>
                                <td><?=$bid?></td>
                            </tr>
                            <tr>
                                <th>Results</th>
                                <td><?=$results?></td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td><?=$notes?></td>
                            </tr>
                            <tr>
                            <?php } ?>
                            <?php if ( $teamManager == "yes" && $formAction != "delete"  && $formAction != "view") { ?>                    
                            <tr>
                                <td>
                                    User:
                                </td>
                                <td>
                                    <select name="userId" id="userId" onchange="setTeamNameList('userId','tmName');" >
                                        <option value="" selected></option>
                                        <?php while($res = mysql_fetch_array($userResult))
                                        { 
                                        $selected = "";
                                        if ( $res['id'] == $userId )
                                        $selected = "selected";
                                        $optName=$res['lastname'].", ".$res['firstname'];
                                        if ( $webAdmin == "yes") 
                                        $optName .= " of ".$res['teamName'];
                                        ?>
                                        <option value="<?= $res['id'] ?>" <?=$selected?> ><?= $optName ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ( $webAdmin == "yes" && $formAction != "delete" && $formAction != "view" ) { ?>
                            <tr>
                                <td>
                                    Member Of Team: 
                                </td>
                                <td>
                                <?php if ( $id == "" ) { ?>
                                    <select name="tmName" id="tmName" >
                                        <option value="" selected></option>
                                        <?php while($res = mysql_fetch_array($teamResult))
                                        { 
                                        $selected = "";
                                        if ( $res['teamName'] == $tmName )
                                        $selected = "selected";
                                        ?>
                                        <option value="<?= $res['teamName'] ?>" <?=$selected?> ><?= $res['teamName'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } else { ?>
                                    <input type="hidden" name="tmName" id="tmName" value="<?=$tmName?>"/>
                                    <?=$tmName?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?> 
                            <tr>
                            <?php if ( $teamManager == "yes") {
                                if ( $formAction != "view" ) { ?>
                                <td><input type="submit" name="cancel" id="cancel" value="Cancel" /></td>
                                <?php } else { ?>
                                <td><input type="submit" name="cancel" id="cancel" value="Back" /></td>
                                <?php }
                                } else { 
                                if ( $formAction != "view" ) { ?>
                                <td><input type="button" onclick="cancelButton();" name="cancel" id="cancel" value="Cancel"/></td>
                                <?php } else { ?>
                                <td><input type="button" onclick="cancelButton();" name="back" id="back" value="Back"/></td>
                                <?php }
                                }
                                if (  $formAction != "delete"  && $formAction != "view" ) {?>
                                <td><input type="submit" name="save" id="save" value="Save" /></td>
                                <?php } else { 
                                if ( $formAction != "view" ) { ?>
                                <td><input type="submit" name="delete" id="delete" value="Delete" /></td>
                                <?php      }
                                } ?>
                            </tr>
                        </table>
                        <?php if ( $formAction == "view" ) { ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    <iframe bgcolor="#000000" class="style1" width="99%" height="600" src="../PHP-FileManager/filemanager.php?section=toAwards" name="frame3" border="0" marginwidth="0" marginheight="0">
                                </td>
                            </tr>
                        </table>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>