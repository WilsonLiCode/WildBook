<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head> 
		<title>Search Results</title>
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
			.posttab {
				text-align:center;
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
				
				$friends = array();
				$FOF = array();
				
				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
					$getFriends = mysqli_prepare($conn, 
						"SELECT a.friend AS username
						FROM friendship a, friendship b
						WHERE a.username=? AND a.username=b.friend AND b.username=a.friend");
					mysqli_stmt_bind_param ($getFriends, 's', $_SESSION['uname']);
					mysqli_stmt_execute($getFriends);
					$friendResult = mysqli_stmt_get_result($getFriends);
					while($row = mysqli_fetch_array($friendResult))
					{
						$friends[] = $row['username'];
					}
					
					$getFOF = mysqli_prepare($conn, 
						"SELECT DISTINCT a.friend AS username
						FROM 
							(SELECT a.friend AS username
							FROM friendship a, friendship b
							WHERE a.username=? AND a.username=b.friend AND b.username=a.friend) 
						AS currFriends, friendship a, friendship b
						WHERE currFriends.username=a.username AND a.username=b.friend AND b.username=a.friend");
					mysqli_stmt_bind_param ($getFOF, 's', $_SESSION['uname']);
					mysqli_stmt_execute($getFOF);
					$fofResult = mysqli_stmt_get_result($getFOF);
					while($row = mysqli_fetch_array($fofResult))
					{
						$FOF[] = $row['username'];
					}
				}
			?>
			<table border=0 class="posttab">
				<tr>
					<th>Search Results for "<?php echo $_POST['keyword']; ?>"</th>
				</tr>
			<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
					$keyword = "%".$_POST['keyword']."%";
					//Display users/profiles
					$getUsers = mysqli_prepare($conn, 
						"SELECT * 
						FROM user NATURAL JOIN profile 
						WHERE username LIKE ? OR fname LIKE ? OR lname LIKE ? OR city LIKE ? OR bio LIKE ?");
					mysqli_stmt_bind_param ($getUsers, 'sssss', $keyword, $keyword, $keyword, $keyword, $keyword);
					mysqli_stmt_execute($getUsers);
					$userResult = mysqli_stmt_get_result($getUsers);
					while($row = mysqli_fetch_array($userResult))
					{
						if(($_SESSION['uname'] == $row['username']) || $row['privacy'] == 'Public' || ($row['privacy'] == 'Friends' && in_array($row['username'], $friends)) || ($row['privacy'] == 'FOF' && (in_array($row['username'], $friends) || in_array($row['username'], $FOF))))
						{
			?>
							<tr><td><a href='user.php?username=<?php echo $row['username']; ?>'style="color:#0000ff;"><?php echo $row['username']; ?></a></td></tr>
			<?php
						}
					}
					
					//Display diary entry/multimedia
					$getMedia = mysqli_prepare($conn, 
						"(SELECT deid, diaryentry.username, title, body, diaryentry.mid, diaryentry.lid, diaryentry.privacy, diaryentry.timestamp, lname, longitude, latitude, filetype
						FROM diaryentry LEFT OUTER JOIN location USING(lid) LEFT OUTER JOIN multimedia USING(mid)
						WHERE diaryentry.username LIKE ? OR title LIKE ? OR body LIKE ? OR filetype LIKE ? OR lname LIKE ?)
						UNION
						(SELECT NULL AS deid, username, NULL AS title, NULL AS body, mid, NULL AS lid, privacy, timestamp, NULL AS lname, NULL AS longitude, NULL AS latitude, filetype
						FROM multimedia
						WHERE (username LIKE ? OR filetype LIKE ?) AND mid NOT IN(SELECT mid FROM diaryentry WHERE mid IS NOT NULL))");
					mysqli_stmt_bind_param ($getMedia, 'sssssss', $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword);
					mysqli_stmt_execute($getMedia);
					$mediaResult = mysqli_stmt_get_result($getMedia);
					while($row = mysqli_fetch_array($mediaResult))
					{
						if(($_SESSION['uname'] == $row['username']) || $row['privacy'] == 'Public' || ($row['privacy'] == 'Friends' && in_array($row['username'], $friends)) || ($row['privacy'] == 'FOF' && (in_array($row['username'], $friends) || in_array($row['username'], $FOF))))
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
					
					//Display location
					$getLocations = mysqli_prepare($conn, 
						"SELECT * 
						FROM location
						WHERE lname LIKE ?");
					mysqli_stmt_bind_param ($getLocations, 's', $keyword);
					mysqli_stmt_execute($getLocations);
					$locationResult = mysqli_stmt_get_result($getLocations);
					while($row = mysqli_fetch_array($locationResult))
					{
			?>
						<tr><td><a href='viewlocation.php?lid=<?php echo $row['lid']; ?>'style="color:#0000ff;"><?php echo $row['lname']; ?></a></td></tr>
			<?php
					}
				}
			?>
			</table>
		</div>
	</body>
</html>