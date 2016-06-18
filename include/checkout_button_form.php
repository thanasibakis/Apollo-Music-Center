<h4 style="display: inline;">Total cost: $<?php echo total_cart_cost(); ?></h4>
<form id="checkout_button_form" method="post" action = "cart.php">
	<input type="hidden" name="checkout" value="1"></input>
	<input type="submit" value="Check out"></input>
</form>
<br/>
<br/>