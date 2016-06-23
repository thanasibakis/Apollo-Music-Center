<header class="card">
	<table id="top">
		<tr>
			<td><a href="index.php"><h1>Apollo Music Center</h1></a></td>
			<td>
				<form method="get" action="search.php">
					<input type="search" name="name" required></input>
					<input type="submit"></input>
				</form>
			</td>
		</tr>
	</table>
	<table id="navigation">
		<tr>
			<?php
				$rows = sql_procedure("GetCategories");
				foreach($rows as $row)
				{
					$category = $row["category"];
					echo "<td><a href='category.php?category=$category'>$category</a></td>";
				}
				
				if(isset($_SESSION["user"]))
				{
					echo "<td><a href='cart.php'>View Cart</a></td>";
				}
			?>
			<td>
				<?php
					if(isset($_SESSION["user"]))
					{
						echo "<a href='account.php'>" . htmlentities($_SESSION["user"]["name"]) . "'s Account</a>";
					} else
					{
						echo "<a href='login.php'>Log In / Sign Up</a>";
					}
				?>
			</td>
		</tr>
	</table>
</header>