<!doctype html>
<?php include_once "setup.php"; ?>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/item.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/header.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/item_small.css" type="text/css" media="screen"/>
		<title>Store</title>
	</head>
	<body>
		<?php include "header.php"; ?>
		<section>
			<?php
				echo var_dump($_SESSION["recent"]); echo '<br/>';
				$item = new Item($_GET["id"]);
				setup_for_html_include($item);
				
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
					echo var_dump($_SESSION["recent"]);
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
					<td><p><?php echo $description; ?></p></td>
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
		</section>
	</body>
</html>