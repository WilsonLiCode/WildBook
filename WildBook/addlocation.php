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
			
			$stmt = mysqli_prepare($conn, "INSERT INTO location (lname, longitude, latitude) VALUES (?, ?, ?);");
			mysqli_stmt_bind_param ($stmt, 'sdd', $_POST['location'], $_POST['long'], $_POST['lat']);
			mysqli_stmt_execute($stmt);

			$result = mysqli_query($conn, "SELECT * FROM location ORDER BY lid desc limit 1;");
			$row = mysqli_fetch_array($result);
			$lid = $row['lid'];
			
			$stmt2 = mysqli_prepare($conn, "INSERT INTO likelocation (username, aid, lid)
											VALUES (?, ?, ?)");
			mysqli_stmt_bind_param ($stmt2, 'sii', $_SESSION['uname'], $_POST['newlocact'], $lid);
			mysqli_stmt_execute($stmt2);			
			
			$stmt3 = mysqli_prepare($conn, "INSERT INTO likeactivity (username, aid) VALUES (?, ?);");
			mysqli_stmt_bind_param ($stmt3, 'si', $_SESSION['uname'], $_POST['newlocact']);
			mysqli_stmt_execute($stmt3);
			
			header('Location: location.php');
		?>
	</body>
</html>	