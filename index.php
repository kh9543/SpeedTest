<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>台大資訊網路組 網路速度測試</title>
	<style type="text/css">
		.container {
			width: 900px;
			margin: 0 auto;
		}
		section, thead, tbody {
			margin: 0;
		}
		body {
			background-color: #E6E6E6;
			font-family: "微軟正黑體";
		}
		nav, p{
			margin: 0 auto;
			text-align: center;
		}
		table {
			margin: 0 auto;
			width: 85%;
			background: white;
			border-spacing: 0;
			border-collapse: collapse;
		}
		th, td {
			margin: 0px;
			padding: 4px;
			height: 14px;
			border: 1px solid #ddd;
			text-align: left;
		}
		tbody > tr:nth-child(odd) {
			background-color: #f2f2f2
		}
		footer {
			padding: 10px;
		}
		.download_pannel {
			display: inline-block;
			margin: 2.5px;
			width: 440px;
			height: 500px;
			font-size: 12px;
			font-weight: bold;
			color: #0B333C;
			text-align: center;
			background-color: #27A833;
	    	background: -webkit-linear-gradient(#27A833, #00800B); 
	    	background: -o-linear-gradient(#27A833, #00800B); 
	    	background: -moz-linear-gradient(#27A833, #00800B); 
	    	background: linear-gradient(#27A833, #00800B);
		}
		.upload_pannel {
			display: inline-block;
			margin: 2.5px;
			width: 440px;
			height: 500px;
			font-size: 12px;
			font-weight: bold;
			color: #0B333C;
			text-align: center;
			background-color: #95ABB6;
			background: -webkit-linear-gradient(#95ABB6, #6D838E); 
	    	background: -o-linear-gradient(#95ABB6, #6D838E); 
	    	background: -moz-linear-gradient(#95ABB6, #6D838E); 
			background: linear-gradient(#95ABB6, #6D838E);
		}
		.download_button {
			margin: 10px;
			margin-left: 45%;
			border-radius: 2px;
			padding: 8px;
			cursor: pointer;
			background: -webkit-linear-gradient(#C0E4C3, #64B16B); 
	    	background: -o-linear-gradient(#C0E4C3, #64B16B); 
	    	background: -moz-linear-gradient(#C0E4C3, #64B16B); 
			background: linear-gradient(#C0E4C3, #64B16B);
			border-radius: 5px; 
			opacity: 0.85;
		}
		.upload_button {
			margin: 10px;
			margin-left: 48%;
			border-radius: 2px;
			padding: 8px;
			cursor: pointer;
			background: -webkit-linear-gradient(#DEE5E8, #A6B3B9); 
	    	background: -o-linear-gradient(#DEE5E8, #A6B3B9); 
	    	background: -moz-linear-gradient(#DEE5E8, #A6B3B9); 
			background: linear-gradient(#DEE5E8, #A6B3B9);
			border-radius: 5px; 
			opacity: 0.85;
		}
		.progress {
			position: relative;
			height: 20px;
			line-height: 20px;
			margin: 20px auto;
			margin-bottom: 10px;
			background-color: white;
			width: 340px;
			padding: 0px;
		}
		.progress_wrap {
			height: 20px;
			width: 0%;
			background: -webkit-linear-gradient(#5EC1FF, #009DFF); 
	    	background: -o-linear-gradient(#5EC1FF, #009DFF); 
	    	background: -moz-linear-gradient(#5EC1FF, #009DFF); 
			background: linear-gradient(#5EC1FF, #009DFF);
		}
		.progress > span {
			left: 39%;
			top: 0px;
			position: absolute;
		}
        .active {
        	left: 35%;   
        }
		.record {
			margin-bottom: 10px;
		}
	</style>
	<script type="text/javascript">
 		var res_d = [];
		var res_u = [];
        var tasks = [1, 2, 4, 8, 16, 32, 64, 128];
        var btns = document.getElementsByTagName("button");
		function upload(s) {
          if(s == 1) {
          	disableBtn();
          	document.getElementById('upload_tasks').style.left = "35%";
          }
		  console.log("Start upload: "+ s);
		  var xhr = new XMLHttpRequest();
		  var url = "./server/?module=upload";
		  var size = s * 1024 * 1024; //s'MB
		  var buffer = new ArrayBuffer(size);
		  var int8View = new Int8Array(buffer);
		  var blob = new Blob([int8View], {type: "application/octet-stream"});
		  xhr.upload.addEventListener("progress", uploadProgress, false);
		  xhr.onreadystatechange = processUploadRequest; 
		  xhr.open('POST', url, true);
		  xhr.setRequestHeader("Content-Type", "application/octet-stream");
		  xhr.send(blob);
		  var startTime = new Date().getTime();
		  function uploadProgress(e) {
		  	if(e.lengthComputable) {
		  		//console.log(e.loaded/e.total);
                document.getElementById('upload_tasks').innerHTML = "Testing "+ s +"(MB): " + Math.round(e.loaded*100/e.total)+"%";
                document.getElementById('upload_progress').style.width = Math.round(e.loaded*100/e.total)+"%";
            }
		  }
		  function processUploadRequest() {
		    if(xhr.readyState == 4 && xhr.status == 200) {
		      var endTime = new Date().getTime();
		      var elapsedTime = endTime - startTime;
		      var uploadSpeed = (s*8/(elapsedTime/1000));
		      console.log(uploadSpeed);
                      res_u.push(uploadSpeed);
		      if (elapsedTime < 8000 && s <= 128)
		          upload(s*2);
              else {
                  console.log(res_u);
                  var sum = 0;
                  var div = 0;
                  for(var i = 0; i < res_u.length; i++ ) {
                     sum += res_u[i]*tasks[i];
                     div += tasks[i];
                  }
                  document.getElementById("upload_result").value = (sum/div).toFixed(4);
                  res_u = [];
                  enableBtn();
              }
		    }
		  }
		}
		// Creating Dowload Speed Test request to server
		function download(s) {
          if(s == 1) {
          	disableBtn();
          	document.getElementById('download_tasks').style.left = "35%";
          }
		  console.log("Start download: "+s);
		  var xhr = new XMLHttpRequest();
		  var url = "./server/";
		  var params = "module=download&size="
		  var size = s * 1024 *1024; // s'MB
		  xhr.addEventListener("progress", downloadProgress, false);
		  xhr.onreadystatechange = processDownloadRequest;
		  xhr.open('GET', url+"?"+params+size, true);
		  xhr.send();
		  var startTime = new Date().getTime();
		  function downloadProgress(e) {
		      //console.log(e.loaded/size);
		      document.getElementById('download_tasks').innerHTML = "Testing "+ s +"(MB): " + Math.round(e.loaded*100/size)+"%";
		      document.getElementById('download_progress').style.width = Math.round(e.loaded*100/size)+"%"; 
		  }
		  function processDownloadRequest() {
		    //msg received 
		    if (xhr.readyState == 4 && xhr.status == 200 ){
		      var endTime = new Date().getTime();
		      var elapsedTime = endTime - startTime; //ms
		      var downloadSpeed = (s*8/(elapsedTime/1000));
		      console.log(downloadSpeed);
		      //console.log(elapsedTime)
		      res_d.push(downloadSpeed);
		      if(elapsedTime < 8000 && s <= 128)
		          download(s*2);
		      else{
		          console.log(res_d);
                  var sum = 0;
                  var div = 0;
                  for(var i = 0; i < res_d.length; i++ ) {
                  	sum += res_d[i]*tasks[i];
                  	div += tasks[i];
                  }
                  document.getElementById("download_result").value = (sum/div).toFixed(4);
                  res_d = [];
              	  enableBtn();
		      	}
		      }
		    }
		 }	
        function disableBtn() {
          btns[0].disabled = true;
          btns[1].disabled = true;
        }
        function enableBtn() {
          btns[0].disabled = false;
          btns[1].disabled = false;
        }
	</script>
</head>
<body>
	<div class="container">
		<header>
			<nav>
				<img src="img/banner.png" width="890" height="150" border="0" usemap="#map">
				<map name="Map" id="Map">
					<area shape="rect" coords="633,40,862,108" href="http://itunes.apple.com/us/app/ntu-speed-test/id477952414" target="_blank">
					<area shape="rect" coords="386,40,615,108" href="https://market.android.com/details?id=ntu.speedman" target="_blank">
				</map>
			</nav>
		</header>
		<section class="pannel">
			<div style="padding: 10px">
				<p>歡迎使用台大計中HTML5網路速度測試網頁，本頁不支援舊版IE(&#60;10)</p>
			</div>
			<div class="download_pannel">
				<div class="progress">
					<div class="progress_wrap" id="download_progress"></div>
					<span id="download_tasks">Testing 0%</span>
				</div>
				<div>
					<span>Download speed = <input type="text" id="download_result"> Mbps </span>
				</div>
				<button type="button" class="download_button" onclick="download(1);"> 下載測試(Download Test)</button>
				<div>
					<p class="record">這個IP最近的下載紀錄</p>
				</div>
				<table>
					<thead>
						<tr>
							<th>IP</th>
							<th>時間</th>
							<th>速度(Mbps)</th>
						</tr>	
					</thead>
					<tbody>
						<tr>
							<td>36.228.159.203</td>
							<td>2016-11-28 19:14</td>
							<td>37.4943</td>
						</tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
					</tbody>
				</table>
			</div>
			<div class="upload_pannel">
				<div class="progress">
					<div class="progress_wrap" id="upload_progress"></div>
					<span id="upload_tasks">Testing 0%</span>
				</div>
				<div>
					<span>Upload speed = <input type="text" id="upload_result"> Mbps </span>
				</div>
				<button type="button" class="upload_button" onclick="upload(1)"> 上傳測試(Upload Test)</button>
				<div>
					<p class="record">這個IP最近的上傳紀錄</p>
				</div>
				<table>
					<thead>
						<tr>
							<th>IP</th>
							<th>時間</th>
							<th>速度(Mbps)</th>
						</tr>	
					</thead>
					<tbody>
						<tr>
							<td>36.228.159.203</td>
							<td>2016-11-28 19:14</td>
							<td>37.4943</td>
						</tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td></td></tr>
					</tbody>
				</table>
			</div>
		</section>
		<footer>
			<p>
				如果無法顯示測試數據,有可能被防毒軟體所誤擋(關閉即可),為調查連線到台大校園網路滿意度統計, <a href="http://speed.ntu.edu.tw/test.php">請按此</a>
				<br>
				If you can not see the test results, it may be blocked by anti-virus software.
				<br>
				若你連某些網站速度變慢,網路速度之瓶頸檢測教學, <a href="http://ccnet.ntu.edu.tw/network_debug/bottleneck.html">請按此</a>
				<br>
				There are some method to identify slow network speed, <a href="http://ccnet.ntu.edu.tw/network_debug/bottleneck.html">please click here.</a> 
				<br>
				工作團隊:台大計資中心、台大電信所、台大資工所
				<br>
				<a href="mailto:speed@ntu.edu.tw">任何建議請E-mail到speed@ntu.edu.tw</a>
				<br>
				<a href="mailto:speed@ntu.edu.tw">E-mail us</a>
			</p>
		</footer>
	<div>
</body>
</html>
