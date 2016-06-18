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
			<h3>Featured</h3>
			<div class="item_scroll">
				<table>
					<tr>
						<?php
							$featured_items = get_featured_items();
							foreach($featured_items as $item)
							{
								create_data_vars($item);
								
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
							<?php
								$recent_items = array_reverse($_SESSION["recent"]);
								foreach($recent_items as $item)
								{
									create_data_vars($item);
								
									echo "<td>";
									include "item_small.php";
									echo "</td>";
								}
								
								if(count($_SESSION["recent"]) == 0)
								{
									echo "Nothing viewed recently.";
								}
							?>
						</tr>
					</tr>
				</table>
			</div>
		</section>
	</body>
</html>