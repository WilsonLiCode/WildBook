<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Messages</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<style>
			img{cursor:pointer; width:26px; height:26px;}
		</style>
		<script type="text/javascript">
			function view(meid)
			{
				var form = document.createElement("form");
				form.setAttribute("method", "post");
				form.setAttribute("action", "viewmessage.php");

				// setting form target to a window named 'formresult'
				form.setAttribute("target", "formresult");

				var hiddenField = document.createElement("input");              
				hiddenField.setAttribute("name", "meid");
				hiddenField.setAttribute("value", meid);
				form.appendChild(hiddenField);
				document.body.appendChild(form);

				// creating the 'formresult' window with custom features prior to submitting the form
				window.open('viewmessage.php', 'formresult', 'scrollbars=no,menubar=no,height=400,width=550,resizable=yes,toolbar=no,status=no');
				form.submit();
				document.body.removeChild(form);
			}
		</script>	
	</head>
	<body>
		<div class="sidebar">
			<div id="sidebar">
				<ul>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="home.php">Home</a></li>
					<li><a href="activity.php">Activity</a></li>
					<li><a href="location.php">Locations</a></li>
				</ul>
			</div>
		</div>
		<?php
			//connect to db
			$conn = mysqli_connect("localhost", "root");
			mysqli_select_db($conn, "wildbook");
			$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
			mysqli_stmt_bind_param ($unameCheck, 's', $uname);
			
			$stmt = mysqli_prepare($conn, "SELECT *
											FROM message
											WHERE message.touser = ?");
			mysqli_stmt_bind_param ($stmt, 's', $_SESSION['uname']);
			mysqli_stmt_execute($stmt);
			$mess = mysqli_stmt_get_result($stmt);
		?>
		<div class="messbox"><?php
			echo "<table border='0'>
			<tr>
			<th>Message</th>
			</tr>";
			if(mysqli_num_rows($mess)==0) {
				echo "<tr><td><i>You don't have any messages right now.</i></td></tr>";
			}
			else {
				while($row = mysqli_fetch_array($mess)) { 
					echo "<tr>";
					echo "<td> From: $row[1] at " . date("F j, Y, g:i a",strtotime($row[4])) ."<img src='msg.png' title='View' align='right' onclick='view(".$row['meid'].");'></td>";
					echo "</tr>";
				}
			}
		echo "</table>";
		?>
		<img src="compose.png" title="Write a new message" onclick="window.open('newmessage.php', 'newwindow', 'width=550, height=400'); return false;">
		</div>
	</body>
</html>