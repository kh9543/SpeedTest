<?php
    //create connection to server
    function create_connection()
    {   
        include 'config.php';
        $link = mysql_connect($host,$username,$password);
        if(!$link)
            die("couldn't connect: ".mysql_error());
        return $link;
    }   
    //select database
    function execute_sql($dbname, $sql, $link)
    {   
        mysql_select_db($dbname, $link);
        $result = mysql_query($sql, $link);
        return $result;
    }   
?>
