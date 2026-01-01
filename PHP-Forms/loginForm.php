<?php ?>
        <form id="form1" name="form1" method="post" action="../PHP-Actions/loginFormAction.php">
            <div align="center">
                <p align="center">
                    <strong>Team FRC Log-In</strong>
                </p>
            </div>
            <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                    <td align='right'>User Id: </td>
                    <td><input size='40' name="login" id="login" type="text" size="15" /></td>
                </tr>
                <tr>
                    <td align='right'>Password: </td>
                    <td><input size='40' name="password" id="password" type="password" size="15" /></td>
                </tr>
                <tr>
                    <td  colspan="2" align='center'><input type="submit" name="Submit" value="Submit" />
                    &nbsp;&nbsp;<input name="Clear" type="submit" id="Clear" value="Clear" /></td>
                </tr>
                <tr>
                    <td colspan="2" align='center'>
                        <a style="color:#000000" href="forgotPasswordForm.php?section=ForgotPassword">Forgot Password</a>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>