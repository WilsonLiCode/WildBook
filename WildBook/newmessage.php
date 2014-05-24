<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Composing New Message</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
	</head>
	<body>
		<form id="newmess" action="sendmess.php" method="post">
			<input type="text" name="touser" placeholder="Username"><br>
			<textarea form="newmess" name="body" style="height:250px; width:500px;"></textarea><br>
			<input type="submit" value="Send">
		</form>
	</body>
</html>