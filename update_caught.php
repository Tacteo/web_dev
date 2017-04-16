<?php
	session_start();

	if (!isset($_SESSION['logged_in_user'])) {
		header("location: login.php");
	} else {
		$username = $_SESSION['logged_in_user'];
	}
	
	// set server variabless
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_DATABASE', 'ac_new_leaf');

	// Function to use for errors. Closes DB and echos error message
	function return_error($message) {
		if ($conn) {
			mysqli_close($conn);
		}
		echo "Error: $message";
		exit();
	}





	// set caught variable
	if (isset($_REQUEST['caught'])) {
		$caught = $_REQUEST['caught'];
	} else {
		return_error('No \'caught\' POST variable.');
	}

	// set number variable
	if (isset($_REQUEST['id_number'])) {
		$number = $_REQUEST['id_number'];
	} else {
		return_error('No \'id_number\' POST variable.');
	} 

	// set table name
	if (isset($_REQUEST['db_table'])) {
		$db_table = $_REQUEST['db_table'];
	} else {
		return_error('No \'db_table\' POST variable.');
	} 



	// connect to database
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   	// check for connection errors
	if (!$conn) {
		return_error(mysqli_connect_error());
	}

	$sql = "UPDATE $db_table SET " . $db_table . '_id_' . "$number=$caught WHERE username='$username'";
	echo $sql;
	$result = mysqli_query($conn, $sql);

	mysqli_close($conn);

	//header("Location: index.php?message=Success&db_request_type=$db_request_type");
?>