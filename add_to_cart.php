<?php
include_once "setup.php";

if(isset($_POST["id"]))
{
	$id = $_POST["id"];
	$_SESSION["cart"][] = new Item($id);
}
header("Location: cart.php");
?>