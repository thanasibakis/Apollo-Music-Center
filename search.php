<!doctype html>
<?php include_once "setup.php" ?>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/index.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include 'header.php' ?>
		<section>
			<h3>Results for "<?php echo $_GET["name"]; ?>"</h3>
			<?php
				$rows = sql("select id from items where name like '%" . $_GET["name"] . "%'");
				foreach($rows as $row)
				{
					$id = $row["id"];
					$item = new Item($id);
					$name = $item->get_name();
					$price = $item->get_price_each();
					$description = $item->get_description();
					$image = $item->get_image_location();
					$quantity = $item->get_quantity_available();
					
					include "item_small.php";
				}
				if(count($rows) == 0)
				{
					echo "No results.";
				}
			?>
		</section>
	</body>
</html>