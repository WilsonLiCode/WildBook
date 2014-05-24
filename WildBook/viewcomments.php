<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>View Comments</title>
		<style type="text/css"> 
			.posttab{
				width:100%; 
				border-collapse:collapse;
			}
			/*  Define the background color for all the ODD background rows  */
			.posttab tr:nth-child(odd){ 
				background: #b8d1f3;	
			}
			/*  Define the background color for all the EVEN background rows  */
			.posttab tr:nth-child(even){
				background: #dae5f4;
			}
			.posttab {
				text-align:center;
			}
		</style> 
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<script type="text/javascript">
		</script>
	</head>
	<body>
		<div class="main">
		<?php
			//Connect to DB
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") 
			{
				$stmt = mysqli_prepare($conn, "SELECT * FROM diarycomments WHERE deid = ? ORDER BY timestamp");
				mysqli_stmt_bind_param ($stmt, 'i', $_POST['deid']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				
				echo "<table border='0' class='posttab'>
				<tr>
				<th>Comments</th>
				</tr>";
				if(mysqli_num_rows($result)==0) {
					echo "<tr><td><i>No comments currently.</i></td></tr>";
				}
				else {					
					while($row = mysqli_fetch_array($result))
					{
						echo "<tr>";
			?>
						<td><div><a href='user.php?username=<?php echo $row['username']; ?>'><?php echo $row['username']; ?></a></div>
			<?php		
						echo "<div>".$row['body']."</div><div>".date("F j, Y, g:i a",strtotime($row['timestamp']))."</div></td>";
						echo "</tr>";
					}
				}
				echo "</table>";
			}
		?>
	</body>
</html>