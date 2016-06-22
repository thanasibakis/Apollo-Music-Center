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
			<h3>Featured</h3>
			<div class="scrolled">
				<table class="centered">
					<tr>
						<?php
							$featured_items = get_featured_items();
							foreach($featured_items as $item)
							{
								create_data_vars($item);
								
								echo "<td>";
								include "include/item_small.php";
								echo "</td>";
							}
						?>
					</tr>
				</table>
			</div>
		</section>
		<section>
			<h3>Recently Viewed</h3>
			<div class="scrolled">
				<table class="centered">
					<tr>
						<?php
							$recent_items = array_reverse($_SESSION["recent"]);
							foreach($recent_items as $item)
							{
								create_data_vars($item);
							
								echo "<td>";
								include "include/item_small.php";
								echo "</td>";
							}
							
							if(count($_SESSION["recent"]) == 0)
							{
								echo "Nothing viewed recently.";
							}
						?>
					</tr>
				</table>
			</div>
		</section>
	</body>
</html>