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
	return mysqli_fetch_array($response);
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
		$data = sql("select name from items where id=" . $this->get_id());
		return $data["name"];
	}
	
	function get_price_each()
	{
		$data = sql("select price from items where id=" . $this->get_id());
		return $data["price"];
	}
	
	function get_image_location()
	{
		$data = sql("select image_location from items where id=" . $this->get_id());
		return $data["image_location"];
	}
	
	function get_description()
	{
		$data = sql("select description from items where id=" . $this->get_id());
		return $data["description"];
	}
	
	function get_quantity_available()
	{
		$data = sql("select quantity_available from items where id=" . $this->get_id());
		return $data["quantity_available"];
	}
}

function get_featured_items()
{
	$one = new Item(4);
	$two = new Item(5);
	return array($one, $two);
}

?>