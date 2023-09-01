<head>
<title>BlackAnt02's Server List</title>
<style>
body {
	margin: 0 auto;
	align: center;
	color: #fff;
	background-color: #000;
}
table {
	text-align: center;
	background-color: #333;
	color: #fff;
	padding: 25px 25px 25px 25px;
	width: 100%;
	height: 50%;
}
th {
	width: 200px;
	padding: 10px 10px 10px 10px;
	margin-bottom: 10px;
}
tr {
}
td {
	background-color: #222;
}
h1 {
	margin-top: 25px;
	font-size: 28px;
}
</style>
</head>
<body>
<center>
<h1><b>BlackAnt02's Server List</b></h1>
<table>
  <tr>
    <th>Server Name</th>
    <th>Server IP : Port</th>
    <th>Live Status</th>
	<th>Users</th>
  </tr>
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
	
	foreach ($statistic as $esr)
	{
		$sName = $esr['name'];
		$ipAddr = $esr['IP'];
		$port = $esr['port'];
		$users = $esr['users'];
		$max = $esr['max'];
		$serverStatus = pingDomain($ipAddr, $port);
		if($serverStatus == -1)
		{
			$ispublic = "0";
			$update_stmt = $conn->prepare("UPDATE server_data SET is_public = ? WHERE IP = ?");
			$update_stmt->bind_param("is", $ispublic, $ipAddr);
			$update_stmt->execute();
			
			$serverS = "<img src='.\red.png' width='16px' height='16px' /> ".$serverStatus."ms";
			echo '<tr><td>'.$sName.'</td><td>'.$ipAddr.":".$port.'</td><td>'.$serverS.'</td></tr></table>';
		}
		else
		{
			$serverS = "<img src='.\green.png' width='16px' height='16px' /> ".$serverStatus."ms";
			echo '<tr><td>'.$sName.'</td><td>'.$ipAddr.":".$port.'</td><td>'.$serverS.'</td><td>'.$users.' / '.$max.'</td></tr>';
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
</table>
</center>
</body>