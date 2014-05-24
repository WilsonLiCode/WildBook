<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<style type="text/css"> 
			img.icon{cursor:pointer; width:0.5cm; height:0.5cm;}
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
		</style> 
		<script type="text/javascript">
			function view(deid)
			{
				var form = document.createElement("form");
				form.setAttribute("method", "post");
				form.setAttribute("action", "viewcomments.php");

				// setting form target to a window named 'formresult'
				form.setAttribute("target", "formresult");

				var hiddenField = document.createElement("input");              
				hiddenField.setAttribute("name", "deid");
				hiddenField.setAttribute("value", deid);
				form.appendChild(hiddenField);
				document.body.appendChild(form);

				// creating the 'formresult' window with custom features prior to submitting the form
				window.open(comment.php, 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');

				form.submit();
				document.body.removeChild(form);
			}
			function like(deid)
			{
				var form = document.createElement("form");
				form.setAttribute("method", "post");
				form.setAttribute("action", "likevalidate.php");

				// setting form target to a window named 'formresult'
				form.setAttribute("target", "formresult");

				var hiddenField = document.createElement("input");              
				hiddenField.setAttribute("name", "deid");
				hiddenField.setAttribute("value", deid);
				form.appendChild(hiddenField);
				document.body.appendChild(form);

				// creating the 'formresult' window with custom features prior to submitting the form
				window.open(comment.php, 'formresult', 'scrollbars=no,menubar=no,height=1,width=1,resizable=yes,toolbar=no,status=no');

				form.submit();
				document.body.removeChild(form);
			}
			function comment(deid)
			{
				var form = document.createElement("form");
				form.setAttribute("method", "post");
				form.setAttribute("action", "comment.php");

				// setting form target to a window named 'formresult'
				form.setAttribute("target", "formresult");

				var hiddenField = document.createElement("input");              
				hiddenField.setAttribute("name", "deid");
				hiddenField.setAttribute("value", deid);
				form.appendChild(hiddenField);
				document.body.appendChild(form);

				// creating the 'formresult' window with custom features prior to submitting the form
				window.open(comment.php, 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');

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
					<li><a href="messages.php">Messages</a></li>
					<li><a href="activity.php">Activities</a></li>
					<li><a href="location.php">Locations</a></li>
				</ul>
			</div>
		</div>
		<div class="searchbox">
			<form method="post" action="searchresult.php" id="search"> 
				<input type="text" name="keyword" placeholder="Search for keyword..." style="width:200px;">
				<input type="submit" name="submit" value="Search"> 
			</form>
		</div>		
		<div class="mainpage" >		
			<div id="navbar">
				<ul>
					<li><a href="friendrequests.php">Friend Requests</a></li>
					<li><a href="multimedia.php">Upload Multimedia</a></li>
					<li><a href="newentry.php">Post New Diary Entry</a></li>
					<li><a href="editprofile.php">Edit Profile</a></li>
				</ul>
			</div>
			<div class="friend">
				<?php
					$conn = mysqli_connect("localhost", "root");
					mysqli_select_db($conn, "wildbook");				
					$getFriends = mysqli_prepare($conn, 
						"SELECT a.friend AS username
						FROM friendship a, friendship b
						WHERE a.username = ? AND a.username=b.friend AND b.username=a.friend");
					mysqli_stmt_bind_param ($getFriends, 's', $_SESSION['uname']);
					mysqli_stmt_execute($getFriends);
					$result = mysqli_stmt_get_result($getFriends);
					
					echo "<table border='0'>
					<tr>
					<th>My Friends</th>
					</tr>";
					if(mysqli_num_rows($result)==0) {
						echo "<tr><td><i>You don't have any friends right now.</i></td></tr>";
					}
					else {			
					while($row = mysqli_fetch_array($result))
						{
							echo "<tr>";
							echo "<td><div>";
					?>
							<a href='user.php?username=<?php echo $row['username']; ?>'style="color:#0000ff;"><?php echo $row['username']; ?></a></div>
					<?php		
						}
					}
					echo "</table>";
				?>
			</div>
			<div class="diarypost">
				<div class="innerdiary">
				<?php
					$stmt = mysqli_prepare($conn, 
						"SELECT *
						FROM
							((SELECT deid, diaryentry.username, title, body, diaryentry.mid, diaryentry.lid, diaryentry.privacy, diaryentry.timestamp, lname, longitude, latitude, filetype
							FROM 
								(SELECT a.friend AS username
								FROM friendship a, friendship b
								WHERE a.username = ? AND a.username=b.friend AND b.username=a.friend) AS currFriends 
							NATURAL JOIN diaryentry LEFT OUTER JOIN location USING(lid) LEFT OUTER JOIN multimedia USING(mid)
							WHERE diaryentry.privacy != 'Private' AND TIMESTAMPDIFF(DAY, diaryentry.timestamp, NOW()) <= 60)
							UNION
							(SELECT NULL AS deid, username, NULL AS title, NULL AS body, mid, NULL AS lid, privacy, timestamp, NULL AS lname, NULL AS longitude, NULL AS latitude, filetype
							FROM multimedia NATURAL JOIN (SELECT a.friend AS username
								FROM friendship a, friendship b
								WHERE a.username = ? AND a.username=b.friend AND b.username=a.friend) AS currFriends 
							WHERE privacy != 'Private' AND mid NOT IN(SELECT mid FROM diaryentry WHERE mid IS NOT NULL))) AS allposts
						ORDER BY timestamp DESC");
					mysqli_stmt_bind_param ($stmt, 'ss', $_SESSION['uname'], $_SESSION['uname']);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					
					echo "<table border='0' class='posttab'>
					<tr>
					<th>Recent Posts by Friends</th>
					</tr>";
					if(mysqli_num_rows($result)==0) {
						echo "<tr><td><i>You dont have any posts right now.</i></td></tr>";
					}
					else {				
						while($row = mysqli_fetch_array($result))
						{
							echo "<tr>";
							echo "<td><div>".$row['title']." By ";
					?>
							<a href='user.php?username=<?php echo $row['username']; ?>'style="color:#0000ff;"><?php echo $row['username']; ?></a></div>
					<?php		
							echo "<div>".$row['body']."</div>";
							if($row['mid'] != NULL && substr($row['filetype'], 0, 5) == "image")
							{
								echo "<img src='upload/".$row['mid'].".".substr($row['filetype'], 6)."' />";
							}
							elseif($row['mid'] != NULL && substr($row['filetype'], 0, 5) == "video")
							{
								echo "<video width='320' height='240' src='upload/".$row['mid'].".".substr($row['filetype'], 6)."' controls />";
							}
							echo "<div>".date("F j, Y, g:i a",strtotime($row['timestamp']));
							if($row['lid'] != NULL)
							{
					?>
								at <a href='viewlocation.php?lid=<?php echo $row['lid']; ?>'style="color:#0000ff;"><?php echo $row['lname']; ?></a>
					<?php			
							}
							if($row['deid'] != NULL)
								echo "</div><img class='icon' onclick='view(".$row['deid'].");' src='comment.png' /> <img class='icon' onclick='like(".$row['deid'].");' src='like.png' /> <img class='icon' onclick='comment(".$row['deid'].");' src='write.png' />";
							echo "</td></tr>";
						}
					}
					echo "</table>";
				?>
				</div>
			</div>			
		</div>
	</body>
</html>