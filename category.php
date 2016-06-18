<?php include_once "include/setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/section.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/theme.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php include "include/header.php"; ?>
		<section>
			<h3><?php echo $_GET["category"]; ?></h3>
			<?php
				$items = get_category_items($_GET["category"]);
				
				echo "<div class='item_grid'>";
				foreach($items as $item)
				{
					create_data_vars($item);
					include "include/item_small.php";
				}
				echo "</div>";
				
				if(count($items) == 0)
				{
					echo "No items found.";
				}
			?>
		</section>
	</body>
</html>