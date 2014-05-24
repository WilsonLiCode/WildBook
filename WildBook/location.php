<!DOCTYPE HTML> 
<?php session_start(); ?>
<html>
	<head>
		<title>Locations</title>
		<link rel="stylesheet" type="text/css" href="unlogged.css">
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxSPW5CJgpdgO_s4yyMovOaVh_KvvhSfpvagV18eOyDWu7VytS6Bi1CWxw"
			type="text/javascript"></script>
			<script type="text/javascript">
			var map = null;
			var geocoder = null;

			function initialize() {
			  if (GBrowserIsCompatible()) {
				map = new GMap2(document.getElementById("map_canvas"));
				map.setCenter(new GLatLng(37.4419, -122.1419), 1);
				map.setUIToDefault();
				geocoder = new GClientGeocoder();
			  }
			}

			function showAddress(address) {
			  if (geocoder) {
				geocoder.getLatLng(
				  address,
				  function(point) {
					if (!point) {
					  alert(address + " not found");
					} else {
					  map.setCenter(point, 15);
					  var marker = new GMarker(point, {draggable: true});
					  map.addOverlay(marker);
					  GEvent.addListener(marker, "dragend", function() {
						marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
					  });
					  GEvent.addListener(marker, "click", function() {
						marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
					  });
				  GEvent.trigger(marker, "click");
					}
				  }
				);
			  }
			}
		</script>
		<style>
			h2 {text-align:center;}
		</style>
	</head>
	<body onload="initialize()" onunload="GUnload()">
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
			<div class="locationsliked" >
				<?php
				//connect to db
				$conn = mysqli_connect("localhost", "root");
				mysqli_select_db($conn, "wildbook");
				$unameCheck = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
				mysqli_stmt_bind_param ($unameCheck, 's', $uname);
				
				$stmt = mysqli_prepare($conn, "SELECT *
												FROM location l, likelocation ll, activity a
												WHERE a.aid = ll.aid AND l.lid = ll.lid AND ll.username = ?");
				mysqli_stmt_bind_param ($stmt, 's', $_SESSION['uname']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				
				echo "<table border=0>
				<tr>
				<th><h2>Locations you like and what you like doing at that location:</h2></th>
				</tr>";
				if(mysqli_num_rows($result)==0) {
					echo "<tr><td><i>You don't like any location right now.</i></td></tr>";
				}
				else {
					while($row = mysqli_fetch_array($result)) { 
						echo "<tr>";
						echo "<td><a href=\"viewlocation.php?lid='$row[0]'\" style='color:#0000ff;'>".$row['lname']."</a>, " .$row['aname']."</td>";
						echo "</tr>";
					}
				}
				echo "</table>";		
				?>
			</div>
			<h2>Like a new location? Choose a location and the activity associated!</h2>
			<div class="locationsnotliked">
				<?php
				$stmt2 = mysqli_prepare($conn, "SELECT * FROM location");
				//mysqli_stmt_bind_param ($stmt2, 's', $_SESSION['uname']);
				mysqli_stmt_execute($stmt2);
				$result2 = mysqli_stmt_get_result($stmt2);
				
				?><form action="likelocation.php" method="post"><?php
				echo "<select name='locationsnotliked'>";
				while($row2 = mysqli_fetch_array($result2)) {
					$lname = $row2['lname'];
					$lid = $row2['lid'];
					echo "<option value=".$lid.">".$lname."</option>";
				}
				echo "</select>";
				
				$result3 = mysqli_query($conn, "SELECT * FROM activity;");			
				echo "<select name='activitylinktoloc'>";
				while ($row2 = mysqli_fetch_array($result3)) {
					$aname = $row2['aname'];
					$aid = $row2['aid'];
					echo "<option value=".$aid.">".$aname."</option>";
				}
				echo "</select>";?>
					<input type="submit" value="Like">
				</form>
			</div>
			<h2>Location not there? Feel free to add a new location!</h2>
			<div class="newlocation">
				<form action="addlocation.php" method="post">
					<table>
						<tr>
							<td align="right">Add new location: </td>
							<td align="left"><input type="text" name="location"></td>
						</tr>
						<tr>
							<td align="right">Latitude: </td>
							<td align="left"><input type="number" step="any" name="lat"></td>
						</tr>
						<tr>
							<td align="right">Longitude: </td>
							<td align="left"><input type="number" step="any" name="long"></td>
						</tr>
						<tr>
							<td align="right">Activity: </td>
							<td align="left"><?php
											$result4 = mysqli_query($conn, "SELECT * FROM activity;");			
											echo "<select name='newlocact'>";
											while ($row3 = mysqli_fetch_array($result4)) {
												$aname2 = $row3['aname'];
												$aid2 = $row3['aid'];
												echo "<option value=".$aid2.">".$aname2."</option>";
											}
											echo "</select>";?><input type="submit" value="Add"></td>
						</tr>
					</table>
				</form>
			</div>
			<div class="lookuploc">
				<form action="#" onsubmit="showAddress(this.address.value); return false">
				  <p>
					Look up the coordinates with the search below.<br/>
					The latitude/longitude will appear in the infowindow after each geocode/drag.
				  </p>
				  <p>
					<input type="text" style="width:350px" name="address" value="NYU Poly" />
					<input type="submit" value="Search!" />
				  </p>
				  <div id="map_canvas" style="width: 600px; height: 400px"></div>
				</form>
			</div>			
		</div>
	</body>
</html>