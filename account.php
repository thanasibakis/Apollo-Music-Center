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
	</head>
	<body>
		<?php include "include/header.php"; ?>
		<section>
			<h3>Actions</h3>
			<table class="card centered">
				<tr>
					<td><a href="logout.php">Log Out</a></td>
				</tr>
				<tr>
					<td><a href="#">Change Password</a></td>
				</tr>
			</table>
		</section>
		<section>
			<h3>Past Orders</h3>
			<div class="centered">
				<table>
					<tr>Coming soon...
						<?php
							// read them
							//foreach($featured_items as $item)
							//{
							//	create_data_vars($item);
							//	
							//	echo "<td>";
							//	include "include/item_small.php";
							//	echo "</td>";
							//}
						?>
					</tr>
				</table>
			</div>
		</section>
	</body>
</html>