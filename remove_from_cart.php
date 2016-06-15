<?php
include_once "setup.php";


if(isset($_POST["id"]))
{
	$count = count($_SESSION["cart"]);
	for($i = 0; $i < $count; $i++)
	{
		if($_SESSION["cart"][$i]->get_id() == $_POST["id"])
		{
			unset($_SESSION["cart"][$i]);
			break;
		}
	}
}
header("Location: cart.php");
?>