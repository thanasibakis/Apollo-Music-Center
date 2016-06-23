<?php
	include_once "include/setup.php";
	
	if(isset($_POST["id"]))
	{
		$id = $_POST["id"];
		$index = get_index_of_item_in_cart(new Item($id));
		$_SESSION["cart"][$index]->update_quantity_in_cart(-1);
		if($_SESSION["cart"][$index]->get_quantity_in_cart() == 0)
		{
			unset($_SESSION["cart"][$index]);
			$_SESSION["cart"] = array_values($_SESSION["cart"]); // re-index the array because it does not have dynamic list-style functionality
		}
	}
	
	header("Location: cart.php");
?>