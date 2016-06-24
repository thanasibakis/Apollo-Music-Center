<?php
	include_once "include/setup.php";
	
	$action = htmlentities($_POST["action"]);
	
	if($action == "login")
	{	
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$rows = sql_procedure("GetSalt", array($username), 's');
		$salt = $rows[0]["salt"];
		$password = crypt($password, $salt);
		
		$rows = sql_procedure("CheckLoginCredentials", array($username, $password), "ss");
		$login_correct = $rows[0]["result"];
		if($login_correct)
		{
			$rows = sql_procedure("GetUserID", array($username), 's');
			$user_id = $rows[0]["user_id"];
			session_regenerate_id(); // put before setting session user data below!
			$_SESSION["user"] = array("name" => $username, "id" => $user_id);
			$_SESSION["cart"] = array();
			
			$rows = sql_procedure("GetUserCart", array($user_id), 'i');
			$cart_json = $rows[0]["cart"];
			json_to_cart($cart_json);
			
			header("Location: cart.php");
			exit();
		} else
		{
			header("Location: login.php?message=Incorrect credentials.&username=$username");
			exit();
		}
	} elseif($action == "sign up")
	{
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$rows = sql_procedure("DoesUserExist", array($username), 's');
		$exists = $rows[0]["result"];
		
		if($exists)
		{
			header("Location: login.php?message=Username taken.&username=$username");
			exit();
		} else
		{
			$salt = uniqid();
			$password = crypt($password, $salt);
			sql_procedure("AddUser", array($username, $password, $salt), "sss");
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
		$old_password = $_POST["old_password"];
		$new_password = $_POST["new_password"];
		
		$rows = sql_procedure("GetSalt", array($username), 's');
		$salt = $rows[0]["salt"];
		$old_password = crypt($old_password, $salt);
		$new_password = crypt($new_password, $salt);
		
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