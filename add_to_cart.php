<?php
	include_once "include/setup.php";
	
	if(isset($_POST["id"]))
	{
		$id = $_POST["id"];
	} elseif(isset($_SESSION["item_id_before_login"]))
	{
		$id = $_SESSION["item_id_before_login"];
		unset($_SESSION["item_id_before_login"]);
	}
	
	if(isset($id))
	{
		add_to_session_cart($id);
		sql_procedure("SetUserCart", array($_SESSION["user"]["id"], cart_to_json()), "is");
	}
	header("Location: cart.php");
	exit();
?>