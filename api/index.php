<?php
	include_once "../credentials.php";
	include_once "../include/setup.php";
	
	header("Content-Type: application/json");
	
	function prepare_item_array_for_json($item)
	{
		global $name;
		global $price;
		global $description;
		global $image;
		global $quantity;
		global $id;
		create_data_vars($item);
		$item_data = array(
			"name" => $name,
			"price" => $price,
			"description" => $description,
			"image" => $image,
			"quantity_available" => $quantity,
			"id" => $id,
		);
		
		return $item_data;
	}
	
	function api_get_item_by_id($id)
	{
		$item = new Item($id);
		if($item->get_name() == null)
		{
			$item_data = array("error_message" => "Item with id $id not found.");
		} else
		{
			$item_data = prepare_item_array_for_json($item);
		}
		
		$json = json_encode($item_data);
		return $json;
	}
	
	function api_get_category_items($category)
	{
		$items = get_category_items($category);
		$items_data = array();
		
		if(count($items) == 0)
		{
			$items_data = array("error_message" => "No items found for category '$category'.");
		} else
		{
			for($i = 0; $i < count($items); $i++)
			{
				$item = $items[$i];
				$item_data = prepare_item_array_for_json($item);
				$items_data[] = $item_data;
			}
		}
		
		$json = json_encode($items_data);
		return $json;
	}
	
	function api_search_for_item($name)
	{
		$items_data = array();
		$rows = sql_procedure("GetIDbyName", array($name), 's');
		
		if(count($rows) == 0)
		{
			$items_data = array("error_message" => "No items found for search term '$name'.");
		} else
		{
			foreach($rows as $row)
			{
				$id = $row["id"];
				$item = new Item($id);
				$item_data = prepare_item_array_for_json($item);
				$items_data[] = $item_data;
			}
		}
		
		$json = json_encode($items_data);
		return $json;
	}

	$method = $_SERVER["REQUEST_METHOD"];
	$request = explode('/', trim($_SERVER["REQUEST_URI"], '/'));
	
	if($request[0] != "api")
	{
		exit();
	}
	
	$action = $request[1];
	
	if($method == "GET")
	{
		switch($action)
		{
			case "item":
				$id = $request[2];
				echo api_get_item_by_id($id);
				break;
			case "category":
				$category = $request[2];
				echo api_get_category_items($category);
				break;
			case "search":
				$name = $request[2];
				echo api_search_for_item($name);
				break;
			default:
				$json_data = array("error_message" => "No items found for category '$category'.");
				$json = json_encode($json_data);
				echo $json;
				break;
		}
	}
?>