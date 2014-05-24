<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Like</title>
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
				$stmt = mysqli_prepare($conn, "INSERT INTO likediary(deid, username) VALUES (?, ?)");
				mysqli_stmt_bind_param ($stmt, 'is', $_POST['deid'], $_SESSION['uname']);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				echo "<script type='text/javascript'>";
				echo "window.close();";
				echo "</script>";
			}
		?>
	</body>
</html>