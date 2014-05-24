<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<title><?php echo $_GET['username']; ?></title>
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
				window.open(comment.php, 'formresult', 'scrollbars=no,menubar=no,height=50,width=50,resizable=yes,toolbar=no,status=no');

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
					<li><a href="home.php">Home</a></li>
					<li><a href="messages.php">Messages</a></li>
					<li><a href="activity.php">Activity</a></li>
				</ul>
			</div>
		</div>
		<div class="usermain">
				<?php
				$friend = false;
				$FOF = false;
			
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
				$friendCheck = mysqli_prepare($conn, 
					"SELECT *
					FROM friendship
					WHERE (username = ? AND friend = ?) OR (username = ? AND friend = ?)");
				mysqli_stmt_bind_param ($friendCheck, 'ssss', $_SESSION['uname'], $_GET['username'], $_GET['username'], $_SESSION['uname']);
				mysqli_stmt_execute($friendCheck);
				$result = mysqli_stmt_get_result($friendCheck);
				
				$getProfile = mysqli_prepare($conn, 
					"SELECT *
					FROM profile NATURAL JOIN user
					WHERE username = ?");
				mysqli_stmt_bind_param ($getProfile, 's', $_GET['username']);
				mysqli_stmt_execute($getProfile);
				$profileResult = mysqli_stmt_get_result($getProfile);
				$profileRow = mysqli_fetch_assoc($profileResult);
				
				if(mysqli_num_rows($result)==2)
				{
					$friend = true;
					$FOF = true;
				}
				else
				{
					$fofCheck = mysqli_prepare($conn, 
						"SELECT DISTINCT a.friend AS username
						FROM 
						(SELECT a.friend AS username
						FROM friendship a, friendship b
						WHERE a.username=? AND a.username=b.friend AND b.username=a.friend) 
						AS currFriends, friendship a, friendship b
						WHERE currFriends.username=a.username AND a.username=b.friend AND b.username=a.friend AND a.friend = ?");
					mysqli_stmt_bind_param ($fofCheck, 'ss', $_SESSION['uname'], $_GET['username']);
					mysqli_stmt_execute($fofCheck);
					$fofresult = mysqli_stmt_get_result($fofCheck);
					if(mysqli_num_rows($fofresult) > 0)
					{
						$FOF = true;
					}
				}
			?>
			<div class="profilebutton">
				<?php
					if(($_SESSION['uname'] == $_GET['username']) || $profileRow['privacy'] == 'Public' || ($profileRow['privacy'] == 'Friends' && $friend) || ($profileRow['privacy'] == 'FOF' && $FOF))
					{
						if($_SESSION['uname'] != $_GET['username'])
						{
							$_SESSION['fromuser'] = $_GET['username'];
							$firstrow = mysqli_fetch_assoc($result);
							if(mysqli_num_rows($result)==0)
							{
				?>
								<div>
									<a href='sendfriendrequest.php?user=<?php echo $_GET['username'] ?>'style="color:#0000ff;">Send Friend Request</a>
								</div>
					<?php	
							}
							elseif(mysqli_num_rows($result)==2)
							{
					?>
								<div>
									<a href='deletefriend.php?user=<?php echo $_GET['username'] ?>'style="color:#0000ff;">Delete Friend</a>
								</div>
					<?php
							}
							elseif($firstrow['username'] == $_SESSION['uname'])
							{
					?>
								<div>
									<a href='deletefriend.php?user=<?php echo $_GET['username'] ?>'style="color:#0000ff;">Delete Friend Request</a>
								</div>
					<?php
							}
							else
							{
					?>
								<div>
									<a href='sendfriendrequest.php?user=<?php echo $_GET['username'] ?>'style="color:#0000ff;">Accept Friend Request</a>
								</div>
					<?php	
							}
					?>		
							<div>
								<a href='reply.php' onclick="window.open('reply.php', 'newwindow', 'width=550, height=300'); return false;"style="color:#0000ff;">Send Message</a>
							</div>
					<?php
						}
					?>
				</div>
				<div class="aboutme">
					<?php
						echo "<span><b>About Me:</b></span><br />";
						echo "<span>Name: ".$profileRow['fname']." ".$profileRow['lname']."</span><br />";
						echo "<span>City: ".$profileRow['city']."</span><br />";
						echo "<span>Birthday: ".date("F j, Y",strtotime($profileRow['dob']))."</span><br />";?>
							<div id="biobox"><?php
								echo "<span>".$profileRow['bio']."</span><br>";?>
							</div><?php
						echo "<span>Last Logged In: ".date("F j, Y, g:i a",strtotime($profileRow['lastaccessed']))."</span>";
					?>
				</div>
				<div id="profilepost">
					<div id="innerprofilepost">
					<?php
						$stmt = mysqli_prepare($conn, 
							"SELECT *
							FROM
								((SELECT deid, diaryentry.username, title, body, diaryentry.mid, diaryentry.lid, diaryentry.privacy, diaryentry.timestamp, lname, longitude, latitude, filetype
								FROM location RIGHT OUTER JOIN diaryentry USING(lid) LEFT OUTER JOIN multimedia USING(mid)
								WHERE diaryentry.username = ?)
								UNION
								(SELECT NULL AS deid, username, NULL AS title, NULL AS body, mid, NULL AS lid, privacy, timestamp, NULL AS lname, NULL AS longitude, NULL AS latitude, filetype
								FROM multimedia
								WHERE username = ? AND mid NOT IN(SELECT mid FROM diaryentry WHERE mid IS NOT NULL))) AS allposts
							ORDER BY timestamp DESC");
						mysqli_stmt_bind_param ($stmt, 'ss', $_GET['username'], $_GET['username']);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						
						echo "<table border='0' class='posttab'>
						<tr>
						<th>Posts by ".$_GET['username']."</th>
						</tr>";
						if(mysqli_num_rows($result)==0) {
							echo "<tr><td><i>You don't have any posts right now.</i></td></tr>";
						}
						else {			
							while($row = mysqli_fetch_array($result))
							{
								if($_SESSION['uname'] == $_GET['username'])
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
								elseif($friend)
								{
									if($row['privacy'] != 'Private')
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
								elseif($FOF)
								{
									if($row['privacy'] == 'Public' || $row['privacy'] == 'FOF')
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
								else
								{
									if($row['privacy'] == 'Public')
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
							}
						}
						echo "</table>";
					?>
					</div>
				</div>
				<div class="profilefriend">
					<?php
						$getFriends = mysqli_prepare($conn, 
							"SELECT a.friend AS username
							FROM friendship a, friendship b
							WHERE a.username = ? AND a.username=b.friend AND b.username=a.friend");
						mysqli_stmt_bind_param ($getFriends, 's', $_GET['username']);
						mysqli_stmt_execute($getFriends);
						$result = mysqli_stmt_get_result($getFriends);
						
						echo "<table border='0'>
						<tr>
						<th>".$_GET['username']."'s Friends:</th>
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
				<div class="profileact">
					<?php
						$actliked = mysqli_prepare($conn, "SELECT *
														FROM activity a, likeactivity la
														WHERE a.aid = la.aid AND la.username = ?");
						mysqli_stmt_bind_param ($actliked, 's', $_GET['username']);
						mysqli_stmt_execute($actliked);
						$result = mysqli_stmt_get_result($actliked);
						
						echo "<table border='0'>
						<tr>
						<th>Activities ".$_GET['username']." likes:</th>
						</tr>";
						if(mysqli_num_rows($result)==0) {
							echo "<tr><td><i>You don't like any location right now.</i></td></tr>";
						}
						else {
							while($row = mysqli_fetch_array($result)) 
							{ 
								echo "<tr>";
								echo "<td>".$row['aname']."</td>";
								echo "</tr>";
							}
						}
						echo "</table>";
					?>
				</div>
				<div class="profileloc">
					<?php
						$locliked = mysqli_prepare($conn, "SELECT *
														FROM location l, likelocation ll, activity a
														WHERE a.aid = ll.aid AND l.lid = ll.lid AND ll.username = ?");
						mysqli_stmt_bind_param ($locliked, 's', $_GET['username']);
						mysqli_stmt_execute($locliked);
						$result = mysqli_stmt_get_result($locliked);
						
						echo "<table border='0'>
						<tr>
						<th>Locations ".$_GET['username']." likes and what ".$_GET['username']." likes doing at that location:</th>
						</tr>";
						if(mysqli_num_rows($result)==0) {
							echo "<tr><td><i>You don't like any location right now.</i></td></tr>";
						}
						else {			
						while($row = mysqli_fetch_array($result)) { 
								echo "<tr>";
								echo "<td>";
						?>
								<a href='viewlocation.php?lid=<?php echo $row['lid']; ?>'style="color:#0000ff;"><?php echo $row['lname']; ?></a>, <?php echo $row['aname']; ?></td>
						<?php
								echo "</tr>";
							}
						}
						echo "</table>";	
					?>
				</div>
			<?php
				}
				else
				{
					echo "<div>You do not have permission to view this user's profile page.</div>";
				}
			?>
		</div>
	</body>
</html>