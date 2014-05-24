<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Friend Requests</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">		
		<style type="text/css"> 
			img{cursor:pointer; width:0.5cm; height:0.5cm;}
		</style> 
		<script type="text/javascript">
			function accept(uname)
			{
				document.getElementById("friend").value = uname;
				document.getElementById("friendform").action = "acceptvalidate.php";
				document.getElementById("friendform").submit();
			}
			
			function decline(uname)
			{
				document.getElementById("friend").value = uname;
				document.getElementById("friendform").action = "declinevalidate.php";
				document.getElementById("friendform").submit();
			}
		</script>
	</head>
	<body>
		<div class="sidebar">
			<div id="sidebar">
				<ul>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="home.php">Home</a></li>
					<li><a href="messages.php">Messages</a></li>
					<li><a href="activity.php">Activity</a></li>
				</ul>
			</div>
		</div>		
		<div class="main">	
			<?php
				//Connect to DB
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
					
				$stmt = mysqli_prepare($conn, 
					"SELECT *
					FROM friendship
					WHERE friend = ? AND username NOT IN
						(SELECT a.friend AS username
						FROM friendship a, friendship b
						WHERE a.username=? AND a.username=b.friend AND b.username=a.friend)");
				mysqli_stmt_bind_param ($stmt, 'ss', $_SESSION['uname'], $_SESSION['uname']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				
				if(mysqli_num_rows($result)==0)
				{
					echo "<div>You have no new friend requests.</div>";
				}
				else
				{
					echo "<table border='0'>
					<tr>
					<th>Friend Requests</th>
					</tr>";
					while($row = mysqli_fetch_array($result))
					{
						$uname = $row['username'];
						echo "<tr>";?>
						<td><div>You got a friend request from <a href='user.php?username=<?php echo $row['username']; ?>'><?php echo $row['username']; ?></a> on <?php echo date("F j, Y, g:i a",strtotime($row['timestamp'])); ?> </div><div><img onclick='accept("<?php echo $row['username']; ?>");' src='accept.png' /> <img onclick='decline("<?php echo $row['username']; ?>");' src='decline.png' /></div></td>
						<?php
						echo "</tr>";
					}
					echo "</table>";
				}
			?>
			<form method="post" action="" id="friendform"> 
				<input type="hidden" name="friend" id="friend" >
			</form>
		</div>
	</body>
</html>