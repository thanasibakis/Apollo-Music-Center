<?php
	include_once "include/setup.php"; 
	include_once "include/exit_if_not_logged_in.php";
	
	if(!isset($_POST["first_name"]))
	{
		header("Location: index.php");
		exit();
	}
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php
			$cart = "";
			$json_data = array();
			foreach($_SESSION["cart"] as $item)
			{
				create_data_vars($item);
				$item_data = array("quantity" => $quantity_in_cart, "name" => $name);
				$json_data["$id"] = $item_data;
				sql_procedure("UpdateQuantity", array($id, $item->get_quantity_available() - $quantity_in_cart), "dd");
			}
			
			$cart = json_encode($json_data);
			
			$user_id = $_SESSION["user"]["id"];
			$first_name = $_POST["first_name"];
			$last_name = $_POST["last_name"];
			$street = $_POST["street"];
			$city = $_POST["city"];
			$state = $_POST["state"];
			$card_number = $_POST["card_number"];
			$card_exp_date = $_POST["card_exp_date"];
			$cost = total_cart_cost();
			
			sql_procedure("AddTransaction", array($user_id, $first_name, $last_name, $street, $city, $state, $card_number, $card_exp_date, $cost, $cart), "isssssssds");
			$row = sql_procedure("GetOrderNumber", array($cart, $card_number), "ss");
			
			$order_number = $row[0]["order_number"];
			$_SESSION["cart"] = array();
		?>
		<?php include "include/header.php"; ?>
		<section>
			<h3>Order Complete</h3>
			<h4>Your order number: <?php echo $order_number; ?></h4>
		</section>
	</body>
</html>