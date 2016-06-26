<?php

/* include this file before doctype to ensure proper session functionality */

include_once "credentials.php";

if(defined(DEBUG_MODE) && DEBUG_MODE == 1)
{
	error_reporting(-1);
	ini_set('display_errors', 'On');
}

function html_print_r($v, $n = '', $ret = false)
{
	if(defined(DEBUG_MODE) && DEBUG_MODE != 1)
	{
		return;
	}	
	if($ret)
	{
		ob_start();
	}	
	echo $n.'<pre>';
	print_r($v);
	echo '</pre>'."\n";
	if($ret)
	{
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
}

if(!isset($_SESSION))
{
	session_start();
}

if(!isset($_SESSION["recent"]))
{
	$_SESSION["recent"] = array();
}

function refValues($arr)
{
	/* Creates an array of references for call_user_func_array to call mysqli_stmt_bind_param in sql_procedure */
	$refs = array();
	foreach($arr as $key => $value)
		$refs[$key] = &$arr[$key];
	return $refs;
}

function sql_procedure($procedure, $args=array(), $types='', $should_log=true)
{
	// log call
	$do_not_log_procedures = array("LogSQL", "GetCategories", "GetColumnByID", "GetFeatured", "GetIDbyCategory", "GetIDbyName", "GetMostRecentOrder", "GetOrderNumber", "GetSalt", "GetUserCart", "GetUserID");
	
	foreach($do_not_log_procedures as $proc)
	{
		if($procedure == $proc)
		{
			$should_log = false;
		}
	}
	
	if($should_log)
	{
		sql_procedure("LogSQL", array($procedure, json_encode($args)), "ss");
	}
	
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
	
	// return result, or a blank array if there is none
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
	
	$name = htmlentities($item_object->get_name());
	$price = htmlentities($item_object->get_price_each());
	$description = htmlentities($item_object->get_description());
	$image = htmlentities($item_object->get_image_location());
	$quantity = htmlentities($item_object->get_quantity_available());
	$id = htmlentities($item_object->get_id());
	$quantity_in_cart = htmlentities($item_object->get_quantity_in_cart());
	$total_price = htmlentities($item_object->get_total_price());
}

class Item
{
	private $id;
	private $quantity_in_cart;
	
	function __construct($id)
	{
		$this->id = (int)$id;
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
	
	function to_json_array()
	{
		$item_data = array(
			"name" => $this->get_name(),
			"price" => $this->get_price_each(),
			"description" => $this->get_description(),
			"image" => $this->get_image_location(),
			"quantity_available" => $this->get_quantity_available(),
			"id" => $this->get_id()
		);
		
		return $item_data;
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

function add_to_session_cart($id)
{
	$item = new Item($id);
	$index = get_index_of_item_in_cart($item);
		
	if($index == -1)
	{
		$_SESSION["cart"][] = $item;
		$item->update_quantity_in_cart(1);
	} else
	{
		$item = $_SESSION["cart"][$index];
		if($item->get_quantity_in_cart() < $item->get_quantity_available())
		{
			$item->update_quantity_in_cart(1);
		}
	}
}

function remove_from_session_cart($id)
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

function count_cart()
{
	$count = 0;
	foreach($_SESSION["cart"] as $item)
	{
		$count += $item->get_quantity_in_cart();
	}
	return $count;
}

function cart_to_json()
{
	$json_data = array();
	$cart = array();
	foreach($_SESSION["cart"] as $item)
	{
		$item_data = array("quantity" => $item->get_quantity_in_cart(), "name" => $item->get_name(), "id" => $item->get_id());
		$json_data[] = $item_data;
	}
	return json_encode($json_data);
}

function json_to_cart($json)
{
	$json_data = json_decode($json, true);
	
	foreach($json_data as $data)
	{
		$id = $data["id"];
		$quantity = $data["quantity"];
		for($i = 0; $i < $quantity; $i++)
		{
			add_to_session_cart($id);
		}
	}
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