<?php

	// ===================== Retrieve the filter option from the client =====================
		// Fetch the search bar written characters
		$search_keywords = $_POST['search_keywords'];

		// Fetch the number of books to be shown in each page
		//		a page switching mechanism hasn't been implemented yet
		$num_products_per_page = $_POST['num_products_per_page'];
	// ===================== ========================================== =====================


	// ===================== Fetch value from the DB =====================
		// Open MySQL connection with mysqli framework
		$connection = mysqli_connect("localhost","anto","123","libreria_ad_astra");

		// Check if the connection has failed
		if (!$connection)
			// Print the error brief description and exit
			die("Connection failed: " . mysqli_connect_error());

		// The query to fetch from the DB the products
		//		with name and price
		$books_query = "SELECT nome, prezzo 
							FROM prodotti
							WHERE nome LIKE '$search_keywords%'
							ORDER BY nome";

		// Run the query
		$query_result = mysqli_query($connection, $books_query);
	// ===================== ======================== ====================


	// ===================== Format the message that has to be sent to the client =====================
		// String to be sent out
		$result = "";

		// If a record at least has been got
		if (mysqli_num_rows($query_result) > 0) 
		{
			// Save query results as an associative array
			$query_rows = mysqli_fetch_all($query_result, MYSQLI_ASSOC);

			// Cycle to print books HTML elements
			for(

				// Set the counter to zero
				$i = 0;

				// As long as the counter is lower than the number of records
				$i < count($query_rows) &&

				// If the number of products to be shown is -1 (default value when "All" has been sent as number of products to be shown in a page)
				//		Do not put any other condition, so show each fetched record
				// Else:
				//		Put the condition: As long as the counter is lower than the number of products to be shown in a page
				(($num_products_per_page == -1) ? True : ($i < $num_products_per_page));

				// Increase the counter by one	
				$i++)
			{

				// Add to the result to be sent a product
				//		each %s will be replaced with values under this string
				//		some values are reapeted more than one time due to the need of showing them in tags and as titles of the tags
				//		title of tag is shown when someone move the cursor on the tag
				$result .= sprintf('
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-5 ps-3 pe-3 pb-4">
						<div class="card border border-danger">
							<div class="card-header">
								<h5 class="card-title text-capitalize text-truncate text-centers">

									<a title="%s">
										%s
									</a>

								</h5>
							</div>
							<div class="card-body">
								<p class="card-subtitle text-muted text-capitalize text-truncate">

									€ %s

								</p>
							</div>
						</div>
					</div>',

					$query_rows[$i]["nome"],
					$query_rows[$i]["nome"],
					$query_rows[$i]["prezzo"]);
			}
		}
	// ===================== ==================================================== =====================


	// Print the processed result
	echo $result;

?>