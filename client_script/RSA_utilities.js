"use strict";


// Class that represents RSA keys
class RSA_key
{
	// String-encoded hexadecimal parameters without the 0x prefix are expected (es. "EF893A")
	constructor(hex_exponent, hex_module)
	{
		// Values are represented as sjcl big number objects
		this.exponent = new BigInteger(hex_exponent, 16);
		this.module = new BigInteger(hex_module, 16);
	}
}


// Function to crypt a characters string using the RSA cipher
//		output is an array of crypted characters
function RSA_encrypt_string(string, public_key)
{

	// Array of crypted characters
	var encrypted_chars_array = new Array();

	// Crypt each plaintext ASCII value
	for (var i = 0; i < string.length; i++) 
	{
		// String character as an ASCI value
		//		represented as a BigInteger jsbn objects
		var ASCII_char = new BigInteger(string.charCodeAt(i).toString());

		// Crypt the numeric ASCII value
		//		the RSA operation is a modular exponentiation: ((ascii character)^(public key exponent) % public key module)
		//		done using the modPow jsbn method
		var encrypted_char = ASCII_char.modPow(public_key.exponent, public_key.module);

		// Turn the crypted value into an hexadecimal value
		//		the toString jsbn method is overrided, it returns the hexadecimal translation of the value
		var hex_encrypted_char = "0x" + encrypted_char.toString(16);

		// Add the crypted value to the array of crypted values
		encrypted_chars_array.push(hex_encrypted_char);
	}

	// Return the array of RSA encrypted characters
	return encrypted_chars_array;
}

// Function to get a RSA public key from the server
function get_RSA_public_key()
{
	
	// Open a synchronized POST connection with the server PHP page
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "../server_script/RSA_public_key.php", false);

	// Send a request to the server
	xhttp.send();

	// Get the public key encoded as a JSON document with two elements: publicExponent and module
	//		it is represented as a javascript oject
	var JSON_RSA_public_key = JSON.parse(xhttp.responseText);

	// Initialize a RSA public key object with the received values
	var RSA_public_key = new RSA_key(JSON_RSA_public_key.public_exponent, JSON_RSA_public_key.module);

	// Return the RSA public key
	return RSA_public_key;
}
