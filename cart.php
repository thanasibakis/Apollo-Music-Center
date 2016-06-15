<?php include_once "setup.php" ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/index.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_cart.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include 'header.html' ?>
		<section>
			<h3>Your Cart (<?php echo count($_SESSION["cart"])?> Items)</h3>
			<?php
				$cart = array_reverse($_SESSION["cart"]);
				foreach($cart as $item)
				{
					$name = $item->get_name();
					$price = $item->get_price_each();
					$description = $item->get_description();
					$image = $item->get_image_location();
					$id = $item->get_id();
					
					include "item_cart.php";
				}
			?>
		</section>
	</body>
</html>