<?php

/* include this file before doctype to ensure proper session functionality */

include_once "credentials.php";

if(isset($_GET["debug"]))
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

function refValues($arr)
{
	/* Creates an array of references for call_user_func_array to call mysqli_stmt_bind_param in sql_procedure */
	$refs = array();
	foreach($arr as $key => $value)
		$refs[$key] = &$arr[$key];
	return $refs;
}

function sql_procedure($procedure, $args=array(), $types='')
{
	// set up db connection
	$link = mysqli_init();
	$success = mysqli_real_connect(
	   $link, 
	   DBHOST, 
	   DBUSER, 
	   DBPASS, 
	   DB,
	   DBPORT
	);
	
	// create a valid parameterized call statement from the procedure name
	$call = "call $procedure(";
	foreach($args as $arg)
	{
		$call .= "?,";
	}
	$call = rtrim($call, ",") . ");";
	$stmt = mysqli_prepare($link, $call);
	
	// load all data onto args to pass into bind_param
	array_unshift($args, $stmt, $types);
	
	// execute call and close connection
	call_user_func_array("mysqli_stmt_bind_param", refValues($args));
	mysqli_stmt_execute($stmt);
	$response = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($link);
	
	if(gettype($response) == "boolean")
	{
		return array('');
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
		$rows = sql_procedure("GetColumnByID", array("name", $this->get_id()), "ss");
		return $rows[0]["col"];
	}
	
	function get_price_each()
	{
		$rows = sql_procedure("GetColumnByID", array("price", $this->get_id()), "ss");
		$price = $rows[0]["col"];
		return number_format($price, 2, '.', '');
	}
	
	function get_total_price()
	{
		$total = $this->get_price_each() * $this->get_quantity_in_cart();
		return number_format($total, 2, '.', '');
	}
	
	function get_image_location()
	{
		$rows = sql_procedure("GetColumnByID", array("image_location", $this->get_id()), "ss");
		return $rows[0]["col"];
	}
	
	function get_description()
	{
		$rows = sql_procedure("GetColumnByID", array("description", $this->get_id()), "ss");
		return $rows[0]["col"];
	}
	
	function get_quantity_available()
	{
		$rows = sql_procedure("GetColumnByID", array("quantity_available", $this->get_id()), "ss");
		return $rows[0]["col"];
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
	$cart = $_SESSION["cart"];
	$count = count($cart);
	for($i = 0; $i < $count; $i++)
	{
		if($cart[$i]->get_id() == $item->get_id())
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
	return number_format($total, 2, '.', '');
}

function get_featured_items()
{
	$featured = array();
	$rows = sql_procedure("GetFeatured");
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
	$rows = sql_procedure("GetIDbyCategory", array($category), 's');
	foreach($rows as $row)
	{
		$id = $row["id"];
		$item = new Item($id);
		$items[] = $item;
	}
	return $items;
}

?>