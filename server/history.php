<?php
	function history($type) {
		include 'db/config.php';
		$ip = $_SERVER['REMOTE_ADDR']; 
    	$sql = "SELECT * FROM records WHERE ip = '$ip' AND type = '$type' ORDER BY time DESC LIMIT 10";
		// Create connection
    	$conn = mysqli_connect($host, $username, $password, $dbname);
   		// Check connection
    	if (!$conn) {
      		die("Connection failed: " . mysqli_connect_error());
  		}
		$result = mysqli_query($conn, $sql);
		if (($row_num = mysqli_num_rows($result)) > 0) {
   	 		while ($row = mysqli_fetch_assoc($result)) {
				echo '<tr>';
				echo '<td>'.$row['ip'].'</td>';
				echo '<td>'.$row['time'].'</td>';
				echo '<td>'.$row['speed'].'</td>';
				echo '</tr>';
			}
    	} else {
       		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  		}
		for ($i = 0; $i < (10 - $row_num); $i++)
			echo '<tr><td> </td><td> </td><td> </td></tr>';
   		mysqli_close($conn);
	}
?>
