<?php
    include 'db/config.php';
    $ip = $_SERVER['REMOTE_ADDR']; 
    $speed = filter_var($_POST['speed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $time = date('Y-m-d H:i:s');
    $type = $_POST['type'];
    if(isset($speed) && $speed > 0 && ($type=='upload' || $type=='download')) {
        if($type=='upload')
            $sql = "INSERT INTO upload_record(ip,speed,time) VALUES('$ip','$speed','$time')";
        else
            $sql = "INSERT INTO download_record(ip,speed,time) VALUES('$ip','$speed','$time')";
		// Create connection
    	$conn = mysqli_connect($host, $username, $password, $dbname);
   		// Check connection
    	if (!$conn) {
       		die("Connection failed: " . mysqli_connect_error());
  		}   
	    if (mysqli_query($conn, $sql)) {
    	    echo $time;
    	} else {
       		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  		}
   		mysqli_close($conn);
    } 
?>
