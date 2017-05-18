<!DOCTYPE html>
<html>
<head>
	<title>Test Concurrent Connection</title>
</head>
<body>
	<script type="text/javascript">
		var nRequest = []
		console.log("Start download: ");
		var url = "./server/";
		var params = "module=download&size=";
		var size = 128 * 1024 *1024;
		var startTime = new Date().getTime();
		for (var i=0; i<5; i++){
		   (function(i) {
		      nRequest[i] = new XMLHttpRequest();
		      nRequest[i].open("GET", url+"?"+params+size, true);
		      nRequest[i].onreadystatechange = function (oEvent) {
		         if (nRequest[i].readyState === 4) {
		            if (nRequest[i].status === 200) {
		              var endTime = new Date().getTime();
		              console.log(-(startTime-endTime));
		            } else {
		              console.log("Error", nRequest[i].statusText);
		            }
		         }
		      };
		      nRequest[i].send(null);
		      console.log(i)
		   })(i);
		}
	</script>
</body>
</html>