<?php

	// Class to represent the main AES components
	//		the Initial Vector and the private key
	class AES_components
	{
		// Attributes with default values
		//		-1 means they haven't been initialized yet
		public $IV = -1;
		public $key = -1;

		// Constructor
		function __construct($IV, $key)
		{
			$this->IV = $IV;
			$this->key = $key;
		}
	}

	// Function to encrypt a characters string using the AES algorithm
	//		a Python script is used to encrypt using AES
	function AES_encrypt_string($string, $AES_components)
	{
		// Encrypt the string using a Python script
		$encrypted_strign = shell_exec("python3 python/AES.py $AES_components->IV $AES_components->key -e \"$string\"");

		// Return the AES encrypted string
		return $encrypted_strign;
	}

	// Function to decrypt an AES ecrypted characters string
	//		a Python script is used to decrypt using AES
	function AES_decrypt_string($encrypted_string, $AES_components)
	{
		// Decrypt the string using a Python script
		$decrypted_strign = shell_exec("python3 python/AES.py $AES_components->IV $AES_components->key -d \"$encrypted_string\"");

		// Return the AES encrypted string
		return $decrypted_strign;
	}

?>