<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Delete</title>
		<style type="text/css"> 

		</style> 
		<script type="text/javascript">
			
		</script>
	</head>
	<body>
	
		<?php
			//Connect to DB
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
			
			$loc = "Location: user.php?username=" . $_GET['user'];
				
			$stmt = mysqli_prepare($conn, "DELETE FROM friendship WHERE (username = ? AND friend = ?) OR (username = ? AND friend = ?)");
			mysqli_stmt_bind_param ($stmt, 'ssss', $_GET['user'], $_SESSION['uname'], $_SESSION['uname'], $_GET['user']);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			header($loc);
		?>
	</body>
</html>