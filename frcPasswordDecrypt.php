<?php
require_once dirname(__FILE__) .'/PHP-GlobalIncludes/auth.php';
require_once dirname(__FILE__) .'/PHP-GlobalIncludes/encdec.php';
require_once dirname(__FILE__) .'/PHP-DAOs/userDAO.php';

require_once dirname(__FILE__) .'/PHP-GlobalIncludes/AES128.php';
$decode = new AES128();
$salt=$decode->makeKey($salt);

$userDAO = new userDAO();

$qry = "select u.id, p.email, u.lastname, u.firstname, u.login, u.passwd from user u, companies c, userprofile p where p.userId = u.id and p.companyId = c.id";

$mailList = $userDAO->executeQry($qry);
?>

<html>
    <head>
    </head>
    <body>
        <table>
            <tr>
                <th>ID</th>
                <th>UpperEmail</th>
                <th>LowerEmail</th>
                <th>LastName</th>
                <th>firstName</th>
                <th>login</th>
                <th>password</th>
            </tr>
<?php
        while($res = mysql_fetch_array($mailList))
        {
            $pPassword = $decode->blockDecrypt($res['passwd'],$salt);
?>
            <tr>
                <td>
                    <?php echo $res['id']; ?>
                </td>
                <td>
                    <?php echo strtoupper($res['email']); ?>
                </td>
                <td>
                    <?php echo strtolower($res['email']); ?>
                </td>
                <td>
                    <?php echo $res['lastname']; ?>
                </td>
                <td>
                    <?php echo $res['firstname']; ?>
                </td>
                <td>
                    <?php echo $res['login']; ?>
                </td>
                <td>
                    <?php echo $pPassword; ?>
                </td>
            </tr>
<?php
        }
?>
        </table>
    </body>
</html>