<!doctype html>
<?php include_once "setup.php"; ?>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/index.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_cart.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include "header.php"; ?>
		<section>
			<h3>Your Cart (<?php echo count_cart(); ?> Items)</h3>
			<?php
				$cart = array_reverse($_SESSION["cart"]);
				
				foreach($cart as $item)
				{
					setup_for_html_include($item);
					include "item_cart.php";
				}
				
				if(count($cart) == 0)
				{
					echo "Your cart is empty!";
				}
			?>
		</section>
	</body>
</html>