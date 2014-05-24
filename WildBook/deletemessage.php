<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Deleting...</title>
	</head>
	<body>
		<?php
		//connect to db
		$conn = mysqli_connect("localhost", "root");
		mysqli_select_db($conn, "wildbook");
		$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
		mysqli_stmt_bind_param ($unameCheck, 's', $uname);
		
		$stmt = mysqli_prepare($conn, "DELETE FROM message
										WHERE meid = ?");
		mysqli_stmt_bind_param ($stmt, 'i', $_SESSION['meid']);
		mysqli_stmt_execute($stmt);
		
		if(isset($_SESSION['meid']))
			unset($_SESSION['meid']);
		?>
	<script type="text/javascript">
		url = opener.window.location;
		opener.window.location = url;
		window.close();
	</script>
	</body>
</html>