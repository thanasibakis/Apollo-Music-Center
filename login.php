<?php
	include_once "include/setup.php"; 
	
	if(isset($_SESSION["user"])) // opposite of exit_if_not_logged_in.php
	{
		header("Location: index.php");
		exit();
	}	
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php
			if(isset($_GET["message"]))
			{
				$message = htmlentities($_GET["message"]);
				$username = htmlentities($_GET["username"]);
			}
			if(isset($_GET["add"]))
			{
				// user clicked on item to add to cart but wasn't logged in; add to cart once they do
				$_SESSION["item_id_before_login"] = $_GET["add"];
			}
		?>
		<?php include "include/header.php"; ?>
		<section>
			<h3>Login</h3>
			<?php if(isset($message)) { echo $message; } ?>
			<form method="post" action="process_user.php">
				<input type="hidden" name="action" value="login"></input>
				<table class="card centered">
					<tr>
						<td>Username:</td>
						<td><input type="text" name="username" value="<?php echo $username; ?>" required></input></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" required></input></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Login"></input></td>
					</tr>
				</table>
			</form>
			
			
			<h3>Sign Up</h3>
			<form method="post" action="process_user.php">
				<input type="hidden" name="action" value="sign up"></input>
				<table class="card centered">
					<tr>
						<td>Username:</td>
						<td><input type="text" name="username" value="<?php echo $username; ?>" required></input></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" required></input></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Sign Up"></input></td>
					</tr>
				</table>
			</form>
		</section>
	</body>
</html>