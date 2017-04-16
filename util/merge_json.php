<?php
	
	$pretty_print = 0;
	if (isset($_REQUEST['pretty_print'])) {
		$pretty_print = $_REQUEST['pretty_print'];
	}


	$exhibition_types = ['fish', 'bugs', 'deep_sea_creatures'];


	$json = array();
	foreach ($exhibition_types as $type) {
		$json_location = "../data/{$type}.json";
		$file = fopen($json_location, 'r') or die("Unable to open file to read JSON.");
		$json_data = json_decode(fread($file, filesize($json_location)), true);
		fclose($file);

		$json[$type] = $json_data;
	}


	header('Content-Type: application/json');
	echo $json_string = ($pretty_print ? json_encode($json, JSON_PRETTY_PRINT) : json_encode($json));


	// Write the schedule JSON to the data folder
	// $json_file_location = '../data/all.json';
	// $myfile = fopen($json_file_location, 'w') or die("Unable to open file to write to schedule JSON data file!");
	// fwrite($myfile, $json_string);
	// fclose($myfile);

?>