<?php
	include_once "include/setup.php";
	
	if(isset($_POST["id"]))
	{
		$id = $_POST["id"];
		$item = new Item($id);
		$index = get_index_of_item_in_cart($item);
		if($index == -1)
		{
			$_SESSION["cart"][] = $item;
			$item->update_quantity_in_cart(1);
		} else {
			$_SESSION["cart"][$index]->update_quantity_in_cart(1);
		}
	}
	header("Location: cart.php");
?>