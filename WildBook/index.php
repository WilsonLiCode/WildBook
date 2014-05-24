<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>WildBook</title>
		<style type="text/css"> 
			.error {color: #FF0000;}
		</style> 
		<link rel="stylesheet" type="text/css" href="unlogged.css">
	</head>
	<body>
		<div class="main" style="text-align:center;">
			<img src="logo.png">
			<div>Welcome to WildBook!</div>
			<a href="register.php" style="color:#0000ff;">Click here to create an account.</a>
			<div class="inner" style="text-align:center;">
				<div style="text-align:center;">Log In:</div>
				
				<?php
				// define variables and set to empty values
				$uname = $password = $errMsg = "";
				$valid = false;
				
				//Connect to DB
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
				$stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ? AND password = ?");
				mysqli_stmt_bind_param ($stmt, 'ss', $uname, $password);

				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
				   if (empty($_POST["uname"])) 
				   {
					 $errMsg = "Username/password is incorrect.";
				   } 
				   else 
				   {
					 $uname = test_input($_POST["uname"]);
					 // check if username only contains letters and numbers
					 if (!preg_match("/^[a-zA-Z0-9]*$/",$uname)) 
					 {
					   $errMsg = "Username/password is incorrect.";
					 }
				   }
				   
				   if (empty($_POST["password"])) 
				   {
					 $errMsg = "Username/password is incorrect.";
				   } 
				   else 
				   {
					 $password = test_input($_POST["password"]);
					 // check if password only contains letters and numbers
					 if (!preg_match("/^[a-zA-Z0-9]*$/",$password)) 
					 {
					   $errMsg = "Username/password is incorrect.";
					 }
				   }
				   
					mysqli_stmt_execute($stmt);
					if(mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0)
					{
						$errMsg = "Username/password is incorrect.";
					}
					else
					{
						$errMsg = "Success";
						$updateTime = mysqli_prepare($conn, "UPDATE user SET lastaccessed = now() WHERE username = ?");
						mysqli_stmt_bind_param ($updateTime, 's', $uname);
						mysqli_stmt_execute($updateTime);
						$_SESSION['uname'] = $uname;
						header("Location: home.php");
					}
				}
				
				function test_input($data) 
				{
				   $data = trim($data);
				   $data = stripslashes($data);
				   $data = htmlspecialchars($data);
				   return $data;
				}
				?>
				<div id="login">
					<form action="index.php" method="post">
						<table>
							<tr>
								<td align="right">Username: </td>
								<td align="left"><input type="text" name="uname" placeholder="Username"></td>
							</tr>
							<tr>
								<td align="right">Password: </td>
								<td align="left"><input type="password" name="password" placeholder="Password"></td>
							</tr>
						</table>
						<input type="submit" value="Login">
						<span class="error"><?php echo $errMsg;?></span><br>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>