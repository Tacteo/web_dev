<?php
	//header('Content-Type: application/json');

	// session_start();

	// if (!isset($_SESSION['logged_in_user'])) {
	// 	header("location: login.php");
	// } else {
	// 	$username = $_SESSION['logged_in_user'];
	// }

	// set server variabless
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_DATABASE', 'ac_new_leaf');

	// connect to database
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	$username = 'Tacteo';

	// Function to use for errors. Closes DB and echos error message
	function return_error($message) {
		GLOBAL $conn;
		if ($conn) {
			mysqli_close($conn);
		}
		echo "Error: $message";
		exit();
	}

   	// check for connection errors
	if (!$conn) {
		return_error(mysqli_connect_error());
	}

	if (isset($_REQUEST['db_table'])) {
		$db_table = $_REQUEST['db_table'];
	} else {
		return_error('No db_table POST variable.');
	}



	switch($db_table) {
		case 'fish':
			$columns = ['Number', 'Name', 'Price', 'Shadow', 'Location'];
			break;
		case 'bugs':
			$columns = ['Number', 'Name', 'Price'];
			break;
		case 'deep_sea_creatures':
			$columns = ['Number', 'Name', 'Price', 'Shadow', 'Location'];
			break;
		default:
			return_error("POST Variable db_table=$db_table in not a valid table option.");
	}



	$json_location = "data/{$db_table}.json";
	$file = fopen($json_location, 'r') or die("Unable to open file to read JSON.");
	$json_creature_data = json_decode(fread($file, filesize($json_location)), true);
	fclose($file);



	// create SQL statement string
	$caught_sql = "SELECT * FROM $db_table WHERE username='$username';";  
	// query database with SQL string
	$caught_result = mysqli_query($conn, $caught_sql);
	// get assoc row of caught bits
	$caught_row = mysqli_fetch_assoc($caught_result);

	//print_r($caught_row);


	foreach ($json_creature_data as &$creature_ref) {
		$caught_bit = $caught_row[$db_table . '_id_' . $creature_ref['Number']];
	    $creature_ref['Caught'] = $caught_bit;
	}
	
	$cm = date('n');
	$nm = date('n', strtotime('next month'));

	// these are temp. being used since im playing January in April
	$cm = 1;
	$nm = 2;

	foreach ($json_creature_data as $creature) {
	    if (!$creature['Caught']) {
	    	$current_month = false;
	    	$next_month = false;
			//echo $creature['Name'] . '<br>';
	    	foreach ($creature['Times']['Mainland'] as $month) {
	    		if ($month['month'] == $cm) {
	    			$current_month = true;
	    			echo $creature['Name'] . '<br>';
	    			echo 'Location: ' . $creature['Location'] . '<br>';
	    			echo 'Shadow: ' . $creature['Shadow'] . '<br>';
	    			// echo json_encode($creature) . '<br>';
	    			foreach ($month['spawn'] as $time_slot) {
	    				if ($time_slot['all_day']) {
	    					echo 'All day.<br>';
	    				} else {
	    					echo date("g:ia", strtotime($time_slot['start'] . ':00')) . ' - ' . date("g:ia", strtotime($time_slot['end'] . ':00')) . '<br>';
	    				}
	    			}
	    			echo '<br><br>';
	    		}
	    		if ($month['month'] == $nm) {
	    			$next_month = true;
	    		}
	    	}
	    	
	    	// //echo '<br>';
	    	// if ($current_month) {
	    	// 	//echo json_encode($creature, JSON_PRETTY_PRINT);
	    	// 	//echo $creature['Name'] . '<br>';
	    	// }
	    }
	}

	

	// This function automatically creates a JSON string from the 2d array
	//echo json_encode($json_creature_data, JSON_PRETTY_PRINT);

?>