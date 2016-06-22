<?php include_once "include/setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php include "include/header.php"; ?>
		<section>
			<?php $category = htmlentities($_GET["category"]); ?>
			<h3><?php echo $category; ?></h3>
			<?php
				$items = get_category_items($category);
				
				echo "<div class='centered'>";
				foreach($items as $item)
				{
					create_data_vars($item);
					include "include/item_small.php";
				}
				echo "</div>";
				
				if(count($items) == 0) { echo "No items found."; }
			?>
		</section>
	</body>
</html>