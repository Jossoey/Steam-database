<?php
/*
* ╔═════════════════════╗
* ║       Session       ║
* ╚═════════════════════╝
* Should include all essential session information
*/
	require('config.php');

// This will likely be resuming an existing session instead of creating a new one
	session_start();
	
// If no user is currently logged in, redirect page to login (even if they tried to go elsewhere)
/*	if( ! isset($_SESSION['login_user'])) {
		header("Location: login.php");
		exit();
	}
*/
?>
