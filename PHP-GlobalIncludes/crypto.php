<?php

/* * *  ***********************************************************

	GPLed Software, use at your own risk.  The Only requirement
	is that you understand this statement: "The class Crypto
	has not been tested or verified as to the level of security
	or encryption it offers.  You are using this product at your
	own risk.   Do not expect highly regarded information to
	be completely safe with this or any encryption program."

	Use of this software confirms understanding of the above
	statement.

 * * *  *********************************************************** */

class Crypto
{

    var $ralphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890 \\!,.:;?~@#\$%^&*()_+-=][}{/><\"'`|";

    function Crypto( $Password = '1212121' ) {
	if ( !empty ( $Password ) )
	{
	    return $this->password = $Password;
	}
    }

    function encrypt ( $strtoencrypt )
    {

	$alphabet = $this->ralphabet . $this->ralphabet;

	if( !isset ( $this->password ) )
	{
	    return 'please supply a password';
        }

	$strtoencrypt = str_replace( "\t", "[tab]", $strtoencrypt);
	$strtoencrypt = str_replace( "\n", "[new]", $strtoencrypt);
	$strtoencrypt = str_replace( "\r", "[ret]", $strtoencrypt);

	for( $i=0; $i < strlen ( $this->password ); $i++ )
	{
	    $cur_pswd_ltr = substr($this->password,$i,1);
	    $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($this->ralphabet));
	}

	$i  = 0;
	$n  = 0;
	$nn = strlen ( $this->password );
	$c  = strlen ( $strtoencrypt );

	$encrypted_string = '';

	while ( $i < $c )
	{
	    $encrypted_string .=  substr ( $pos_alpha_ary[$n],
				  strpos ( $this->ralphabet, substr($strtoencrypt, $i, 1 ) ),
				  1 );
 
	    $n++;

	    if ( $n == $nn )
	    {
		$n = 0;
	    }
	    $i++;
	}

	return $encrypted_string;

    }


    function decrypt ( $strtodecrypt )
    {

	if( !isset ( $this->password ) )
	{
	    return 'please supply a password';
	}

	$alphabet = $this->ralphabet . $this->ralphabet;
	for ( $i=0; $i < strlen ( $this->password ); $i++ )
	{
	    $cur_pswd_ltr = substr ( $this->password, $i, 1 );
	    $pos_alpha_ary[] = substr ( strstr ( $alphabet, $cur_pswd_ltr),
				0,
				strlen ( $this->ralphabet ) );
	}

	$i  = 0;
	$n  = 0;
	$nn = strlen ( $this->password );
	$c  = strlen ( $strtodecrypt );

	$decrypted_string = '';

	while ( $i < $c )
	{

	    $decrypted_string .= substr ( $this->ralphabet,
				strpos ( $pos_alpha_ary[$n],
				substr ( $strtodecrypt, $i, 1) ),
				1);
 
	    $n++;
	    if ( $n == $nn )
	    {
		$n = 0;
	    }
	    $i++;
	}

	    $decrypted_string = str_replace("[tab]","\t", $decrypted_string);
	    $decrypted_string = str_replace("[new]","\n", $decrypted_string);
	    $decrypted_string = str_replace("[ret]","\r", $decrypted_string);

	return $decrypted_string;

    }


    function cryption_table ()
    {
 
	if( !isset ( $this->password ) )
	{
	    return 'please supply a password';
	}

	$alphabet = $this->ralphabet . $this->ralphabet;
	$table = '';
	for( $i=0; $i<strlen($this->password); $i++ )
	{
	    $cur_pswd_ltr = substr($this->password,$i,1);
	    $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($alphabet));
	}

	$table .= "<table border=1 cellpadding=\"0\" cellspacing=\"0\">\n";
	$table .= "<tr><td></td>";

	for( $j=0; $j < strlen ( $this->ralphabet ); $j++ )
	{

	    $ltr = substr ( $this->ralphabet, $j, 1 );
	    $table .= "<td align=\"center\"><font size=\"2\" face=\"arial\">$ltr</td>\n";
	}

	$table .= "</tr>\n\n";

	for( $i=0; $i < count ( $pos_alpha_ary ); $i++ )
	{

	    $z = $i + 1;
	    $table .= "<tr><td align=\"right\"><font size=\"2\"><b>$z</b></font></td>";

	    for( $k=0; $k < strlen ( $pos_alpha_ary[$i] ); $k++ )
	    {

		$ltr = substr($pos_alpha_ary[$i],$k,1);
		$table .= "<td align=\"center\"><font color=\"red\" size=\"2\" face=\"arial\">$ltr</td>\n";
	    }

	    $table .= "</tr>\n\n";
	}

	$table .= "</table>\n";

	return $table;
    }

} // end class Crypto

?>
