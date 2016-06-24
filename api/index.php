<?php
	include_once "../credentials.php";
	include_once "../include/setup.php";
	
	header("Content-Type: application/json");
	
	function validate_input($json_data, $params)
	{
		$data = json_decode($json_data, true);
		foreach($params as $param)
		{
			if(!isset($data[$param]))
			{
				return array("HTTP/1.0 400 INVALID INPUT MISSING $param");
			}
		}
		$rows = sql_procedure("GetSalt", array($data["username"]), 's');
		$salt = $rows[0]["salt"];
		$data["password"] = crypt($data["password"], $salt);
		
		$rows = sql_procedure("CheckLoginCredentials", array($data["username"], $data["password"]), "ss");
		$result = $rows[0]["result"];
		
		if(!$result)
		{
			return array("HTTP/1.0 403 FORBIDDEN");
		}
		
		$rows = sql_procedure("GetUserID", array($data["username"]), 's');
		$user_id = $rows[0]["user_id"];
		unset($data["username"]);
		unset($data["password"]);
		$data = array("user_id" => $user_id) + $data;
		
		return array("200", $data);
	}
	
	function api_get_item_by_id($id)
	{
		$item = new Item($id);
		if($item->get_name() == null)
		{
			$item_data = array("error_message" => "Item with id $id not found.");
		} else
		{
			$item_data = $item->to_json_array();
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
				$item_data = $item->to_json_array();
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
			$item_data = $item->to_json_array();
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
				$item_data = $item->to_json_array();
				$items_data[] = $item_data;
			}
		}
		
		$json = json_encode($items_data);
		return $json;
	}
	
	function api_add_transaction($json_data)
	{
		$params = array("username", "password", "first_name", "last_name", "street", "city", "state", "card_number", "card_exp_date", "cost", "cart");
		$result = validate_input($json_data, $params);
		
		if($result[0] != "200")
		{
			header($result[0]);
			exit();
		}
		
		$data = $result[1];
		$data["cart"] = json_encode($data["cart"]);
		
		sql_procedure("AddTransaction", $data, "isssssssds");
		$row = sql_procedure("GetOrderNumber", array($cart, $card_number), "ss");
		
		$order_number = $row[0]["order_number"];
		return json_encode(array("order_number" => $order_number));
	}
	
	function api_set_cart($json_data)
	{
		$params = array("username", "password", "cart");
		$result = validate_input($json_data, $params);
		
		if($result[0] != "200")
		{
			header($result[0]);
			exit();
		}
		
		$data = $result[1];
		$data["cart"] = json_encode($data["cart"]);
		
		sql_procedure("SetUserCart", $data, "is");
	}
	
	function api_get_cart($json_data)
	{
		$params = array("username", "password");
		$result = validate_input($json_data, $params);
		
		if($result[0] != "200")
		{
			header($result[0]);
			exit();
		}
		
		$data = $result[1];
		$rows = sql_procedure("GetUserCart", $data, 'i');
		$cart_json = $rows[0]["cart"];
		
		return $cart_json;
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
			case "setcart":
				$post_data = file_get_contents("php://input");
				api_set_cart($post_data);
				break;
			case "getcart":
				$post_data = file_get_contents("php://input");
				echo api_get_cart($post_data);
				break;
			default:
				echo json_encode(array("error_message" => "Invalid action '$action'."));
				break;
		}
	}
?>