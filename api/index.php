<?php
	include_once "../credentials.php";
	include_once "../include/setup.php";
	
	define("HTTP_VER", "HTTP/1.1");
	define("HTTP_OK", HTTP_VER . " 200 OK");
	
	/**
	 * Decodes $json_data.
	 * Check if $json_data has elements that satisfy $params.
	 * If applicable, replaces username and password with corresponding user_id.
	 *
	 * @param	string	$json_data	input to process
	 * @param	array 	$params		required elements for $json_data
	 * @return	array 	[HTTP response, decoded $json_data]
	 */
	function process_input($json_data, $params)
	{
		$data = json_decode($json_data, true);

		foreach($params as $param)
		{
			if(!isset($data[$param]))
			{
				return array("HTTP/1.0 400 INVALID INPUT");
			}
		}
		
		if(isset($data["username"]))
		{
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
		}
		
		return array(HTTP_OK, $data);
	}
	
	/**
	 * Sets an error in an HTTP header.
	 * Extracts the message from the error and returns that message in JSON format.
	 *
	 * @param	string	$response	error to set (in format "HTTP/X.X XXX MESSAGE")
	 * @return	string 	JSON form of error message
	 */
	function fail($response)
	{
		header($response);
		return json_encode(array("response" => substr(strtolower($response), strlen(HTTP_VER . " XXX "))));
	}
	
	/**
	 * Returns information on an item of a given ID.
	 *
	 * @param	string	$id		item ID
	 * @return	string 	JSON form of item information
	 */
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
	
	/**
	 * Returns information on items of a given category.
	 *
	 * @param	string	$category	item category
	 * @return	string 	JSON form of item information
	 */
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
	
	/**
	 * Returns information on featured items.
	 *
	 * @return	string 	JSON form of item information
	 */
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
	
	/**
	 * Returns information on items whose names are similar to a given search term.
	 *
	 * @param	string	$name	search term
	 * @return	string 	JSON form of item information
	 */
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
	
	/**
	 * Processes a transaction and returns the order number.
	 *
	 * @param	string	$json_data		transaction information
	 * @return	string 	JSON form of order number
	 */
	function api_add_transaction($json_data)
	{
		$params = array("username", "password", "first_name", "last_name", "street", "city", "state", "card_number", "card_exp_date");
		$result = process_input($json_data, $params);
		
		if($result[0] != HTTP_OK)
		{
			return fail($result[0]);
		}
		
		$data = $result[1];
		$json_temp_array = json_decode($json_data, true);
		$username = $json_temp_array["username"];
		$password = $json_temp_array["password"];
		$data["cost"] = ""; // add before adding cart to array
		$data["cart"] = api_get_cart(json_encode(array("username" => $username, "password" => $password)));
		
		$cart_temp_arr = json_decode($data["cart"], true);
		$total_price = 0;
		foreach($cart_temp_arr as $item_array)
		{
			$id = $item_array["id"];
			$item = new Item($id);
			$total_price += $item->get_price_each() * $item_array["quantity"];
			sql_procedure("UpdateQuantity", array($id, $item->get_quantity_available() - $item_array["quantity"]), "dd");
		}
		$data["cost"] = $total_price;
		
		sql_procedure("AddTransaction", $data, "isssssssds");
		$row = sql_procedure("GetOrderNumber", array($data["cart"], $data["card_number"]), "ss");
		api_set_cart(json_encode(array("username" => $username, "password" => $password, "cart" => "[]")));
		
		$order_number = $row[0]["order_number"];
		return json_encode(array("order_number" => $order_number));
	}
	
	/**
	 * Updates a given user's cart in the database.
	 *
	 * @param	string	$json_data		user information
	 */
	function api_set_cart($json_data)
	{
		$params = array("username", "password", "cart");
		$result = process_input($json_data, $params);
		
		if($result[0] != HTTP_OK)
		{
			return fail($result[0]);
		}
		
		$data = $result[1];
		
		$cart = array();
		foreach($data["cart"] as $item_array)
		{
			$id = $item_array["id"];
			$item = new Item($id);
			$item_data = array("quantity" => $item_array["quantity"], "name" => $item->get_name(), "id" => $item->get_id());
			$cart[] = $item_data;
		}
		$data["cart"] = json_encode($cart);
		
		sql_procedure("SetUserCart", $data, "is");
	}
	
	/**
	 * Retrieves a given user's cart from the database.
	 *
	 * @param	string	$json_data		user information
	 * @return	string 	JSON form of cart
	 */
	function api_get_cart($json_data)
	{
		$params = array("username", "password");
		$result = process_input($json_data, $params);
		
		if($result[0] != HTTP_OK)
		{
			return fail($result[0]);
		}
		
		$data = $result[1];
		$rows = sql_procedure("GetUserCart", $data, 'i');
		$cart_json = $rows[0]["cart"];
		
		return $cart_json;
	}
	
	/**
	 * Checks to see if a given username and password pair is valid.
	 *
	 * @param	string	$json_data		login credentials
	 * @return	string 	JSON form of true or false response
	 */
	function api_validate_login($json_data)
	{
		$result = process_input($json_data, array("username", "password"));
		switch($result[0])
		{
			case HTTP_OK:
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

	/************* Begin Main *************/

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