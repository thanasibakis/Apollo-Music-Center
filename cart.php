<?php
	include_once "include/setup.php"; 
	include_once "include/exit_if_not_logged_in.php";
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
		
		<style>
			#customer_info_form
			{
				text-align: right;
			}
			
			#checkout_button_form
			{
				display: inline;
				margin-left: 10px;
			}
		</style>
	</head>
	<body>
		<?php include "include/header.php"; ?>
		<section>
			<h3>Your Cart (<?php echo count_cart(); ?>)</h3>
			<?php
				$cart = array_reverse($_SESSION["cart"]);
				
				if(isset($_POST["checkout"])) // enter checkout mode
				{
					include "include/customer_info_form.php";
				}
				else // enter cart-view mode
				{
					if(count_cart() > 0) // show option to enter checkout mode
					{
						include "include/checkout_button_form.php";
					} 
					
					echo "<div class='centered'>";
					foreach($cart as $item)
					{
						create_data_vars($item);
						include "include/item_cart.php";
					}
					echo "</div>";
					
					if(count($cart) == 0) { echo "Your cart is empty."; }
				}
			?>
		</section>
	</body>
</html>