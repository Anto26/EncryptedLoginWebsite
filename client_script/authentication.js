"use strict";


// Function to fully carry out registration with remote PHP scripts
//		it includes establishing a secure connection with server scripts
//		using self-implemented RSA and CryptoJS AES in hybrid manner
function register()
{

	// Show spinner loading animation in submit button
	document.forms['registration-form']['submit'].innerHTML = `
		<div class="spinner-border text-light" role="status" style="width: 1.5rem; height: 1.5rem;">
  			<span class="visually-hidden">Loading...</span>
		</div>`;


	// ===================== AES Encrypt registration form  =====================
		// Load AES components from cookies if they exist
		//		otherwise create them from scratch and and send them to server
		var AES_components = load_AES_components_by_cookie();

		// AES Encrypt each registration form value
		var encrypted_registration_form = AES_encrypt_form('registration-form', AES_components);
	// ===================== =============================  =====================


	// ===================== Synchronous POST connection with PHP registration script =====================
		// Open Synchronized POST connection with registration script
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "../server_script/registration.php", false);

		// Send AES encrypted registration form to the registration script
		xhttp.send(encrypted_registration_form);

		// Output registration script response to the registration-response HTML element
		document.getElementById("registration-response").innerHTML = xhttp.responseText;
	// ===================== ========================================================= =====================


	// Stop spinner loading animation in submit button
	document.forms['registration-form']['submit'].innerHTML = "Registrati";
}


// Function to fully carriy out login with a remote PHP script
//		it includes establishing a secure connection with server scripts
//		using self-implemented RSA and CryptoJS AES in hybrid manner
function login()
{

	// Show spinner loading animation in submit button
	document.forms['login-form']['submit'].innerHTML = `
		<div class="spinner-border text-light" role="status" style="width: 1.5rem; height: 1.5rem;">
  			<span class="visually-hidden">Loading...</span>
		</div>`;


	// ===================== AES Encrypt login form  =====================
		// Load AES components from cookies if they exist
		//		otherwise create them from scratch and and send them to server
		var AES_components = load_AES_components_by_cookie();

		// AES Encrypt each login form value
		var encrypted_login_form = AES_encrypt_form('login-form', AES_components);
	// ===================== =============================  =====================


	// ===================== Synchronized POST connection with PHP login script =====================
		// Open Synchronized POST connection with login script
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "../server_script/login.php", false);

		// Send AES encrypted login form to the login script
		xhttp.send(encrypted_login_form);

		// Output login script response to the login-response HTML element
		document.getElementById("login-response").innerHTML = xhttp.responseText;
	// ===================== ========================================================= =====================


	// Stop spinner loading animation in submit button
	document.forms['login-form']['submit'].innerHTML = "Accedi";
}