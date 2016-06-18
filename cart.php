<!doctype html>
<?php include_once "setup.php"; ?>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/index.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_cart.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include "header.php"; ?>
		<section>
			<h3>Your Cart (<?php echo count_cart(); ?> Items)</h3>
			<?php
				$cart = array_reverse($_SESSION["cart"]);
				
				if(isset($_POST["checkout"])) // enter checkout mode
				{
					include "customer_info_form.php";
				}
				else // enter cart-view mode
				{
					if(count_cart() > 0) // show option to enter checkoutmode
					{
						echo "<h4 style='display: inline;'>Total cost: $" . total_cart_cost() . "</h4>";
						echo "<form style='display: inline;' method='post' action = 'cart.php'>";
						echo "	<input type='hidden' name='checkout' value='1'></input>";
						echo "	<input type='submit' value='Check out'></input>";
						echo "</form><br/>";
					} 
					
					foreach($cart as $item)
					{
						create_data_vars($item);
						include "item_cart.php";
					}
					
					if(count($cart) == 0)
					{
						echo "Your cart is empty!";
					}
				}
			?>
		</section>
	</body>
</html>