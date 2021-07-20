<!DOCTYPE html>

<html>
	<head>
		<title>Libreria Ad Astra - Prodotti</title>

		<meta charset="utf-8">

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

		<!-- Bootstrap JS -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

		<!-- Bootstrap icons -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

		<!-- JQuery -->
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>

		<!-- CryptoJS AES implementation -->
		<script src="../client_script/libraries/CryptoJS-aes.js"></script>

		<!-- Tom Wu's JSBN javascript library -->
		<script src="../client_script/libraries/jsbn/jsbn.js"></script>
		<script src="../client_script/libraries/jsbn/jsbn2.js"></script>

		<!-- Local scripts -->
		<script src="../client_script/RSA_utilities.js"></script>
		<script src="../client_script/AES_utilities.js"></script>
		<script src="../client_script/authentication.js"></script>
		<script src="../client_script/miscellaneous.js"></script>

		<!-- Local CSS rules -->
		<link rel="stylesheet" href="../style/style.css">
	</head>

	<body>


		<!-- ===================== Navigation bar ===================== -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-lg-top">
		 	<div class="container-fluid">

			 	<!-- Moon icon -->
		  		<i class="bi bi-moon-stars-fill text-danger fs-1 me-2"></i>

		  		<!-- Title -->
			    <div class="navbar-brand me-3"><span class="display-6">Ad Astra</span></div>

				<!-- Hidden responsive button -->
			    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
			    </button>

			    <div class="collapse navbar-collapse" id="navbarSupportedContent">

			    	<!-- ===================== Navbar unordered list ===================== -->
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link" aria-current="page" href="index.php">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="books.php">Libri</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="products.php">Prodotti</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="contacts.php">Contattaci</a>
						</li>
					</ul>
					<!-- ===================== ===================== ===================== -->

					<!-- ===================== Show enter button or current user name and surname ===================== -->
					<?php
						// Start the session 
						session_start();

						// If login has been made
						//		thereofre if user id session variable has been set
						if(isset($_SESSION['db_codice_utente']))
						{

							// ===================== Fetch user data from DB =====================
								// Retrieve the user id from session
								$codice_utente = $_SESSION['db_codice_utente'];

								// Open the MySQL connection with the DB server
								$connection = mysqli_connect("localhost","anto","123","libreria_ad_astra");

								// Check if an error occured
								if (!$connection) die("Connection failed: " . mysqli_connect_error());

								// QUery to fetch from the DB the name ans surnme of the user
								$query = "SELECT nome, cognome
											FROM utenti 
											WHERE codice_utente='$codice_utente';";

								// Run the query on the DB
								$query = mysqli_query($connection, $query);

								// Fetch just one record from the query result
								//		as an associative array
								$user_record = mysqli_fetch_assoc($query);
							// ===================== ======================= =====================


							// ===================== Save fetched user data =====================
								// Save user name
								$name = $user_record['nome'];

								//Save user surname
								$surname = $user_record['cognome'];
							// ===================== ====================== =====================


							// Output a bootstrap-formatted link with the name and the surname as values
							echo "<a class=\"link-danger me-3 text-capitalize\">$name $surname</a>";
							
						}

						// If login hasn't been made
						//		due to the unset user id session variable
						else
							// Output the button to access the website
							echo '<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#login-modal">Entra</button>';
					?>
					<!-- ===================== ================================================== ===================== -->

		    	</div>
		  	</div>
		</nav>
	  	<!-- ===================== ============== ===================== -->


	  	<!-- ===================== Login popup ===================== -->
		<div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"> Login</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>	
					<div class="modal-body">

						<!-- ===================== Login form ===================== -->
						<form role="form" name="login-form" method="POST" onsubmit="login(); return false;">

							<div class="form-floating mb-4">
								<!-- Email field -->
								<input type="email" name="email" class="form-control" placeholder="" required>
								<label for="email">Email</label>
							</div>

							<div class="form-floating mb-4">
								<!-- Password field -->
								<input type="password" name="pwd" class="form-control" maxlength='50' placeholder="" required>
								<label for="name">Password</label>
							</div>

							<!-- Submit button -->
							<button type="submit" name="submit" class="btn btn-lg btn-danger w-100">Accedi</button>
							
						</form>
						<!-- ===================== ========== ===================== -->

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
						<button type="button" class="btn btn-outline-danger" data-bs-target="#register-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Registrati</button>
					</div>

					<!--  Login response  -->
					<div id="login-response"></div>

				</div>
			</div>
		</div>
		<!-- ===================== =========== ===================== -->


		<!-- ===================== Registration popup ===================== -->
		<div class="modal fade" id="register-modal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"> Registrati</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">

						<!-- ===================== Registration form ===================== -->
						<form role="form" name="registration-form" method="POST" onsubmit="register(); return false;">

							<div class="form-floating mb-4">
								<!-- Name field -->
								<input type="text" name="name" class="form-control" placeholder="" required>
								<label for="name">Nome</label>
							</div>

							<div class="form-floating mb-4">
								<!-- Surname field -->
								<input type="text" name="surname" class="form-control" placeholder="" required>
								<label for="surname">Cognome</label>
							</div>

							<div class="form-floating mb-4">
								<!-- Birth date field -->
								<input type="date" name="birth-date" class="form-control" placeholder="" required>
								<label for="birth-date">Data di nascita</label>
							</div>

							<div class="form-floating mb-4">
								<!-- Email field -->
								<input type="email" name="email" class="form-control" placeholder="" required>
								<label for="email">Email</label>
							</div>

							<div class="form-floating mb-4">
								<!-- Passoword field -->
								<input type="password" name="pwd" class="form-control" maxlength='50' placeholder="" required>
								<label for="pwd">Password</label>
							</div>

							<div class="form-floating mb-4">
								<!-- Again password field -->
								<input type="password" name="again-pwd" class="form-control" maxlength='50' placeholder="" required>
								<label for="again-pwd">Ripeti password</label>
							</div>

							<!-- Submit button -->
							<button type="submit" name="submit" class="btn btn-lg btn-danger w-100">Registrati</button>

						</form>
						<!-- ===================== ================= ===================== -->

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
						<button type="button" class="btn btn-outline-danger" data-bs-target="#login-modal" data-bs-toggle="modal" data-bs-dismiss="modal">Login</button>
					</div>

					<!-- Registration response -->
					<div id="registration-response"></div>

				</div>
			</div>
		</div>
		<!-- ===================== ================== ===================== -->


  		<div class="container-fluid mt-3">
	        <div class="row">

	        	<!-- ===================== Filtering options ===================== -->
        		<div class="col-md-2" style="">

      				<!-- Search bar -->
					<input type="search" id="search-bar" class="form-control mb-3" aria-label="Search" placeholder="Cerca" oninput="loadProducts();">

					<!-- Number of books to be shown -->
					<select id="num-products" class="form-select" aria-label="select" onchange="loadProducts();">
						<option value="50" selected disabled>Numero di libri da mostrare</option>
						<option value="30">30</option>
						<option value="50">50</option>
						<option value="80">80</option>
						<option value="-1">Tutti</option>
					</select>

	            </div>
	            <!-- ===================== ================= ===================== -->

	            <div class="col-md-10 mt-4 mt-md-0">
					<div class="container-fluid">
						<div class="row">

							<!-- Element in which products are loaded and shown -->
							<div id="products-page-content" class="card-group"></div>

						</div>
					</div>  
	            </div>
	        </div>
		</div>
	</body>
</html>