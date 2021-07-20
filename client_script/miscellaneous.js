'use strict';


// Function to generate a random big number
//		the input is the expected bytes size of the random number
//		the output is a pseudo-random BigInteger number (defined by the JSBN library)
function random_BigInteger(number_of_bytes) 
{
	
	// Throw an erorr if the number of bytes is lower than one
	if(number_of_bytes < 1)
		throw new Error("Invalid argument value");

	// Calculate the number of bits
	var number_of_bits = number_of_bytes*8;


	// ===================== Generate a random arrays of bits =====================
		// Initialize an array of unsigned 8-bit integer elements
		//		the number of elements is equal to the number of bits
		var array_of_bits = new Uint8Array(number_of_bits);

		// Fill the array with random values (from 0 to 255)
		//		a specific browser function is used
		//		due to its robust capability of generating pseudo-random numbers
		crypto.getRandomValues(array_of_bits);

		// Apply a function to each array element
		array_of_bits = array_of_bits.map(
			// Arrow function
			(value) => 
			{
				// Each element E is replaced with E mod 2
				return value%2;
			} 
		);

		// Set the first element (bit) of the array to one
		//		if it is zero, the random number size will be lower than the expected one
		array_of_bits[0] = 1;
	// ===================== ================================ =====================


	// Convert the bits array to a string in which each array bit is next to the other one
	var bits_as_string = array_of_bits.join("");

	// Convert the bits string to a BigInteger number
	var random_number = new BigInteger(bits_as_string, 2);

	// Return the random BigInteger
	return random_number;
}


// Function to get a specific cookie value having the name of the cookie
function get_cookie(name) 
{

	// Initialize an associative array used to store cookie key-value pairs
	var cookie_associative_array = {};

	// The browser cookies string
	document.cookie.
		// As an array with values of the cookie string divided by a semicolon
		split(';').
		// For each of these cookie elements
		forEach(
		// Run an arrow function with the cookie element as an argument
		(cookie_element) =>
		{
			// Initialize a "key" variable with the key of the cookie_element
			// and initialize a "value" variable with the value of the cookie_element
			//		in the cookie_element they are divided using an equal sign
			var [key, value] = cookie_element.split('=');

			// Remove extra spaces form the "key" variable
			// and create a new element in the cookie associative array
			//		with the "key" as the associate key and "value" as the associative value
			cookie_associative_array[key.trim()] = value;
		})

	// Return the cookie value which matches the passed cookie name
  	if(cookie_associative_array[name] != undefined)
  		return cookie_associative_array[name];
  	else
  		return false;
}


// Function that retrieve books information from the server script and print them
function loadBooks() 
{

	// ===================== Fetch filters values from HTML page  =====================
		// Fetch search bar value from HTML page
		var search_bar_content = document.getElementById("search-bar").value;

		// Fetch number of books to show from HTML page
		var num_books_to_show = document.getElementById("num-books").value;
	// ===================== ==================================== =====================


	// ===================== Asynchronous POST connection with PHP script =====================
		// Intialize the connection variable
		var xhttp = new XMLHttpRequest();

		// Whenever the connection changes its state
		xhttp.onreadystatechange = function()
		{
			// If the server is ready to reply
			if (this.readyState == 4 && this.status == 200) 
				// Retrieve the server response and save it in book-page-content HTML element
				document.getElementById("book-page-content").innerHTML = this.responseText;
		};

		// Initialize a virtual form with the filter values
		var filter_options = new FormData();
		filter_options.append("search_keywords", search_bar_content);
		filter_options.append("num_books_per_page", num_books_to_show);

		// Open the asynchronous POST connection with the PHP script
		xhttp.open("POST", "../server_script/books.php", true);

		// Send plain-text filter values to the PHP script
		xhttp.send(filter_options);
	// ===================== =========================================== =====================
}


// Function that retrieve products information from the server script and print them
function loadProducts() 
{

	// ===================== Fetch filters values from HTML page  =====================
		// Fetch search bar value from HTML page
		var search_bar_content = document.getElementById("search-bar").value;

		// Fetch number of products to show from HTML page
		var num_products_to_show = document.getElementById("num-products").value;
	// ===================== ==================================== =====================


	// ===================== Asynchronous POST connection with PHP script =====================
		// Intialize the connection variable
		var xhttp = new XMLHttpRequest();

		// Whenever the connection changes its state
		xhttp.onreadystatechange = function()
		{
			// If the server is ready to reply
			if (this.readyState == 4 && this.status == 200) 
				// Retrieve the server response and save it in products-page-content HTML element
				document.getElementById("products-page-content").innerHTML = this.responseText;
		};

		// Initialize a virtual form with the filter values
		var filter_options = new FormData();
		filter_options.append("search_keywords", search_bar_content);
		filter_options.append("num_products_per_page", num_products_to_show);

		// Open the asynchronous POST connection with the PHP script
		xhttp.open("POST", "../server_script/products.php", true);

		// Send plain-text filter values to the PHP script
		xhttp.send(filter_options);
	// ===================== =========================================== =====================
}


// Each time the page loads
window.onload = (event) => 
{
	// Get the page name
	var this_page = 
		// Get the absolute path of the file
		window.location.pathname.
		// Generate an array in which elements are extracted from the absolute path of the file each time a "/" is encountered
		//		e.g. "dir1/dir2/web/page.html" becomes the array [dir1, dir2, web, page.html]
		split("/").
		// Remove the last element from the array and return it
		//		that's the file name
		pop();

	// Check if the page name is equal to books page name (books.php)
	if(this_page == "books.php")
		// Load the books retrieved from the server
		loadBooks();
	// Check if the page name is equal to products page name (products.php)
	else if(this_page == "products.php")
		// Load the products retrieved from the server
		loadProducts();
};