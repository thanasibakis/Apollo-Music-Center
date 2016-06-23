<?php
	include_once "include/setup.php"; 
	
	if(isset($_SESSION["user"]))
	{
		unset($_SESSION["user"]);
		unset($_SESSION["cart"]); // could theoretically save the cart to a database here for next time...
		session_regenerate_id(); // stays after above!
		header("Location: index.php");
		exit();
	}
?>