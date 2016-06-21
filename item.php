<?php include_once "include/setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/section.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/theme.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php include "include/header.php"; ?>
		<section>
			<?php
				$item = new Item($_GET["id"]);
				create_data_vars($item);
				if($name == '')
				{
					echo "Item not found.";
					exit();
				}
				
				$already_viewed = false;
				
				foreach($_SESSION["recent"] as $viewed)
				{
					if($viewed->get_id() == $id)
					{
						$already_viewed = true;
					}
				}
				
				if(!$already_viewed)
				{
					$_SESSION["recent"][] = $item;
				}
				
				while(count($_SESSION["recent"]) > 5)
				{
					unset($_SESSION["recent"][0]);
					$_SESSION["recent"] = array_values($_SESSION["recent"]);
				}
			?>
			<h3><?php echo $name; ?></h3>
			<img src="<?php echo $image; ?>" alt="Hmmm... this should be <?php echo $name; ?>"/>
			<table>
				<tr>
					<td><?php echo $description; ?></td>
				</tr>
				<tr>
					<td><p></p></td><!-- Add space between description and price -->
				</tr>
				<tr>
					<td>$<?php echo $price; ?></td>
				</tr>
				<tr>
					<td><?php echo $quantity; ?> in stock</td>
				</tr>
				<tr>
					<td>
						<form method="post" action="add_to_cart.php">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<input type="submit" value="Add to Cart"></input>
						</form>
					</td>
				</tr>
			</table>
			<div style="clear:both;"></div> <!-- To force <section> to expand with the floated elements -->
		</section>
	</body>
</html>