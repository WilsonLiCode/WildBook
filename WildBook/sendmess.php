<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>sending...</title>
	</head>
	<body>
		<?php
		//connect to db
		$conn = mysqli_connect("localhost", "root");
		mysqli_select_db($conn, "wildbook");
		$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
		mysqli_stmt_bind_param ($unameCheck, 's', $uname);
		
		$stmt = mysqli_prepare($conn, "INSERT INTO message(fromuser, touser, body) 
												VALUES (?, ?, ?)");
		mysqli_stmt_bind_param ($stmt, 'sss', $_SESSION['uname'], $_POST['touser'], $_POST['body']);
		mysqli_stmt_execute($stmt);
		
		if(isset($_SESSION['fromuser']))
			unset($_SESSION['fromuser']);
		?>
	<script type="text/javascript">
		window.close();
	</script>
	</body>
</html>