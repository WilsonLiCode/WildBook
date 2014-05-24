<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head> 
		<title>Edit Profile</title>
		<style type="text/css"> 
			.error {color: #FF0000;} 
		</style>
		<link rel="stylesheet" type="text/css" href="unlogged.css">		
		<script>
		</script>
	</head>
	<body> 
		<div class="sidebar">
			<div id="sidebar">
				<ul>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="home.php">Home</a></li>
					<li><a href="messages.php">Messages</a></li>
					<li><a href="activity.php">Activity</a></li>
				</ul>
			</div>
		</div>
		<div class="main">
			<div class="editcenter">
				<h2><b>Profile info:</b></h2>
				<?php
					// define variables and set to empty values
					$bioErr = $bio = "";
					$valid = true;
					
					//Connect to DB
					$conn = mysqli_connect("localhost", "root");
					mysqli_select_db($conn, "wildbook");
					
					if($_SERVER["REQUEST_METHOD"] != "POST") 
					{
						$getProfile = mysqli_prepare($conn, "SELECT * FROM profile WHERE username=?");
						mysqli_stmt_bind_param ($getProfile, 's', $_SESSION['uname']);
						mysqli_stmt_execute($getProfile);
						$profileResult = mysqli_stmt_get_result($getProfile);
						$profileRow = mysqli_fetch_assoc($profileResult);
						$bio = $profileRow['bio'];
					}

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
							$stmt = mysqli_prepare($conn, "UPDATE profile SET bio=?, privacy=? WHERE username=?");
							mysqli_stmt_bind_param ($stmt, 'sss', $bio, $_POST['privacy'], $_SESSION['uname']);
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
			
				<form method="post" action="editprofile.php" id="profile"> 
					Bio: <br /> 
					<textarea form="profile" name="bio" style="height:200px; width:500px;"><?php echo $bio;?></textarea><br />
					<span class="error"><?php echo $bioErr;?></span><br>
					Privacy:
					<select name="privacy">
				<?php
					if($profileRow['privacy'] == 'Public')
					{
				?>
						<option value="Public" selected>Public</option>
						<option value="Friends">Friends only</option>
						<option value="FOF">Friends of friends only</option>
						<option value="Private">Private</option>
				<?php
					}
					elseif($profileRow['privacy'] == 'Friends')
					{
				?>
						<option value="Public">Public</option>
						<option value="Friends" selected>Friends only</option>
						<option value="FOF">Friends of friends only</option>
						<option value="Private">Private</option>
				<?php
					}
					elseif($profileRow['privacy'] == 'FOF')
					{
				?>
						<option value="Public">Public</option>
						<option value="Friends">Friends only</option>
						<option value="FOF" selected>Friends of friends only</option>
						<option value="Private">Private</option>
				<?php
					}
					else
					{
				?>
						<option value="Public">Public</option>
						<option value="Friends">Friends only</option>
						<option value="FOF">Friends of friends only</option>
						<option value="Private" selected>Private</option>
				<?php
					}
				?>
					</select><br />
					<input type="submit" name="submit" value="Update"> 
				</form>
			</div>
		</div>
	</body>
</html>