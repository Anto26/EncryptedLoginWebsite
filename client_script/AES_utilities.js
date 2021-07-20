"use strict";


// Standard AES compoents byte-length
const STANDARD_AES_IV_BYTE_LENGTH = 16;
const STANDARD_AES_KEY_BYTE_LENGTH = 32;


// Function to generate random AES components
//		they are returned as hexadecimal values
function generate_AES_components()
{

	// Initialize the AES components associative array
	var AES_components = [];

	// Generate AES private key
	//		convert it to hexadecimal
	AES_components['IV'] = random_BigInteger(STANDARD_AES_IV_BYTE_LENGTH).toString(16);

	// Generate AES private Initial Vector as hexadecimal
	//		convert it to hexadecimal
	AES_components['key'] = random_BigInteger(STANDARD_AES_KEY_BYTE_LENGTH).toString(16);

	// Return the generated AES components
	return AES_components;
}


// Function to securely send an AES private key to a PHP page
//		privacy is ensured by RSA encryption adn AES algorithm
//		authentication is not ensured yet
function send_AES_components(AES_components)
{

	// Get a RSA public key
	var RSA_public_key = get_RSA_public_key();


	// ===================== Encrypt AES components with RSA ===================== 
		// Encrypt AES IV with RSA
		var AES_encrypted_IV = RSA_encrypt_string(AES_components['IV'], RSA_public_key);

		// Encrypt AES key with RSA
		var AES_encrypted_key = RSA_encrypt_string(AES_components['key'], RSA_public_key);
	// ===================== =============================== =====================


	// ===================== Encode encrypted AES components with JSON ===================== 
		// Encode encrypted AES Initial Vector with JSON
		var JSON_AES_encrypted_IV = JSON.stringify(AES_encrypted_IV);

		// Encode encrypted AES key with JSON
		var JSON_AES_encrypted_key = JSON.stringify(AES_encrypted_key);
	// ===================== ========================================= ===================== 


	// ===================== Send RSA encrypted AES components to PHP page =====================
		// Open a POST connection with the PHP page
		//		Ajax is used, but in synchrnonized mode (deprecated)
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "../server_script/AES_private_key.php", false);

		// Format AES components as a FormData object
		//		easier to be interpreted by PHP
		var AES_encrypted_components = new FormData();
		AES_encrypted_components.append("AES_encrypted_IV", JSON_AES_encrypted_IV);
		AES_encrypted_components.append("AES_encrypted_key", JSON_AES_encrypted_key)

		// Send RSA encrypted AES components to the server
		xhttp.send(AES_encrypted_components);
	// ===================== ============================================= =====================
}

// Function to encrypt a characters string with AES algorithm
//		the encrypted string is returned as a Base64-encoded string
function AES_encrypt_string(string, AES_components)
{

	// ===================== Convert hexadecimal AES components to CryptoJS objects =====================
		// Convert the hexadecimal AES IV to CryptoJS object
		var cryptojs_iv = CryptoJS.enc.Hex.parse(AES_components['IV']);

		// Convert the hexadecimal AES key to CryptoJS object
		var cryptojs_key = CryptoJS.enc.Hex.parse(AES_components['key']);
	// ===================== ====================================================== =====================

	// Encrypt the characters string with AES algorithm
	//		the output is a wordarray CryptoJS object
	var encrypted_string_wordarray = CryptoJS.AES.encrypt(string, cryptojs_key, {iv: cryptojs_iv});

	// Convert the CryptoJS-type encrypted string to Base64-notation string
	//		it seems to be a standard to sends symmetric-encrypted values using Base64 notation
	var encrypted_string_base64 = encrypted_string_wordarray.toString();

	// Return the encrypted string
	return encrypted_string_base64;
}

// Function to decrypt a base64-encoded AES encrypted string
function AES_decrypt_string(base64_string, AES_components)
{

	// ===================== Convert hexadecimal AES components to CryptoJS objects =====================
		// Convert the hexadecimal AES IV to CryptoJS object
		var cryptojs_iv = CryptoJS.enc.Hex.parse(AES_components['IV']);

		// Convert the hexadecimal AES key to CryptoJS object
		var cryptojs_key = CryptoJS.enc.Hex.parse(AES_components['key']);
	// ===================== ====================================================== =====================

	// Decrypt the characters string with AES algorithm
	//		the output is a wordarray CryptoJS object
	var decrypted_string_wordarray = CryptoJS.AES.decrypt(base64_string, cryptojs_key, {iv: cryptojs_iv});

	// Convert the CryptoJS plain-text string to a Utf-8-encoded string
	var decrypted_string = decrypted_string_wordarray.toString(CryptoJS.enc.Utf8);

	// Return the decrypted string
	return decrypted_string;
}

// Function to encrypt a HTML form with AES algorithm
//		the encrypted form is returned as a FormData object
function AES_encrypt_form(form_name, AES_components)
{

	// Initialize the virtual form which will contain encrypted values
	var encrypted_form = new FormData;

	// For each value in the form
	for (var i = 0; i < document.forms[form_name].length; i++) 
	{
		// If the value is not the submit button
		if(document.forms[form_name][i].type != "submit")
		{
			// Encrypt the form value with AES algorithm
			var encrypted_form_value = AES_encrypt_string(document.forms[form_name][i].value, AES_components);

			// Add the encrypted value to the virtual form
			//		a meaningful suffix is added to each value name ("AES_encrypted_")
			encrypted_form.append("AES_encrypted_" + document.forms[form_name][i].name, encrypted_form_value);
		}
	}

	// Return the AES encrypted virtual form
	return encrypted_form;
}


// Function to get the AES components according to the cookies status
function load_AES_components_by_cookie()
{

	// If cookies about AES components alredy exist
	if(get_cookie('AES_key') && get_cookie('AES_IV'))
	{
		// ===================== Create an AES_components array with existing cookie values =====================
			// Initialize the AES components associative array
			var AES_components = [];

			// Set the AES Initial Vector with the retrieved Initial Vector from cookies
			AES_components['IV'] = get_cookie("AES_IV");

			// Set the AES key with the retrieved key from cookies
			AES_components['key'] = get_cookie("AES_key");
		// ===================== ========================================================== =====================
	}
	// If cookies about AES components don't exist yet
	else
	{
		// ===================== Generate new AES components and save them as cookies =====================
			// Initialize the AES components associative array
			var AES_components = generate_AES_components();

			// Securely send the generated AES components to the server
			send_AES_components(AES_components);

			// Create a cookie about the generated AES Initial Vector
			document.cookie = "AES_IV=" + AES_components['IV'];

			// Create a cookie about the generated AES key
			document.cookie = "AES_key=" + AES_components['key'];
		// ===================== ==================================================== =====================
	}

	// Return the AES components associative array
	//		whether it has been generated or just fetched from existing cookies
	return AES_components;
}
