<?php

	include('AES_base.php');


	// ===================== Get through sessions plain AES components =====================
		// Start the session
		session_start();

		// Get AES components Vector using sessions
		$AES_components = $_SESSION['AES_components'];
	// ===================== ========================================= =====================


	// ===================== Get AES encrypted registration form values =====================
		// Get AES encrypted name from registration form
		$AES_encrypted_name = $_POST['AES_encrypted_name'];

		// Get AES encrypted surname from registration form
		$AES_encrypted_surname = $_POST['AES_encrypted_surname'];

		// Get AES encrypted email from registration form
		$AES_encrypted_email = $_POST['AES_encrypted_email'];

		// Get AES encrypted birth date from registration form
		$AES_encrypted_birth_date = $_POST['AES_encrypted_birth-date'];

		// Get AES encrypted password from registration form
		$AES_encrypted_password = $_POST['AES_encrypted_pwd'];

		// Get AES encrypted repeated password from registration form
		$AES_encrypted_again_password = $_POST['AES_encrypted_again-pwd'];
	// ===================== ========================================== =====================


	// ===================== Decrypt AES encrypted registration form values =====================
		// Decrypt AES encrypted name 
		$name = AES_decrypt_string($AES_encrypted_name, $AES_components);

		// Decrypt AES encrypted surname 
		$surname = AES_decrypt_string($AES_encrypted_surname, $AES_components);

		// Decrypt AES encrypted email 
		$email = AES_decrypt_string($AES_encrypted_email, $AES_components);

		// Decrypt AES encrypted birth date 
		$birth_date = AES_decrypt_string($AES_encrypted_birth_date, $AES_components);

		// Decrypt AES encrypted password 
		$password = AES_decrypt_string($AES_encrypted_password, $AES_components);

		// Decrypt AES encrypted repeated password 
		$again_password = AES_decrypt_string($AES_encrypted_again_password, $AES_components);
	// ===================== ============================================== =====================


	// ===================== Check and process form values =====================
		// Check if the two fetched password are not equal
		if($password != $again_password)
		{
			// Print a bootstrap-formatted error alert
			echo '<div class="alert alert-danger alert-dismissible fade show ms-3 me-3" role="alert">
					Le password non corrispondono
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
				  </div>';

			// Exit from the PHP script
			return;
		}

		// Convert birth date from Javascript format to typical MySQL format 
		$mysql_date = date("Y-m-d", strtotime($birth_date));

		// Hash and salt the password to ensure passwords, if stolen, cannnot be used
		//		password_hash function is used with Bcrypt hash algorithm (128-bit hash length)
		$hashed_and_salted_password = password_hash($password,  PASSWORD_BCRYPT, ["cost" => 11]);
	// ===================== ================= =====================
	

	// ===================== Insert form values in database =====================
		// Open MySQL connection with mysqli framework
		$connection = mysqli_connect("localhost","anto","123","libreria_ad_astra");

		// Check if the connection has failed
		if (!$connection)
			// Print the error brief description and exit
			die("Connection failed: " . mysqli_connect_error());

		// The query to create a user in the database
		$query = "INSERT INTO utenti (
							nome, 
							cognome, 
							data_nascita, 
							codice_tipo_utente, 
							email, 
							password)
		   			   	 VALUES (
	   			   			'$name',
	   			   			'$surname', 
	   			   			'$mysql_date', 
	   			   			1, 
	   			   			'$email', 
	   			   			'$hashed_and_salted_password');";

	   	// Run the query and chech the exit code
		if(mysqli_query($connection, $query))
			// If the user has been added
			//		print a bootstrap-formatted alert
			echo '<div class="alert alert-success alert-dismissible fade show ms-3 me-3" role="alert">
					Il tuo utente è stato creto. Benvenuto!
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
				  </div>';
	// ===================== =============================== ====================

?>