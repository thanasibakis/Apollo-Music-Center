<!doctype html>
<?php include_once("setup.php") ?>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/index.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include 'header.html' ?>
		<section>
			<h3>Featured</h3>
			<div class="item_scroll">
				<table>
					<tr>
						<?php
							$featured_items = get_featured_items();
							foreach($featured_items as $item)
							{
								$name = $item->get_name();
								$price = $item->get_price_each();
								$description = $item->get_description();
								$image = $item->get_image_location();
								$id = $item->get_id();
								echo "<td>";
								include "item_small.php";
								echo "</td>";
							}
						?>
					</tr>
				</table>
			</div>
		</section>
		<section>
			<h3>Recently Viewed</h3>
			<div class="item_scroll">
				<table>
					<tr>
						<td><?php include 'item_small.php' ?></td>
						<td><?php include 'item_small.php' ?></td>
					</tr>
				</table>
			</div>
		</section>
	</body>
</html>