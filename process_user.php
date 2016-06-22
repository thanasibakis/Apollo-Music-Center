<?php
	include_once "include/setup.php";
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if($_POST["action"] == "login")
	{	
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
			header("Location: login.php?err_msg=Incorrect credentials.");
			exit();
		}
	} elseif($_POST["action"] == "sign up")
	{
		$rows = sql_procedure("DoesUserExist", array($username), 's');
		$exists = $rows[0]["result"];
		
		if($exists)
		{
			header("Location: login.php?err_msg=Username taken.");
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
	}
?>