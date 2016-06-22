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
					<td><a href="change_password.php">Change Password</a></td>
				</tr>
			</table>
		</section>
		<section>
			<h3>Most Recent Order</h3>
			<div class="scrolled">
				<table class="centered">
					<tr>
						<?php
							$rows = sql_procedure("GetMostRecentOrder", array($_SESSION["user"]["id"]), 'd');
							$order_data = $rows[0]["order_contents"];
							$order = array();
							
							while(strpos($order_data, '{') !== false)
							{
								$pos_start = strpos($order_data, '{');
								$pos_end = strpos($order_data, '}');
								$item_data = substr($order_data, $pos_start + 1, $pos_end - ($pos_start + 1));
								preg_match("/ID=(.+); QUANTITY=(.+)/", $item_data, $match);
								$id = $match[1];
								$order[] = new Item($id);
								$order_data = substr($order_data, $pos_end + 1);
							}
							
							foreach($order as $item)
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
			<?php if(count($order) == 0) { echo "Nothing ordered recently."; } ?>
		</section>
	</body>
</html>