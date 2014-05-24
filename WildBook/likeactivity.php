<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>filler</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
	</head>
	<body>
		<?php
			//connect to db
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
			$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
			mysqli_stmt_bind_param ($unameCheck, 's', $uname);
			$stmt = mysqli_prepare($conn, "INSERT INTO likeactivity (username, aid)
											VALUES (?, ?)");
			mysqli_stmt_bind_param ($stmt, 'si', $_SESSION['uname'], $_POST['activitynotliked']);
			mysqli_stmt_execute($stmt);
			
			header('Location: activity.php');
		?>
	</body>
</html>