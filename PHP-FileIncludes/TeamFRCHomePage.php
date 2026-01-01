<?php
require_once dirname(__FILE__) .'/../PHP-GlobalIncludes/auth.php';
?>
<html>
    <head>
        <link href="../PHP-css/gmail.css" rel="stylesheet" type="text/css">
        <script language="javascript" type="text/javascript">
            function submitForm() {
                document.forms['homePage'].submit();
            }
        </script>
    </head>
    <body>
        <span>
        <table width="98%" align="right">
            <tr>
                <td>
                    <h2>Welcome <?=$teamMemberName?> to the <?=strtoupper($teamName)?> website.</h2><br/><br/>
                    <p align="left" class="style36"><br />
                    This website is dedicated to the Information Technology Service-Small Business (ITS-SB) IDIQ Contract.  It
                    is designed to assist you in meeting the goals, requirements and contractual requirements of a demanding
                    economy.  Together, we can make this a cooperative effort to meet the needs of the customer.
                    </p>
                </td>
            </tr>
            <tr>
                <td valign="bottom" align="right">
                 <img src="/PHP-images/inc5000Web.jpg"/>
                </td>
            </tr>
        </table>
    </body>
</html>