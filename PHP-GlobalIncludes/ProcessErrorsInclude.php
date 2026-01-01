<?php
if ( !isset($errflag) && isset($_SESSION['ERRFLAG']) && $_SESSION['ERRFLAG'] != "" ) {
    $errflag = true;
}
if( $errflag && isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
    echo '<ul class="err">';
    foreach($_SESSION['ERRMSG_ARR'] as $msg) {
            echo '<li>',$msg,'</li>';
        }
    echo '</ul>';
    $_SESSION['ERRMSG_ARR'] = "";
    unset($_SESSION['ERRMSG_ARR']);
    unset($_SESSION['ERRFLAG']);
}
?>