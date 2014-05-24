<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Viewing Message</title>
		<style>
		</style>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
	</head>
	<body>
		<?php
			//Connect to DB
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") 
			{
				$stmt = mysqli_prepare($conn, "SELECT * FROM message WHERE meid = ?");
				mysqli_stmt_bind_param ($stmt, 'i', $_POST['meid']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_array($result);
				?>
				<div class="mFromUser"><?php
				echo "From: ".$row['fromuser']." at ".date("F j, Y, g:i a",strtotime($row['timestamp']))."<br>";?></div>
				<div class="mBody"><?php
				echo $row['body'];
				$_SESSION['fromuser'] = $row['fromuser'];
				$_SESSION['meid'] = $row['meid'];?></div><?php
			}
		?>
		<br>
		<form action="reply.php">
			<input type="submit" value="Reply">
		</form>
		<form action="deletemessage.php">
			<input type="submit" value="Delete">
		</form>
		<button type="button" onclick="window.open('', '_self', ''); window.close();">Close Window</button>
	</body>
</html>	