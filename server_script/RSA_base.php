<?php

	// Function to convert GMP numbers to hexadecimal string
	function gmp_to_hex($number)
	{
		$hex = "";

	    do 
	    {    
	        $last = gmp_mod($number, 16);
	        $hex = dechex($last) . $hex;
	        $number = ($number-$last)/16;
	    } 
	    while($number>0);

	    return $hex;
	}

	// Class for RSA public and private keys
	class RSA_key
	{
		// Attributes with default values
		//		-1 means they hasn't been set
		public $exponent = -1;
		public $module = -1;

		// Constructor
		function __construct($exponent, $module)
		{
			$this->exponent = $exponent;
			$this->module = $module;
		}
	}

	// Function to decrypt a string represented as an array of characters encrypted using the RSA algorithm
	//		each encrypted character is encoded as an hexadecimal value
	function RSA_decrypt_string($encryped_string_array, $private_key)
	{
		$plain_string = "";

		// For each character of the string
		for($i = 0; $i < count($encryped_string_array); $i++) 
		{
			// ASCII character as GMP object
			//		a conversion from hex to gmp value is done, due to the fact that each character is hex-encoded
			$encrypted_ASCII = gmp_init($encryped_string_array[$i]);

			// Decrypt the ASCII value
			//		the RSA operation is a modular exponentiation: ((encrypted ascii)^(private key exponent) % public key module)
			//		done using the gmp_powm function
			$plain_ASCII_GMP = gmp_powm($encrypted_ASCII, $private_key->exponent, $private_key->module);

			// Turn the plain GMP object to a PHP integer value (at the moment is the ASCII value)
			$plain_ASCII = gmp_intval($plain_ASCII_GMP);

			// Turn the ASCII value in a PHP character
			$plain_char = chr($plain_ASCII);

			// Add the plain character to the final string, which will be returned
			$plain_string .= $plain_char;
		}

		// Return the decrypted string
		return $plain_string;
	}

	function generate_RSA_keys()
	{
		$euler_function = gmp_init(0);

		// The public exponent is fixed due to performance reason
		//		in RSA is e
		$public_exponent = gmp_init(65537);

		// Cycle until the GCD of the public exponent adn the Euler function is equal to one
		//		until they are coprime
		do
		{
			// Generate two random values whose lengths are no more than 1024-bit long
			$first_random_value = gmp_random_bits(1024);
			$second_random_value = gmp_random_bits(1024);

			// Find the two prime numbers which are next to the random values
			//		in RSA are p and q
			$first_prime_number = gmp_nextprime($first_random_value);
			$second_prime_number = gmp_nextprime($second_random_value);

			// Calculate the Euler function of the module (p*q)
			// in RSA is φ(N), which is equal to (p-1)*(q-1)
			$euler_function = ($first_prime_number-1)*($second_prime_number-1);
		}
		while(gmp_gcd($public_exponent, $euler_function) != 1);

		// Calculate the module
		//		in RSA is N
		$module = $first_prime_number*$second_prime_number;
			
		// Calculate the private exponent, corresponding to the inverted public exponent
		//		in RSA is d
		$private_exponent = gmp_invert($public_exponent, $euler_function);

		// The public key
		//		in RSA is (e, N)
		$public_key = new RSA_key($public_exponent, $module);

		// The private key
		//		in RSA is (d, N) (the Euler function is not needed anymore)
		$private_key = new RSA_key($private_exponent, $module);

		// Encode the two keys as an array of two elements
		$keys = array
		(
			"public_key" => $public_key, 
			"private_key" => $private_key
		);

		// Return the pair of generated RSA keys
		return $keys;
	}

?>