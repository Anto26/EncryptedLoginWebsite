<?php

	include('AES_base.php');


	// ===================== Get through sessions plain AES components =====================
		// Start the session
		session_start();

		// Get AES components Vector using sessions
		$AES_components = $_SESSION['AES_components'];
	// ===================== ========================================= =====================


	// ===================== Get AES encrypted login form values =====================
		// Get AES encrypted email from registration form
		$AES_encrypted_email = $_POST['AES_encrypted_email'];

		// Get AES encrypted password from registration form
		$AES_encrypted_password = $_POST['AES_encrypted_pwd'];
	// ===================== ========================================== =====================


	// ===================== Decrypt AES encrypted login form values =====================
		// Decrypt AES encrypted email 
		$email = AES_decrypt_string($AES_encrypted_email, $AES_components);

		// Decrypt AES encrypted password 
		$password = AES_decrypt_string($AES_encrypted_password, $AES_components);
	// ===================== ============================================== =====================
	
	// ===================== Check form values with database ones =====================

		// Open MySQL connection with mysqli framework
		$connection = mysqli_connect("localhost","anto","123","libreria_ad_astra");

		// Check if the connection has failed
		if (!$connection)
			// Print the error brief description and exit
			die("Connection failed: " . mysqli_connect_error());

		// The query to get a user ID and password
		$query = "SELECT codice_utente, password
					FROM utenti 
					WHERE email='$email';";

		// Run the query
		$query = mysqli_query($connection, $query);

		// If the query has returned one and only one record as result
		if(mysqli_num_rows($query) == 1)
		{
			// Fetch the record as an associative array
			$record = mysqli_fetch_assoc($query);

			// If the user sent password and the password saved in the DB are equal
			if(password_verify($password, $record['password']))
			{
				// Print a bootstrap-formatted alert
				echo '<div class="alert alert-success alert-dismissible fade show ms-3 me-3" role="alert">
						 Hai effettuato l\'accesso. Bentornato!
						 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
				  	 </div>';

				// Save the user ID as a session variable
				//		it ensures the user doesn't have to login again each time he reloads pages
				$_SESSION['db_codice_utente'] = $record['codice_utente'];
			}
			// If the user sent password and the password saved in the DB are NOT equal
		  	else
		  		// Print an bootstrap-formatted error alert
		  		echo '<div class="alert alert-danger alert-dismissible fade show ms-3 me-3" role="alert">
						 Si è verificato un errore, controllati i tuoi dati e riprova
						 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
					 </div>';
		}
		// If the query hasn't returned one and only one record
		//		hence it has returned zero record (more than one record can't happen)
		else
			// Print an bootstrap-formatted error alert
			echo '<div class="alert alert-danger alert-dismissible fade show ms-3 me-3" role="alert">
					 Si è verificato un errore, controllati i tuoi dati e riprova
					 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
			  	 </div>';
	// ===================== =============================== ====================

?>