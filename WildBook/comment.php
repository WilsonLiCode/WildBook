<!DOCTYPE html>

<?php 
	session_start(); 
	$_SESSION['deid'] = $_POST['deid'];
?>

<html>
	<head>
		<title>Comment</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<style type="text/css"> 
		
		</style> 
		<script type="text/javascript">
			
		</script>
	</head>
	<body>

		<form method="post" action="commentvalidate.php" id="comment">
			Enter comment: <br />
			<textarea form="comment" name="comment" style="height:200px; width:500px;"></textarea><br />
			<input hidden="hidden" type="text" id="deid" name="deid">
			<input type="submit" name="submit" value="Comment"> 
		</form>
		
	</body>
</html>