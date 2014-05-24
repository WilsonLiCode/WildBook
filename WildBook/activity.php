<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Activity</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<style type="text/css">     
			table {
				width:100%;
			}
			h2 {text-align:center;}	
		</style>
	</head>
	<body>
		<div class="sidebar">
			<div id="sidebar">
				<ul>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="home.php">Home</a></li>
					<li><a href="messages.php">Messages</a></li>
					<li><a href="location.php">Locations</a></li>
				<ul>
			</div>
		</div>
		<div class="main">
			<div class="activityliked">
				<?php
				//connect to db
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
				$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
				mysqli_stmt_bind_param ($unameCheck, 's', $uname);
				
				$stmt = mysqli_prepare($conn, "SELECT *
												FROM activity a, likeactivity la
												WHERE a.aid = la.aid AND la.username = ?");
				mysqli_stmt_bind_param ($stmt, 's', $_SESSION['uname']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				
				echo "<table border=0>
				<tr>
				<th><h2>Activities you like:</h2></th>
				</tr>";
				if(mysqli_num_rows($result)==0) {
					echo "<tr><td><i>You don't like any activities right now.</i></td></tr>";
				}
				else {				
					while($row = mysqli_fetch_array($result)) { 
						echo "<tr>";
						echo "<td>".$row['aname']."</td>";
						echo "</tr>";
					}
				}
				echo "</table>";		
				?>
			</div>
			<h2>Like a new activity? Choose an activity you like!</h2>
			<div class="activitiesnotliked">
				<?php
				$stmt2 = mysqli_prepare($conn, "SELECT aid, aname
												FROM activity
												WHERE aid NOT IN (SELECT aid
																	FROM likeactivity
																	WHERE username = ?)");
				mysqli_stmt_bind_param ($stmt2, 's', $_SESSION['uname']);
				mysqli_stmt_execute($stmt2);
				$result2 = mysqli_stmt_get_result($stmt2);
				
				?><form action="likeactivity.php" method="post"><?php
				echo "<select name='activitynotliked'>";
				while($row2 = mysqli_fetch_array($result2)) {
					$aname = $row2['aname'];
					$aid = $row2['aid'];
					echo "<option value=".$aid.">".$aname."</option>";
				}
				echo "</select>";?>
					<input type="submit" value="Like">
				</form>
			</div>
			<h2>Activity not listed? Add a new one!</h2>			
			<div class="newactivity">
				<form action="addactivity.php" method="post">
					Add new activity: <input type="text" name="activity">
					<input type="submit" value="Add">
				</form>
			</div>
		</div>
	</body>
</html>