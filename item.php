<?php include_once "include/setup.php"; ?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
		
		<style>
			section img
			{
				width: 450px;
				min-height: 450px;
				float: left;
			}
        	
			section table
			{
				width: 400px;
				float: right;
			}
		</style>
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
			<img class="card" src="<?php echo $image; ?>" alt="Hmmm... this should be <?php echo $name; ?>"/>
			<table class="card">
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
						<?php
							if(isset($_SESSION["user"]))
							{
								include "include/add_to_cart_form.php";
							} else
							{
								echo "To add this item to your cart, please <a href='login.php' style='color:#e6ac00'>log in</a>.";
							}
						?>
					</td>
				</tr>
			</table>
			<div style="clear:both;"></div> <!-- To force <section> to expand with the floated elements -->
		</section>
	</body>
</html>