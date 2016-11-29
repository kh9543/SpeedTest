<?php
    if(isset($_POST['speed']) && isset($_POST['type'])) {
        if($_POST['type']=='upload' || $_POST['type'] == 'download') {
    	    echo $_SERVER['REMOTE_ADDR'].' '.$_POST['speed'].' '.$_POST['type'].' '.time();
        }
    }
?>
