<?php
	// set server vars
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_DATABASE', 'ac_new_leaf');

	// connect to database
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   	// check for connection errors
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}
?>