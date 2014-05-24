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
			
			//adding user, locationid, and activity id into likelocation table
			$stmt = mysqli_prepare($conn, "INSERT INTO likelocation (username, aid, lid)
											VALUES (?, ?, ?)");
			mysqli_stmt_bind_param ($stmt, 'sii', $_SESSION['uname'], $_POST['activitylinktoloc'], $_POST['locationsnotliked']);
			mysqli_stmt_execute($stmt);
			
			//making user like the activity in case the user didnt already like it
			$stmt3 = mysqli_prepare($conn, "INSERT INTO likeactivity (username, aid) VALUES (?, ?);");
			mysqli_stmt_bind_param ($stmt3, 'si', $_SESSION['uname'], $_POST['activitylinktoloc']);
			mysqli_stmt_execute($stmt3);			
			
			header('Location: location.php');
		?>
	</body>
</html>