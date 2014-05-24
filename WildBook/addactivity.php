<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>filler</title>
	</head>
	<body>
		<?php
			//connect to db
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
			$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
			mysqli_stmt_bind_param ($unameCheck, 's', $uname);
			
			$stmt = mysqli_prepare($conn, "INSERT INTO activity (aname) VALUES (?);");
			mysqli_stmt_bind_param ($stmt, 's', $_POST['activity']);
			mysqli_stmt_execute($stmt);

			$result = mysqli_query($conn, "SELECT * FROM activity ORDER BY aid desc limit 1;");
			$row = mysqli_fetch_array($result);
			
			$stmt3 = mysqli_prepare($conn, "INSERT INTO likeactivity (username, aid) VALUES (?, ?);");
			mysqli_stmt_bind_param ($stmt3, 'si', $_SESSION['uname'], $row['aid']);
			mysqli_stmt_execute($stmt3);
			
			header('Location: activity.php');
		?>
	</body>
</html>