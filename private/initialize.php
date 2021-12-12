<?php


	
  	define("PRIVATE_PATH", dirname(__FILE__));
  	define("PROJECT_PATH", dirname(PRIVATE_PATH));
 	define("PUBLIC_PATH", PROJECT_PATH . '/public');
  	define("SHARED_PATH", PRIVATE_PATH . '/shared');

  	$public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
  	$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  	define("WWW_ROOT", $doc_root);

  	// Change this to your connection info.
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = 'root';
	$DATABASE_NAME = 'same_database2';
	// Try and connect using the info above.
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno()) {
		// If there is an error with the connection, stop the script and display the error.
		exit('Failed to connect to MySQL: ' . mysqli_connect_error());
	}

	require_once('functions.php');
	require_once('query_functions.php');

?>