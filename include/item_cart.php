<a href="item.php?id=<?php echo $id; ?>">
	<div class="item_cart">
		<h3><?php echo trim(substr($name, 0, 25)); if(strlen($name) > 25){ echo '...'; } ?> (<?php echo $quantity_in_cart; ?>)</h3>
		<img src="<?php echo $image; ?>" alt="Hmmm... this should be <?php echo $name; ?>"/>
		<p>$<?php echo "$price * $quantity_in_cart = $$total_price"; ?></p>
		<table>
			<tr>
				<td>
					<form method="post" action="add_to_cart.php">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input type="submit" value="Add One"></input>
					</form>
				</td>
				<td>
					<form method="post" action="remove_from_cart.php">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input type="submit" value="Remove One"></input>
					</form>
				</td>
			</tr>
		</table>
	</div>
</a>