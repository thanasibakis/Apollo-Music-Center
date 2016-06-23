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
	
	function api_get_featured_items()
	{
		$items = get_featured_items();
		$items_data = array();
		
		for($i = 0; $i < count($items); $i++)
		{
			$item = $items[$i];
			$item_data = prepare_item_array_for_json($item);
			$items_data[] = $item_data;
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
	
	function api_add_transaction($json_data)
	{
		$data_obj = json_decode($json_data);
		$data = array();
		foreach ($data_obj as $a => $b)
		{
			$data[trim($a)] = trim($b);
		}
		$params = array("user_id", "first_name", "last_name", "street", "city", "card_number", "card_exp_date", "cost", "cart");
		foreach($params as $param)
		{
			if(!isset($data[$param]))
			{
				header("HTTP/1.0 400 INVALID INPUT");
				exit();
			}
		}
		
		sql_procedure("AddTransaction", $data, "issssssds");
		$row = sql_procedure("GetOrderNumber", array($cart, $card_number), "ss");
		
		$order_number = $row[0]["order_number"];
		return json_encode(array("order_number" => $order_number));
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
			case "featured":
				echo api_get_featured_items();
				break;
			case "search":
				$name = $request[2];
				echo api_search_for_item($name);
				break;
			default:
				echo json_encode(array("error_message" => "Invalid action '$action'."));
				break;
		}
	} elseif($method == "POST")
	{
		switch($action)
		{
			case "transaction":
				$post_data = file_get_contents("php://input");
				api_add_transaction($post_data);
				break;
			default:
				echo json_encode(array("error_message" => "Invalid action '$action'."));
				break;
		}
	}
?>