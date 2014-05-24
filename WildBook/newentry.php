<!DOCTYPE html>
<?php session_start();?> 
<html>
	<head>
		<title>New Diary Entry</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">		
		<style type="text/css"> 
			.error {color: #FF0000;} 
			.center {
				width:540px;
				margin-left:auto;
				margin-right:auto;	
			}
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
			<div class="center">
				<?php //diary entry
					// define variables and set to empty values
					$bodyErr = $body = $titleErr = $title = "";
					$valid = true;
					
					if ($_SERVER["REQUEST_METHOD"] == "POST") 
					{
						if (empty($_POST["title"])) 
						{
							$titleErr = "A title is required.";
							$valid = false;
						} 
						else
						{
							$title = test_input($_POST["title"]);
							if(strlen($title) > 50)
							{
								$titleErr = "The title cannot exceed 50 characters."; 
								$valid = false;
							}
						}
						
						if (empty($_POST["body"])) 
						{
							$bodyErr = "A body is required.";
							$valid = false;
						} 
						else
						{
							$body = test_input($_POST["body"]);
							if(strlen($body) > 500)
							{
								$bodyErr = "The body cannot exceed 500 characters."; 
								$valid = false;
							}
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
			
				<?php //File upload
					// define variables and set to empty values
					$fileErr = "";
					$mid = NULL;
					$nofile = false;
					$filevalid = true;
					
					//Connect to DB
					$conn = mysqli_connect("localhost", "root");
					mysqli_select_db($conn, "wildbook");
				
					if ($_SERVER["REQUEST_METHOD"] == "POST") 
					{
						if (empty($_FILES["file"]["name"])) 
						{
							$nofile = true;
							$filevalid = false;
							$fileErr = "No file.";
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
									$filevalid = false;
								} 
							} 
							else 
							{
								$fileErr = "File type not supported.";
								$filevalid = false;
							}
						}
						if($filevalid)
						{
							$stmt = mysqli_prepare($conn, "INSERT INTO multimedia(filetype, username, privacy, timestamp) VALUES (?, ?, ?, now())");
							mysqli_stmt_bind_param ($stmt, 'sss', $_FILES["file"]["type"], $_SESSION['uname'], $_POST['privacy']);
							mysqli_stmt_execute($stmt);
							$mid = mysqli_insert_id($conn);
							move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $mid . "." . substr($_FILES["file"]["type"], 6));
							$nofile = true;
						}
					}
				?>
				
				<?php //Locations
					$getLoc = mysqli_prepare($conn, "SELECT * FROM location");
					mysqli_stmt_execute($getLoc);
					$locations = mysqli_stmt_get_result($getLoc);
				?>
			
				<form action="newentry.php" method="post" id="entry" enctype="multipart/form-data">
					Title: <input type="text" name="title" id="title" value="<?php echo $title;?>">
					<span class="error"><?php echo $titleErr;?></span><br>
					Body: <br /> 
					<textarea form="entry" name="body" style="height:200px; width:500px;"><?php echo $body;?></textarea><br />
					<span class="error"><?php echo $bodyErr;?></span><br>
					<label for="file">Multimedia (Optional):</label>
					<input type="file" name="file" id="file"><br>
					<span class="error" >*Supported file types are .gif, .jpeg, .jpg, .png, .mp4 and less than 100MB.</span><br />
					Location (Optional):
				<?php
					echo "<select name='locations'><option value='-1' selected></option>";
					while($row = mysqli_fetch_array($locations)) 
					{
						echo "<option value=".$row['lid'].">".$row['lname']."</option>";
					}
					echo "</select><br />";
					
					if ($_SERVER["REQUEST_METHOD"] == "POST") 
					{
						if($_POST['locations'] == -1)
							$lid = NULL;
						else
							$lid = test_input($_POST["locations"]);
						if($valid && $nofile)
						{
							$insEntry = mysqli_prepare($conn, "INSERT INTO diaryentry(username, title, body, mid, lid, privacy, timestamp) VALUES (?, ?, ?, ?, ?, ?, now())");
							mysqli_stmt_bind_param ($insEntry, 'sssiis', $_SESSION['uname'], $title, $body, $mid, $lid, $_POST['privacy']);
							mysqli_stmt_execute($insEntry);
							mysqli_stmt_close($insEntry);
							header("Location: home.php");
						}
					}
				?>
					
					Privacy:
					<select name="privacy">
						<option value="Public" selected>Public</option>
						<option value="Friends">Friends only</option>
						<option value="FOF">Friends of friends only</option>
						<option value="Private">Private</option>
					</select><br />
					<input type="submit" name="submit" value="Post"><span class="error"><?php echo $fileErr;?></span><br>
				</form>
			</div>
		</div>
	</body>
</html>