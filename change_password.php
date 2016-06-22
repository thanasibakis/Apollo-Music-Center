<?php
	include_once "include/setup.php"; 
	include_once "exit_if_not_logged_in.php";
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="stylesheets/position.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="stylesheets/design.css" type="text/css" media="screen"/>
		<title>Apollo Music Center</title>
	</head>
	<body>
		<?php include "include/header.php"; ?>
		<section>
			<h3>Change Password</h3>
			<?php if(isset($_GET["message"])) { echo $_GET["message"]; } ?>
			<form method="post" action="process_user.php">
				<input type="hidden" name="action" value="change password"></input>
				<table class="card centered">
					<tr>
						<td>Old Password:</td>
						<td><input type="password" name="old_password" required></input></td>
					</tr>
					<tr>
						<td>New Password:</td>
						<td><input type="password" name="new_password" required></input></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Change"></input></td>
					</tr>
				</table>
			</form>
		</section>
	</body>
</html>