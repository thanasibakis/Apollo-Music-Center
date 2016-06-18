<?php include_once "setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/section.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/theme.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include "header.php"; ?>
		<section>
			<h3><?php echo $_GET["category"]; ?> Items</h3>
			<?php
				$items = get_category_items($_GET["category"]);
				foreach($items as $item)
				{
					create_data_vars($item);
					include "item_small.php";
				}
				
				if(count($items) == 0)
				{
					echo "No items found.";
				}
			?>
		</section>
	</body>
</html>