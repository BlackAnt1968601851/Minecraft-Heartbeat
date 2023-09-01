<?php
	
	$db_host = "localhost";
    $db_user = "minecraft";
    $db_pass = "test";
    $db_name = "minecraft";
	
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	
	// Function to check response time
	function pingDomain($domain, $port){
		$starttime = microtime(true);
		$file      = fsockopen ($domain, $port, $errno, $errstr, 1);
		$stoptime  = microtime(true);
		$status    = 0;

		if (!$file) $status = -1;  // Site is down
		else {
			fclose($file);
			$status = ($stoptime - $starttime) * 1000;
			$status = floor($status);
		}
		return $status;
	}
	//End Function call, DO NOT EDIT!

	// Check if the server already exists based on IP address
    $existing_server_stmt = $conn->prepare("SELECT * FROM server_data WHERE is_public = 1");
    $existing_server_stmt->execute();
    $existing_server_result = $existing_server_stmt->get_result();
	$statistic = $existing_server_result->fetch_all(MYSQLI_ASSOC);
	
	echo "Server List<br /><br />";
	
	foreach ($statistic as $esr)
	{
		$sName = $esr['name'];
		$ipAddr = $esr['IP'];
		$port = $esr['port'];
		$serverStatus = pingDomain($ipAddr, $port);
		if($serverStatus == -1)
		{
			$serverS = "Server Offline";
		}
		else
		{
			$serverS = "Server Online, Ping is ".$serverStatus."ms";
			echo $sName.' - '.$ipAddr.":".$port.' | '.$serverS;
		}
	}
	
	


	/*$res = pingDomain($domain, $port);

	if($res == -1)
	{
		//Server is offline, Connect and set status to 0
	}
	else
	{
		//Connect to database and set status to 1
	}*/
?>