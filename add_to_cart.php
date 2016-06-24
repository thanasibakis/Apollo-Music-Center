<?php
	include_once "include/setup.php";
	
	if(isset($_POST["id"]))
	{
		$id = $_POST["id"];
	}
	
	if(isset($id))
	{
		add_to_session_cart($id);
		sql_procedure("SetUserCart", array($_SESSION["user"]["id"], cart_to_json()), "is");
	}
	header("Location: cart.php");
	exit();
?>