<?php
##########################################################################
# COPYRIGHT NOTICE
# © 2015 Michael Jacobsen
# All rights reserved
# This copyright notice MUST appear in all copies of the script!
# @author				: Michael Jacobsen <-- place email address here -->
# @package				: Michael Jacobsen CMS (Content Management System)
# @file last updated	: 04.04.2015
##########################################################################

class encryptDecrypt{
	//#################################################################
	// ENCRYPT INFO
	//#################################################################
	function encrypt($pure_string, $encryption_key) {

		/* THIS IS THE NEW ENCRYPTION METHOD*/
		$encrypt_method = "AES-256-CBC";
	    $secret_key = $encryption_key;
	    $secret_iv = $encryption_key;

	    // hash
	    $key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);

		$output = openssl_encrypt($pure_string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
		return $output;
	}

	//#################################################################
	// DECRYPT INFO
	//#################################################################
	function decrypt($encrypted_string, $encryption_key) {

		/* THIS IS THE NEW ENCRYPTION METHOD*/
		$encrypt_method = "AES-256-CBC";
	    $secret_key = $encryption_key;
	    $secret_iv = $encryption_key;

	    // hash
	    $key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);

		$output = openssl_decrypt(base64_decode($encrypted_string), $encrypt_method, $key, 0, $iv);

		return $output;
	}
}
?>
