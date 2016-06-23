<?php
	include_once "include/setup.php";
	
	$action = htmlentities($_POST["action"]);
	
	if($action == "login")
	{	
		$username = htmlentities($_POST["username"]);
		$password = htmlentities($_POST["password"]);
		
		$rows = sql_procedure("CheckLoginCredentials", array($username, $password), "ss");
		$login_correct = $rows[0]["result"];
		if($login_correct)
		{
			$rows = sql_procedure("GetUserID", array($username), 's');
			$user_id = $rows[0]["user_id"];
			session_regenerate_id(); // put before setting session user data below!
			$_SESSION["user"] = array("name" => $username, "id" => $user_id);
			$_SESSION["cart"] = array(); // could theoretically load last time's cart from database here
			header("Location: cart.php");
			exit();
		} else
		{
			header("Location: login.php?message=Incorrect credentials.&username=$username");
			exit();
		}
	} elseif($action == "sign up")
	{
		$username = htmlentities($_POST["username"]);
		$password = htmlentities($_POST["password"]);
		
		$rows = sql_procedure("DoesUserExist", array($username), 's');
		$exists = $rows[0]["result"];
		
		if($exists)
		{
			header("Location: login.php?message=Username taken.&username=$username");
			exit();
		} else
		{
			sql_procedure("AddUser", array($username, $password), "ss");
			$rows = sql_procedure("GetUserID", array($username), 's');
			$user_id = $rows[0]["user_id"];
			session_regenerate_id(); // put before setting session user data below!
			$_SESSION["user"] = array("name" => $username, "id" => $user_id);
			$_SESSION["cart"] = array();
			header("Location: cart.php");
			exit();
		}
	} elseif($action == "change password")
	{
		$username = $_SESSION["user"]["name"];
		$old_password = htmlentities($_POST["old_password"]);
		$new_password = htmlentities($_POST["new_password"]);
		
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