<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Composing New Message</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
	</head>
	<body>
		<form id="newmess" action="sendmess.php" method="post">
			<input type="hidden" name="touser" value="<?php echo $_SESSION["fromuser"];?>"><?php echo $_SESSION['fromuser'];?><br>
			<textarea form="newmess" name="body" style="height:200px; width:450px;"></textarea><br>
			<input type="submit" value="Send">
		</form>
	</body>
</html>