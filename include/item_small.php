<a href="item.php?id=<?php echo $id; ?>">
	<div class="item_small">
		<h3><?php echo trim(substr($name, 0, 30)); if(strlen($name) > 30){ echo '...'; } ?></h3>
		<img src="<?php echo $image; ?>" alt="Hmmm... this should be <?php echo $name; ?>"/>
		<p><?php echo trim(substr($description, 0, 35)); if(strlen($description) > 35){ echo '...'; } ?></p>
		<table>
			<tr>
				<td>$<?php echo $price; ?></td>
				<td><?php echo $quantity; ?> in stock</td>
			</tr>
		</table>
	</div>
</a>