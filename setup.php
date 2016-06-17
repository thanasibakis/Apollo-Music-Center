<?php

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

if(!isset($_SESSION["cart"]))
{
	$_SESSION["cart"] = array();
}

if(!isset($_SESSION["recent"]))
{
	$_SESSION["recent"] = array();
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
	
	$response = mysqli_query($link, $command);
	mysqli_close($link);
	return mysqli_fetch_all($response, MYSQLI_ASSOC);
}

function setup_for_html_include($item_object)
{
	/* creates global variables for every entry for the html items to access */
	global $name;
	global $price;
	global $description;
	global $image;
	global $quantity;
	global $id;
	
	$name = $item_object->get_name();
	$price = $item_object->get_price_each();
	$description = $item_object->get_description();
	$image = $item_object->get_image_location();
	$quantity = $item_object->get_quantity_available();
	$id = $item_object->get_id();
}

class Item
{
	private $id;
	
	function __construct($id)
	{
		$this->id = $id;
	}
	
	function get_id()
	{
		return $this->id;
	}
	
	function get_name()
	{
		$rows = sql("select name from items where id=" . $this->get_id());
		return $rows[0]["name"];
	}
	
	function get_price_each()
	{
		$rows = sql("select price from items where id=" . $this->get_id());
		return $rows[0]["price"];
	}
	
	function get_image_location()
	{
		$rows = sql("select image_location from items where id=" . $this->get_id());
		return $rows[0]["image_location"];
	}
	
	function get_description()
	{
		$rows = sql("select description from items where id=" . $this->get_id());
		return $rows[0]["description"];
	}
	
	function get_quantity_available()
	{
		$rows = sql("select quantity_available from items where id=" . $this->get_id());
		return $rows[0]["quantity_available"];
	}
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

?>