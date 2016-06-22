<form method="post" action="checkout.php">
	<table class="card centered" id="customer_info_form">
		<tr>
			<td>Name:</td>
			<td><input type="text" name="first_name" id="name" placeholder="First" required></input></td>
			<td><input type="text" name="last_name" placeholder="Last" required></input></td>
		</tr>
		<tr>
			<td>Address:</td>
			<td><input type="text" name="street" id="address" placeholder="Number/Street" required></input></td>
			<td><input type="text" name="city" placeholder="City" required></input></td>
			<td><select name="state">
		<option value="AL">Alabama</option>
		<option value="AK">Alaska</option>
		<option value="AZ">Arizona</option>
		<option value="AR">Arkansas</option>
		<option value="CA">California</option>
		<option value="CO">Colorado</option>
		<option value="CT">Connecticut</option>
		<option value="DE">Delaware</option>
		<option value="DC">District Of Columbia</option>
		<option value="FL">Florida</option>
		<option value="GA">Georgia</option>
		<option value="HI">Hawaii</option>
		<option value="ID">Idaho</option>
		<option value="IL">Illinois</option>
		<option value="IN">Indiana</option>
		<option value="IA">Iowa</option>
		<option value="KS">Kansas</option>
		<option value="KY">Kentucky</option>
		<option value="LA">Louisiana</option>
		<option value="ME">Maine</option>
		<option value="MD">Maryland</option>
		<option value="MA">Massachusetts</option>
		<option value="MI">Michigan</option>
		<option value="MN">Minnesota</option>
		<option value="MS">Mississippi</option>
		<option value="MO">Missouri</option>
		<option value="MT">Montana</option>
		<option value="NE">Nebraska</option>
		<option value="NV">Nevada</option>
		<option value="NH">New Hampshire</option>
		<option value="NJ">New Jersey</option>
		<option value="NM">New Mexico</option>
		<option value="NY">New York</option>
		<option value="NC">North Carolina</option>
		<option value="ND">North Dakota</option>
		<option value="OH">Ohio</option>
		<option value="OK">Oklahoma</option>
		<option value="OR">Oregon</option>
		<option value="PA">Pennsylvania</option>
		<option value="RI">Rhode Island</option>
		<option value="SC">South Carolina</option>
		<option value="SD">South Dakota</option>
		<option value="TN">Tennessee</option>
		<option value="TX">Texas</option>
		<option value="UT">Utah</option>
		<option value="VT">Vermont</option>
		<option value="VA">Virginia</option>
		<option value="WA">Washington</option>
		<option value="WV">West Virginia</option>
		<option value="WI">Wisconsin</option>
		<option value="WY">Wyoming</option>
	</select></td>
		</tr>
		<tr>
			<td>Credit Card:</td>
			<td><input type="text" name="card_number" id="credit_card_number" placeholder="Number" required></input></td>
			<td>Exp. <input type="date" name="card_exp_date" id="credit_card_date" required></input></td>
		</tr>
		<tr>
			<td colspan="4"><input type="submit" value="Order Now"></input></td>
		</tr>
	</table>
</form>