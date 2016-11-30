<?php
	include 'db/config.php';
	$ip = $_SERVER['REMOTE_ADDR']; 
    $speed = filter_var($_POST['speed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $type = $_POST['type'];
    if(isset($speed) && ($type=='upload' || $type=='download')) {
        $sql = "INSERT INTO records(ip,speed,type) VALUES('$ip','$speed','$type')";
		// Create connection
    	$conn = mysqli_connect($host, $username, $password, $dbname);
   		// Check connection
    	if (!$conn) {
       		die("Connection failed: " . mysqli_connect_error());
  		}   
	    if (mysqli_query($conn, $sql)) {
    	    echo "Recorded";
    	} else {
       		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  		}
   		mysqli_close($conn);
    } 
?>
