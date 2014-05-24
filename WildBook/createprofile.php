<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head> 
		<title>Create Profile</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">		
		<style type="text/css"> 
			.error {color: #FF0000;} 
		</style> 
		<script>

		</script>
	</head>
	<body> 
		<div class="main">
			<h2><b>Registration info:</b></h2>
			<?php
			// define variables and set to empty values
			$bioErr = $bio = "";
			$valid = true;
			
			//Connect to DB
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");

			if ($_SERVER["REQUEST_METHOD"] == "POST") 
			{
				if (empty($_POST["bio"])) 
				{
					$bioErr = "Your bio is required.";
					$valid = false;
				} 
				else
				{
					$bio = test_input($_POST["bio"]);
					if(strlen($bio) > 500)
					{
						$bioErr = "Bio cannot exceed 500 characters."; 
						$valid = false;
					}
				}
			   
				if($valid)
				{
					$stmt = mysqli_prepare($conn, "INSERT INTO profile(username, bio, privacy) VALUES (?, ?, ?)");
					mysqli_stmt_bind_param ($stmt, 'sss', $_SESSION['uname'], $bio, $_POST['privacy']);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
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
		
			<form method="post" action="createprofile.php" id="profile"> 
				Bio: <br /> 
				<textarea form="profile" name="bio" style="height:200px; width:500px;"><?php echo $bio;?></textarea><br />
				<span class="error"><?php echo $bioErr;?></span><br>
				Privacy:
				<select name="privacy">
					<option value="Public" selected>Public</option>
					<option value="Friends">Friends only</option>
					<option value="FOF">Friends of friends only</option>
					<option value="Private">Private</option>
				</select><br />
				<input type="submit" name="submit" value="Submit"> 
			</form>
		</div>
	</body>
</html>