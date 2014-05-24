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
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") 
			{
				$stmt = mysqli_prepare($conn, "DELETE FROM friendship WHERE username = ? AND friend = ?");
				mysqli_stmt_bind_param ($stmt, 'ss', $_POST['friend'], $_SESSION['uname']);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				header("Location: friendrequests.php");
			}
		?>
	</body>
</html>