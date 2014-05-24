<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head> 
		<title>Register</title>
		<style type="text/css"> 
			.error {color: #FF0000;} 
		</style> 
		<link rel="stylesheet" type="text/css" href="unlogged.css">
	</head>
	<body> 
		<div class="main">
			<a href="index.php"><img src="logo.png"></a>
			<div class="inner">
				<h2><b>Registration info:</b></h2>
				<?php
				// define variables and set to empty values
				$unameErr = $passwordErr = $fnameErr = $lnameErr = $dobErr = "";
				$uname = $password = $fname = $lname = $dob = $city = "";
				$valid = true;
				
				//Connect to DB
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
				$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
				mysqli_stmt_bind_param ($unameCheck, 's', $uname);

				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
				   if (empty($_POST["uname"])) 
				   {
					 $unameErr = "Username is required.";
					 $valid = false;
				   } 
				   else 
				   {
					 $uname = test_input($_POST["uname"]);
					 // check if username only contains letters and numbers
					 if (!preg_match("/^[a-zA-Z0-9]*$/",$uname)) 
					 {
					   $unameErr = "Only letters and numbers allowed.";
					   $valid = false;
					 }
					 //Check if username has been taken.
					 else 
					 {
						mysqli_stmt_execute($unameCheck);
						if(mysqli_num_rows(mysqli_stmt_get_result($unameCheck))>0)
						{
							$unameErr = "Username has already been taken.";
							$valid = false;
						}
					 }
				   }
				   
				   if (empty($_POST["password"])) 
				   {
					 $passwordErr = "Password is required.";
					 $valid = false;
				   } 
				   else 
				   {
					 $password = test_input($_POST["password"]);
					 // check if password only contains letters and numbers
					 if (!preg_match("/^[a-zA-Z0-9]*$/",$password)) 
					 {
					   $passwordErr = "Only letters and numbers allowed.";
					   $valid = false;
					 }
				   }
				   
				   if (empty($_POST["fname"])) 
				   {
					 $fnameErr = "First name is required";
					 $valid = false;
				   } 
				   else 
				   {
					 $fname = test_input($_POST["fname"]);
					 // check if first name is valid
					 if (!preg_match("/^[a-zA-Z ]*$/",$fname)) 
					 {
					   $fnameErr = "Only letters and white space allowed."; 
					   $valid = false;
					 }
				   }
					 
				   if (empty($_POST["lname"])) 
				   {
					 $lnameErr = "Last name is required";
					 $valid = false;
				   } 
				   else 
				   {
					 $lname = test_input($_POST["lname"]);
					 // check if last name is valid
					 if (!preg_match("/^[a-zA-Z ]*$/",$lname)) 
					 {
					   $lnameErr = "Only letters and white space allowed"; 
					   $valid = false;
					 }
				   }
				   
					if (empty($_POST["dob"])) 
					{
						$dobErr = "Date of birth is required";
						$valid = false;
					} 
					else 
					{
						$dob = test_input($_POST["dob"]);
					} 
				   
					$city = test_input($_POST["city"]);
				   
					if($valid)
					{
						$stmt = mysqli_prepare($conn, "INSERT INTO user(username, password, fname, lname, city, dob) VALUES (?, ?, ?, ?, ?, ?)");
						mysqli_stmt_bind_param ($stmt, 'ssssss', $uname, $password, $fname, $lname, $city, $dob);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_close($stmt);
						$_SESSION['uname'] = $uname;
						header("Location: createprofile.php");
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

				<p><span class="error">* Required field.</span></p>
				
				<form method="post" action="register.php"> 
				   Username: <br>
				   <input type="text" name="uname" value="<?php echo $uname;?>" placeholder="Username">
				   <span class="error">* <?php echo $unameErr;?></span><br>
				   Password: <br>
				   <input type="password" name="password" placeholder="Password">
				   <span class="error">* <?php echo $passwordErr;?></span><br>
				   First name: <br>
				   <input type="text" name="fname" value="<?php echo $fname;?>" placeholder="First name">
				   <span class="error">* <?php echo $fnameErr;?></span><br>
				   Last name: <br>
				   <input type="text" name="lname" value="<?php echo $lname;?>" placeholder="Last name">
				   <span class="error">* <?php echo $lnameErr;?></span><br>
				   Date of birth: <br>
				   <input type="date" name="dob" value="<?php echo $dob;?>">
				   <span class="error">* <?php echo $dobErr;?></span><br>
				   City: <br>
				   <input type="text" name="city" value="<?php echo $city;?>" placeholder="City"><br>
				   <input type="submit" name="submit" value="Submit"> 
				</form>
			</div>
		</div>
	</body>
</html>