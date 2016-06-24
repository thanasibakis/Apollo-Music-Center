<?php
	include_once "../credentials.php";
	include_once "../include/setup.php";
	
	define("HTTP_VER", "HTTP/1.1");
	define("HTTP_OK", HTTP_VER . " 200 OK");
	
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
			return array(HTTP_VER . " 403 FORBIDDEN");
		}
		
		$rows = sql_procedure("GetUserID", array($data["username"]), 's');
		$user_id = $rows[0]["user_id"];
		unset($data["username"]);
		unset($data["password"]);
		$data = array("user_id" => $user_id) + $data;
		
		return array("HTTP_OK", $data);
	}
	
	function fail($response)
	{
		header($response);
		return json_encode(array("response" => substr(strtolower($response), strlen("HTTP/X.X XXX "))));
	}
	
	function api_get_item_by_id($id)
	{
		$item = new Item($id);
		if($item->get_name() == null)
		{
			return fail(HTTP_VER . " 404 ITEM NOT FOUND");
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
			return fail(HTTP_VER . " 404 CATEGORY NOT FOUND");
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
			return fail(HTTP_VER . " 404 NO ITEMS FOUND");
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
		
		if($result[0] != "HTTP_OK")
		{
			return fail($result[0]);
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
		
		if($result[0] != "HTTP_OK")
		{
			return fail($result[0]);
		}
		
		$data = $result[1];
		$data["cart"] = json_encode($data["cart"]);
		
		sql_procedure("SetUserCart", $data, "is");
	}
	
	function api_get_cart($json_data)
	{
		$params = array("username", "password");
		$result = validate_input($json_data, $params);
		
		if($result[0] != "HTTP_OK")
		{
			return fail($result[0]);
		}
		
		$data = $result[1];
		$rows = sql_procedure("GetUserCart", $data, 'i');
		$cart_json = $rows[0]["cart"];
		
		return $cart_json;
	}
	
	function api_validate_login($json_data)
	{
		$result = validate_input($json_data, array("username", "password"));
		switch($result[0])
		{
			case "HTTP_OK":
				return json_encode(array("response" => "true"));
				break;
			case HTTP_VER . " 403 FORBIDDEN":
				return json_encode(array("response" => "false"));
				break;
			default:
				return fail($result[0]);
				break;
		}
	}

	header("Content-Type: application/json");
	
	$method = $_SERVER["REQUEST_METHOD"];
	$request = explode('/', trim($_SERVER["REQUEST_URI"], '/'));
	
	if($request[0] != "api")
	{
		exit();
	}
	
	$action = $request[1];
	
	if($action == '')
	{
		header(HTTP_VER . " 303 SEE OTHER");
		header("Location: doc.txt");
		exit();
	}
	
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
				echo fail(HTTP_VER . " 404 NOT FOUND");
				break;
		}
	} elseif($method == "POST")
	{
		switch($action)
		{
			case "transaction":
				$post_data = file_get_contents("php://input");
				echo api_add_transaction($post_data);
				break;
			case "setcart":
				$post_data = file_get_contents("php://input");
				echo api_set_cart($post_data);
				break;
			case "getcart":
				$post_data = file_get_contents("php://input");
				echo api_get_cart($post_data);
				break;
			case "validatelogin":
				$post_data = file_get_contents("php://input");
				echo api_validate_login($post_data);
				break;
			default:
				echo fail(HTTP_VER . " 404 NOT FOUND");
				break;
		}
	}
?>