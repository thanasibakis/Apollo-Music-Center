<?php

/* include this file before doctype to ensure proper session functionality */

include_once "credentials.php";

if($_GET["debug"] == 1)
{
	error_reporting(-1);
	ini_set('display_errors', 'On');
}

if(!isset($_SESSION))
{
	session_start();
}

if(!isset($_SESSION["recent"]))
{
	$_SESSION["recent"] = array();
}

if(!isset($_SESSION["cart"]))
{
	$_SESSION["cart"] = array();
}

function sql($command)
{
	global $host;
	global $user;
	global $password;
	global $db;
	global $port;
	
	$link = mysqli_init();
	$success = mysqli_real_connect(
	   $link, 
	   $host, 
	   $user, 
	   $password, 
	   $db,
	   $port
	);
	
	$response = mysqli_query($link, $command) or die(mysqli_error($link));
	mysqli_close($link);
	if(gettype($response) == "boolean")
	{
		return array();
	}
	return mysqli_fetch_all($response, MYSQLI_ASSOC);
}

function create_data_vars($item_object)
{
	/* creates global variables for every entry for the html items to access */
	global $name;
	global $price;
	global $description;
	global $image;
	global $quantity;
	global $id;
	global $quantity_in_cart;
	global $total_price;
	
	$name = $item_object->get_name();
	$price = $item_object->get_price_each();
	$description = $item_object->get_description();
	$image = $item_object->get_image_location();
	$quantity = $item_object->get_quantity_available();
	$id = $item_object->get_id();
	$quantity_in_cart = $item_object->get_quantity_in_cart();
	$total_price = $item_object->get_total_price();
}

class Item
{
	private $id;
	private $quantity_in_cart;
	
	function __construct($id)
	{
		$this->id = $id;
		$this->quantity_in_cart = 0;
	}
	
	function get_id()
	{
		return $this->id;
	}
	
	function get_name()
	{
		$rows = sql("select name from items where id=" . $this->get_id() . ';');
		return $rows[0]["name"];
	}
	
	function get_price_each()
	{
		$rows = sql("select price from items where id=" . $this->get_id() . ';');
		$price = $rows[0]["price"];
		return number_format($price, 2, '.', '');
	}
	
	function get_total_price()
	{
		$total = $this->get_price_each() * $this->get_quantity_in_cart();
		return number_format($total, 2, '.', '');
	}
	
	function get_image_location()
	{
		$rows = sql("select image_location from items where id=" . $this->get_id() . ';');
		return $rows[0]["image_location"];
	}
	
	function get_description()
	{
		$rows = sql("select description from items where id=" . $this->get_id() . ';');
		return $rows[0]["description"];
	}
	
	function get_quantity_available()
	{
		$rows = sql("select quantity_available from items where id=" . $this->get_id() . ';');
		return $rows[0]["quantity_available"];
	}
	
	function get_quantity_in_cart()
	{
		return $this->quantity_in_cart;
	}
	
	function update_quantity_in_cart($change)
	{
		$this->quantity_in_cart += $change;
		if($this->quantity_in_cart < 0)
		{
			$this->quantity_in_cart = 0;
		}
		return $this->get_quantity_in_cart();
	}
}

function get_index_of_item_in_cart($item)
{
	$count = count($_SESSION["cart"]);
	for($i = 0; $i < $count; $i++)
	{
		if(($_SESSION["cart"])[$i]->get_id() == $item->get_id())
		{
			return $i;
		}
	}
	return -1;
}

function count_cart()
{
	$count = 0;
	foreach($_SESSION["cart"] as $item)
	{
		$count += $item->get_quantity_in_cart();
	}
	return $count;
}

function total_cart_cost()
{
	$total = 0;
	foreach($_SESSION["cart"] as $item)
	{
		$total += $item->get_total_price();
	}
	return number_format($total, 2, '.', '');;
}

function get_featured_items()
{
	$featured = array();
	$rows = sql("select id from items where featured=true");
	foreach($rows as $row)
	{
		$id = $row["id"];
		$item = new Item($id);
		$featured[] = $item;
	}
	return $featured;
}

function get_category_items($category)
{
	$items = array();
	$rows = sql("select id from items where category='$category'");
	foreach($rows as $row)
	{
		$id = $row["id"];
		$item = new Item($id);
		$items[] = $item;
	}
	return $items;
}

?>