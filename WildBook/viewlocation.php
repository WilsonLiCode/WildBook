<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Location Detail</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<style>
			table {width:100%};
		</style>
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
				$lid = $_GET["lid"];
				//connect to db
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");

				$result = mysqli_query($conn, "SELECT *
												FROM location l
												WHERE l.lid = $lid");
				echo "<table border=0>
				<tr>
				<th>Location Detail:</th>
				</tr>";
				

				while($row = mysqli_fetch_array($result)) { 
					echo "<tr><td>Name: " .$row['lname']. "</td></tr>";
					echo "<tr><td>Latitude: ".$row['latitude']."</td></td>";
					echo "<tr><td>Longitude: ".$row['longitude']."</td></td>";
					$long = $row['longitude'];
					$lat = $row['latitude'];				
				}
				echo "</table>";				
			?>
			<div class="googlemaps">
				<iframe
				  width="600"
				  height="400"
				  frameborder="0" style="border:0"
				  src="https://www.google.com/maps/embed/v1/view?key=AIzaSyApu94ZXr2aYiDnTxqW2Imhnl76LKZhHSY&center=<?php echo $lat;?>,<?php echo $long;?>&zoom=15&maptype=roadmap">
				</iframe>
			</div>		
		</div>
	</body>
</html>