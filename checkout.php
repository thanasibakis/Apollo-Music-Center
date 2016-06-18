<?php include_once "setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/section.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/theme.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php
			$cart = "";
			foreach($_SESSION["cart"] as $item)
			{
				create_data_vars($item);
				$cart .= "{ID=$id; QUANTITY=$quantity_in_cart; NAME=$name}";
			}
			$first_name = $_POST["first_name"];
			$last_name = $_POST["last_name"];
			$street = $_POST["street"];
			$city = $_POST["city"];
			$card_number = $_POST["card_number"];
			$card_exp_date = $_POST["card_exp_date"];
			$cost = total_cart_cost();
			
			$values = "'$first_name','$last_name','$street','$city','$card_number','$card_exp_date',$cost,'$cart'";
			
			sql("insert into transactions(first_name, last_name, street, city, card_number, card_exp_date, order_cost, order_contents) values($values);");
			$row = sql("select order_number from transactions where order_contents like '$cart' and card_number='$card_number';");
			
			$order_number = $row[0]["order_number"];
			$_SESSION["cart"] = array();
		?>
		<?php include "header.php"; ?>
		<section>
			<h3>Order Complete</h3>
			<h4>Your order number: <?php echo $order_number; ?></h4>
		</section>
	</body>
</html>