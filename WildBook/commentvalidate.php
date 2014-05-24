<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Comment</title>
		<style type="text/css"> 
			.error {color: #FF0000;} 
		</style> 
		<script type="text/javascript">
			
		</script>
	</head>
	<body>
	
		<?php
			//Connect to DB
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
			
			// define variables and set to empty values
			$errMsg = "";
			$valid = true;
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") 
			{
				if (empty($_POST["comment"])) 
				{
					$errMsg = "Enter a comment.";
					$valid = false;
				} 
				elseif(strlen($_POST["comment"]) > 500)
				{
					$errMsg = "Comment cannot exceed 500 characters."; 
					$valid = false;
				}
				
				if($valid)
				{
					$stmt = mysqli_prepare($conn, "INSERT INTO diarycomments(dcid, deid, username, body, timestamp) VALUES (NULL, ?, ?, ?, now())");
					mysqli_stmt_bind_param ($stmt, 'iss', $_SESSION['deid'], $_SESSION['uname'], $_POST['comment']);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
					if(isset($_SESSION['deid']))
						unset($_SESSION['deid']);
					echo "<script type='text/javascript'>";
					echo "window.close();";
					echo "</script>";
				}
				
				if(isset($_SESSION['deid']))
					unset($_SESSION['deid']);
				echo "<script type='text/javascript'>";
				echo "window.close();";
				echo "</script>";
			}
			
			function test_input($data) 
			{
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}
		?>
	</body>
</html>