<div class="item_small">
	<h3><?php echo $name; ?></h3>
	<img src="<?php echo $image; ?>" alt="Hmmm... this should be <?php echo $name; ?>"/>
	<p><?php echo $description; ?></p>
	<table>
		<tr>
			<td>$<?php echo $price; ?></td>
			<td><?php echo $quantity; ?> in stock</td>
			<td>
				<form method="get" action="item.php">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<input type="submit" value="View"></input>
				</form>
			</td>
		</tr>
	</table>
</div>