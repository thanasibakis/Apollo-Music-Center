<?php
	include_once "include/setup.php";
	
	if($_POST["action"] == "login")
	{	
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$rows = sql_procedure("CheckLoginCredentials", array($username, $password), "ss");
		$login_correct = $rows[0]["result"];
		if($login_correct)
		{
			$rows = sql_procedure("GetUserID", array($username), 's');
			$user_id = $rows[0]["user_id"];
			$_SESSION["user"] = array("name" => $username, "id" => $user_id);
			header("Location: cart.php");
			exit();
		} else
		{
			header("Location: login.php?message=Incorrect credentials.");
			exit();
		}
	} elseif($_POST["action"] == "sign up")
	{
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$rows = sql_procedure("DoesUserExist", array($username), 's');
		$exists = $rows[0]["result"];
		
		if($exists)
		{
			header("Location: login.php?message=Username taken.");
			exit();
		} else
		{
			sql_procedure("AddUser", array($username, $password), "ss");
			$rows = sql_procedure("GetUserID", array($username), 's');
			$user_id = $rows[0]["user_id"];
			$_SESSION["user"] = array("name" => $username, "id" => $user_id);
			header("Location: cart.php");
			exit();
		}
	} elseif($_POST["action"] == "change password")
	{
		$username = $_SESSION["user"]["name"];
		$old_password = $_POST["old_password"];
		$new_password = $_POST["new_password"];
		
		sql_procedure("ChangePassword", array($username, $old_password, $new_password), "sss");
		
		$rows = sql_procedure("CheckLoginCredentials", array($username, $new_password), "ss");
		$login_correct = $rows[0]["result"];
		
		if($login_correct)
		{
			header("Location: change_password.php?message=Password changed.");
			exit();
		} else
		{
			header("Location: change_password.php?message=Failed. Please try again.");
			exit();
		}
	}
?>