<!DOCTYPE html>
<?php session_start();?> 
<html>
	<head>
		<title>Multimedia</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">		
		<style type="text/css"> 
			.error {color: #FF0000;} 
		</style> 
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
			<?php
				// define variables and set to empty values
				$fileErr = "";
				$valid = true;
				
				//Connect to DB
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
			
				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
					if (empty($_FILES["file"]["name"])) 
					{
						$fileErr = "A file upload is required.";
						$valid = false;
					} 
					else
					{
						$allowedExts = array("gif", "jpeg", "jpg", "png", "mp4");
						$temp = explode(".", $_FILES["file"]["name"]);
						$extension = end($temp);

						if ((($_FILES["file"]["type"] == "image/gif")
						|| ($_FILES["file"]["type"] == "image/jpeg")
						|| ($_FILES["file"]["type"] == "image/jpg")
						|| ($_FILES["file"]["type"] == "image/pjpeg")
						|| ($_FILES["file"]["type"] == "image/x-png")
						|| ($_FILES["file"]["type"] == "image/png")
						|| ($_FILES["file"]["type"] == "video/mp4"))
						&& ($_FILES["file"]["size"] < 100000000)
						&& in_array($extension, $allowedExts)) 
						{
							if ($_FILES["file"]["error"] > 0) 
							{
								$fileErr = "Error Code: " . $_FILES["file"]["error"] . "<br>";
								$valid = false;
							} 
						} 
						else 
						{
							$fileErr = "File type not supported.";
							$valid = false;
						}
					}
					if($valid)
					{
						$stmt = mysqli_prepare($conn, "INSERT INTO multimedia(filetype, username, privacy, timestamp) VALUES (?, ?, ?, now())");
						mysqli_stmt_bind_param ($stmt, 'sss', $_FILES["file"]["type"], $_SESSION['uname'], $_POST['privacy']);
						mysqli_stmt_execute($stmt);
						$lastid = mysqli_insert_id($conn);
						move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $lastid . "." . substr($_FILES["file"]["type"], 6));
						header("Location: home.php");
					}
				}
			?>
		
			<form action="multimedia.php" method="post" enctype="multipart/form-data">
				<label for="file">File:</label>
				<input type="file" name="file" id="file"><br>
				<span>*Supported file types are .gif, .jpeg, .jpg, .png, .mp4 and less than 100MB.</span><br />
				Privacy:
				<select name="privacy">
					<option value="Public" selected>Public</option>
					<option value="Friends">Friends only</option>
					<option value="FOF">Friends of friends only</option>
					<option value="Private">Private</option>
				</select><br />
				<input type="submit" name="submit" value="Submit"><span class="error"><?php echo $fileErr;?></span><br>
			</form>
		</div>
	</body>
</html>