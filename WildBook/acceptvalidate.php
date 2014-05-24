<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Accept</title>
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
				$privacy = "Public";
				$stmt = mysqli_prepare($conn, "INSERT INTO friendship(username, friend, privacy, timestamp) VALUES (?, ?, ?, now())");
				mysqli_stmt_bind_param ($stmt, 'sss', $_SESSION['uname'], $_POST['friend'], $privacy);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				header("Location: friendrequests.php");
			}
		?>
	</body>
</html>