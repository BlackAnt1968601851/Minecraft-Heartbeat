<?php
//
// get POST data, if is not set return "UNDEFINED"
//

// Server name
if( isset($_POST["name"]) ) { $name = $_POST["name"]; } else { $name = "UNDEFINED"; }
// Count of players on server now
if( isset($_POST["users"]) ) { $users = (int)$_POST["users"]; } else { $users = "UNDEFINED"; }
// Count of max players on server
if( isset($_POST["max"]) ) { $max = (int)$_POST["max"]; } else { $max = "UNDEFINED"; }
// Server public boolean
$ispublic = filter_var($_POST["public"], FILTER_VALIDATE_BOOLEAN);
if ($ispublic == false) {echo "You are Set to Offline Server Will not be added to List"; exit();}
if( isset($_POST["public"]) ) { $ispublic = filter_var($_POST["public"], FILTER_VALIDATE_BOOLEAN); } else { $ispublic = "UNDEFINED"; }
// Server port
if( isset($_POST["port"]) ) { $port = (int)$_POST["port"]; } else { $port = "UNDEFINED"; }
// Server salt
if( isset($_POST["salt"]) ) { $salt = $_POST["salt"]; } else { $salt = "UNDEFINED"; }
// Server protocol
if( isset($_POST["version"]) ) { $version = (int)$_POST["version"]; } else { $version = "UNDEFINED"; }

// Server UUID. ONLY FOR MODIFIED SERVERS!
if( isset($_POST["uuid"]) ) { $uuid = $_POST["uuid"]; } else { }

// {
//    "name": "Minceraft Server",
//    "users": 3,
//    "max": 16,
//    "public": false,
//    "port": 27015.
//    "salt": "00000000000000",
//    "version": 6
// }
$serverstatus = array( 
			"name" => $name,
			"users" => $users,             
			"max" => $max,
			"public" => $ispublic,
			"port" => $port,
			"salt" => $salt,
			"version" => $version
);

// encode json from array
$serverjson = json_encode($serverstatus);

$serverfile = "server.json";
if( !empty($uuid) ) { $serverfile = "server-" . $uuid . ".json"; }

echo "Welcome your Server ID is: ", $salt, " You are Connected to BlackAnt's Server Listing.";

// recreate (if exists) and write json to serverinfo file
$srv = fopen($serverfile, "w+");
fwrite($srv, $serverjson);
fclose($srv);
?>
