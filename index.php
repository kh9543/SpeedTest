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
			height: 20px;
			padding: 4px;
			border: 1px solid #ddd;
			text-align: left;
		}
		td:first-child {
			width: 32%;
		}
		td:nth-child(2){
			width: 42%;
		}
		tbody > tr:nth-child(odd) {
			background-color: #f2f2f2;
		}
		tbody > tr:hover {
			background-color: #B2E1FF;
		}
		footer {
			padding: 10px;
		}
		.download_pannel {
			display: inline-block;
			margin: 2.5px;
			width: 440px;
			height: 500px;
			font-size: 13px;
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
			font-size: 13px;
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
			line-height: 22px;
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
			left: 40%;
			top: 0px;
			position: absolute;
		}
		.record {
			margin-bottom: 10px;
		}
	</style>
	<script type="text/javascript">
	(function() {
		if(!window.Blob)
			return;
		var btns;
		var upload_err = 0;
		var upload_fin = 0;
		var download_err = 0;
		var download_fin = 0;
		var total_u = 0;
		var total_d = 0;
		var res_d = [];
		var res_u = [];
	    var tasks = [1, 2, 4, 8, 16, 32, 64, 128];
	    window.onload = function() {
			var downloadBar = new progressBar('download'); 
			var uploadBar = new progressBar('upload');
			btns = document.getElementsByClassName("btn");
			document.getElementById('download_btn').onclick = function(){
				disableBtn();
				download(1, 12000);
				for(var i = 1; i<=31; i++)
					(function(j){setTimeout(function timer(){
						if(download_err == 1){
							downloadBar.loading(0);
							return;
						}
						downloadBar.loading(Math.round(j*100/31))
						if(j == 31 && download_fin > 0) {
							document.getElementById("download_result").value = download_fin;
							downloadBar.loading(100);
							enableBtn();
							download_fin = 0;
						}
						else if (j == 31 && download_fin != 1){
							var interval_id = setInterval(function(){
								if(download_fin > 0) {
									document.getElementById("download_result").value = download_fin;
									downloadBar.loading(100);
									enableBtn();
									download_fin = 0;
									clearInterval(interval_id);
								}
							}, 500);
						}
					}, j*400)})(i);
			}
			document.getElementById('upload_btn').onclick = function(){
				disableBtn();
				upload(1, 12000);
	            for(var i = 1; i<=31; i++)
	                (function(j){setTimeout(function timer(){
	                	if(upload_err == 1) {
	                		uploadBar.loading(0);
	                		return;
	                	}
	                	uploadBar.loading(Math.round(j*100/31))
	                	if(j == 31 && upload_fin > 0) {
							document.getElementById("upload_result").value = upload_fin;
							uploadBar.loading(100);
							enableBtn();
							upload_fin = 0;
						}
	                	else if (j == 31 && upload_fin == 0){
							var interval_id = setInterval(function(){
								if(upload_fin > 0) {
									document.getElementById("upload_result").value = upload_fin;
									uploadBar.loading(100);
									enableBtn();
									upload_fin = 0;
									clearInterval(interval_id);
								}
							}, 500);
						}
	                }, j*400)})(i);
	        }
		}
		function upload(s, t) {
		  console.log("Start upload: "+ s);
		  var xhr = new XMLHttpRequest();
		  var url = "./server/?module=upload";
		  var size = s * 1024 * 1024; //s'MB
		  var buffer = new ArrayBuffer(size);
		  var int8View = new Int8Array(buffer);
		  var blob = new Blob([int8View], {type: "application/octet-stream"});
		  var records = [];
		  xhr.upload.addEventListener("progress", uploadProgress, false);
		  xhr.addEventListener("error", errorHandler_u, false);
		  xhr.timeout = t;
		  xhr.onreadystatechange = processUploadRequest;
		  xhr.ontimeout = uploadTimeout;
		  xhr.open('POST', url, true);
		  xhr.setRequestHeader("Content-Type", "application/octet-stream");
		  xhr.send(blob);
		  var startTime = new Date().getTime();
		  function uploadTimeout(e) {
		  	var slice_s = 0;
		  	var upload_speed = 0;
		  	var sum = 0;
	        var div = 0;
		  	if(records.length == 2) {
		  		slice_s = records[1].s - records[0].s;
		  		uploadSpeed = ((slice_s*8/1024/1024)/((records[1].t - records[0].t)/1000));
				console.log(uploadSpeed);
		  	}
	        for(var i = 0; i < res_u.length; i++ ) {
	            sum += res_u[i]*tasks[i];
	            div += tasks[i];
	        }
	        sum += uploadSpeed * s * (slice_s/size);
	        div += s * slice_s/size;
	        upload_fin = (sum/div).toFixed(4);
			report(upload_fin, "upload");
	        res_u = [];

		  }
		  function uploadProgress(e) {
		  	if(e.lengthComputable) {
		  		slice = {t: e.timeStamp,
		  				 s: e.loaded};
		  		if (records.length == 0)
		  			records[0] = slice;
		  		else
		  			records[1] = slice;	
	        }
		  }
		  function processUploadRequest(e) {
		    if(xhr.readyState == 4 && xhr.status == 200) {
		      var endTime = new Date().getTime();
		      var elapsedTime = endTime - startTime;
		      var uploadSpeed = (s*8/(elapsedTime/1000));
		      total_u = total_u + elapsedTime;
		      console.log(uploadSpeed);
	          res_u.push(uploadSpeed);
		      if (total_u < 12000 && s < 128)
		          upload(s*2, 12000 - total_u);
	          else {
	              console.log(res_u);
	              var sum = 0;
	              var div = 0;
	              for(var i = 0; i < res_u.length; i++ ) {
	                 sum += res_u[i]*tasks[i];
	                 div += tasks[i];
	              }
	              upload_fin = (sum/div).toFixed(4);
				  report(upload_fin, "upload");
	              res_u = [];
	          }
		    }
		  }
		}
		function errorHandler_u(t) {
			alert("發生錯誤，請試著重整網頁");
			upload_err = 1;
		}

		// Creating Dowload Speed Test request to server
		function download(s, t) {
		  console.log("Start download: "+s);
		  var xhr = new XMLHttpRequest();
		  var url = "./server/";
		  var params = "module=download&size=";
		  var size = s * 1024 *1024; // s'MB
		  var records = [];
		  xhr.addEventListener("progress", downloadProgress, false);
		  xhr.addEventListener("error", errorHandler_d, false);
		  xhr.timeout = t;
		  xhr.onreadystatechange = processDownloadRequest;
		  xhr.ontimeout = downloadTimeout; 
		  xhr.open('GET', url+"?"+params+size, true);
		  xhr.send();
		  var startTime = new Date().getTime();
		  function downloadTimeout(e) {
		  	var slice_s = 0;
		  	var downloadSpeed = 0;
		  	var sum = 0;
	        var div = 0;
		  	if(records.length == 2) {
		  		slice_s = records[1].s - records[0].s;
		  		downloadSpeed = ((slice_s*8/1024/1024)/((records[1].t - records[0].t)/1000));
				console.log(downloadSpeed);
		  	}
	        for(var i = 0; i < res_d.length; i++ ) {
	            sum += res_d[i]*tasks[i];
	            div += tasks[i];
	        }
	        sum += downloadSpeed * s * (slice_s/size);
	        div += s * slice_s/size;
	        download_fin = (sum/div).toFixed(4);
			report(download_fin, "download");
	        res_d = [];
		  }
		  function downloadProgress(e) {
		  		slice = {t: e.timeStamp,
		  				 s: e.loaded};
		  		if (records.length == 0)
		  			records[0] = slice;
		  		else
		  			records[1] = slice;
		  }
		  function processDownloadRequest() {
		    //msg received 
		    if (xhr.readyState == 4 && xhr.status == 200 ){
		      var endTime = new Date().getTime();
		      var elapsedTime = endTime - startTime; //ms
		      var downloadSpeed = (s*8/(elapsedTime/1000));
		      total_d = total_d + elapsedTime;
		      console.log(downloadSpeed);
		      //console.log(elapsedTime)
		      res_d.push(downloadSpeed);
		      if(total_d < 12000 && s < 128)
		          download(s*2, 12000 - total_d);
		      else{
		          console.log(res_d);
	              var sum = 0;
	              var div = 0;
	              for(var i = 0; i < res_d.length; i++ ) {
	              	sum += res_d[i]*tasks[i];
	              	div += tasks[i];
	              }
	              download_fin = (sum/div).toFixed(4);
	              res_d = [];
				  report(download_fin, "download");
				  //download_fin = 1;
		      	}
		      }
		    }
		}
		function errorHandler_d(t) {
			alert("發生錯誤，請試著重整網頁");
			download_err = 1;
		}
		function progressBar(n) {
			this.bar = document.getElementById(n+'_progress');
			this.text = document.getElementById(n+'_tasks');
		}
		progressBar.prototype.loading = function (x) {
			this.bar.style.width = x+"%";
			this.text.innerHTML = "Testing: "+x+"%";
			
		}
		function report(s, t) {
			var xhr_u = new XMLHttpRequest();
			var url = "./server/report.php";
			xhr_u.open('POST', url, true);
			xhr_u.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_u.send('speed='+s+'&type='+t);
		}
	    function disableBtn() {
	    	btns[0].disabled = true;
	    	btns[1].disabled = true;
	    }
	    function enableBtn() {
	    	btns[0].disabled = false;
	    	btns[1].disabled = false;
		}
	})();	
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
				<button type="button" id="download_btn" class="btn download_button"> 下載測試(Download Test)</button>
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
						<?php 
							include 'server/history.php';
							history("download");
						?>
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
				<button type="button" id="upload_btn" class="btn upload_button"> 上傳測試(Upload Test)</button>
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
						<?php
							history('upload');
						?>
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
	<script>  
	</script>
</body>
</html>
