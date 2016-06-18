<a href="item.php?id=<?php echo $id; ?>">
	<div class="item_small">
		<h3><?php echo $name; ?></h3>
		<img src="<?php echo $image; ?>" alt="Hmmm... this should be <?php echo $name; ?>"/>
		<p><?php echo substr($description, 0, 30); ?>...</p>
		<table>
			<tr>
				<td>$<?php echo $price; ?></td>
				<td><?php echo $quantity; ?> in stock</td>
			</tr>
		</table>
	</div>
</a>