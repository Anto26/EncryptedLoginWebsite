<?php

	include('RSA_base.php');

	// ===================== Generate a public and a private RSA key =====================
		// Generate a pair of RSA keys
		$RSA_keys = generate_RSA_keys();

		// The public key taken out from the keys array
		$RSA_private_key = $RSA_keys['private_key'];

		// The public key taken out from the keys array
		$RSA_public_key = $RSA_keys['public_key'];
	// ===================== ======================================= =====================


	// ===================== Share the RSA private key with PHP pages =====================
		// Start the session
		session_start();

		// Set a RSA private key session variable
		$_SESSION['RSA_private_key'] = $RSA_private_key;
	// ===================== ===================================== =====================


	// ===================== Convert the RSA public key from GMP object to hexadecimal number =====================
		// Convert the RSA public exponent from GMP object to hexadecimal number
		$hex_RSA_public_exponent = gmp_to_hex($RSA_public_key->exponent);

		//Convert the RSA module from GMP object to hexadecimal number
		$hex_RSA_module = gmp_to_hex($RSA_public_key->module);
	// ===================== ================================================================ =====================

	// ===================== Encode the RSA public key as a JSON document  =====================
		// Encode the RSA public key as an associative array
		$associative_array_public_key = array(
			"public_exponent" => $hex_RSA_public_exponent, 
			"module" => $hex_RSA_module);

		// Encode the associative array public key as a JSON document
		$JSON_RSA_public_key = json_encode($associative_array_public_key);
	// ===================== ============================================= =====================

	// Print the JSON-encoded RSA public key
	echo $JSON_RSA_public_key;
?>
