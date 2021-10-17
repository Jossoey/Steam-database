<?php
/*
* ╔═════════════════════╗
* ║       Config        ║
* ╚═════════════════════╝
* Should include all database connection information
*/

	define('DB_USERNAME', 'ora_katkp');
	define('DB_PASSWORD', 'a61163531');
	define('DB_ADDRESS', 'dbhost.students.cs.ubc.ca:1522/stu');
	
//	$db_id = dbConnect(DB_USERNAME, DB_PASSWORD, DB_ADDRESS);


/*
* ╔═════════════════╗
* ║ Functions Begin ║
* ╚═════════════════╝
* Functions within Config.php are essential functions required in almost every file
*/

/*
* dbConnect :
*
* Connects to database function. Copied from Main.php (Should be identical)
*
* @param string $arg_1 Username
* @param string $arg_2 Password
* @param string $arg_3 Database Address
* @return resource $cid Connection identifier or false on error
*
*/
	function dbConnect($arg_1, $arg_2, $arg_3)
	{
		// Initialise default values for database login.
		$dbUsername = "Username";
		$dbPassword = "Password";
		$dbAddress = "dbhost.students.cs.ubc.ca:1522/stu";

		// If arguments passed, use those instead.
		if (isset($arg_1)) {
			$dbUsername = $arg_1;
		}
		if (isset($arg_2)) {
			$dbPassword = $arg_2;
		}
		if (isset($arg_3)) {
			$dbAddress = $arg_3;
		}

		// Try to connect to the database using credentials above. Return the connection identifier.
		try {
			// echo "Attempting database connection...\n";
			// We use oci_pconnect for a persistent connection. OCILogon is deprecated, so we avoid it.
			if ($cid = oci_pconnect($dbUsername, $dbPassword, $dbAddress)) {
				// echo "Connected successfully.\n <br>";
			} else {
				$cerror = oci_error();
				// echo "Something went wrong. Check connection credentials and try again.\n\nError message: " . $cerror['message'] . "\n <br>";
			}
			// We return the connection identifier regardless of successful connection - it will just be 'false' if it failed.
			return $cid;
		}
		// Exit on any exception just in case. Better safe than sorry, eh?
		catch (exception $e) {
			echo $e->getMessage();
			exit();
		}
	}

/*
* dbDisconnect :
*
* Disconnects from database function.
*
* @param resource $arg_1 Connection Identifier
* @return boolean True on success, False on failure
*
*/
	function dbDisconnect($arg_1)
	{
		if (!isset($arg_1)) {
			// echo "A connection identifier is needed to disconnect.\n";
			return false;
		}
		return oci_close($arg_1);
	}


?>
