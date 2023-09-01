<?php
// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Server IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Generate a unique salt (you can modify this method as needed)
    $salt = generateSalt();
    
    // Connect to the MySQL database
    $db_host = "localhost";
    $db_user = "minecraft";
    $db_pass = "test";
    $db_name = "minecraft";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the server already exists based on IP address
    $existing_server_stmt = $conn->prepare("SELECT IP FROM server_data WHERE IP = ?");
    $existing_server_stmt->bind_param("s", $ip_address);
    $existing_server_stmt->execute();
    $existing_server_result = $existing_server_stmt->get_result();

    $update_stmt = null; // Initialize the update statement variable
    $insert_stmt = null; // Initialize the insert statement variable
    $salt_query = null;  // Initialize the salt query variable

    if ($existing_server_result->num_rows > 0) {
        // Server already exists, update information
        // Extract other parameters you want to update
		$name = isset($_POST["name"]) ? $_POST["name"] : "Default Server Name";
        $users = isset($_POST["users"]) ? (int)$_POST["users"] : 0;
        $max = isset($_POST["max"]) ? (int)$_POST["max"] : 0;
        $ispublic = isset($_POST["public"]) ? filter_var($_POST["public"], FILTER_VALIDATE_BOOLEAN) : false;
        $port = isset($_POST["port"]) ? (int)$_POST["port"] : 0;
        $version = isset($_POST["version"]) ? (int)$_POST["version"] : 0;
        $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : null;

        // Update the server information
        $update_stmt = $conn->prepare("UPDATE server_data SET name = ?, users = ?, max = ?, is_public = ?, port = ?, version = ?, uuid = ? WHERE IP = ?");
        $update_stmt->bind_param("siiiiiss", $name, $users, $max, $ispublic, $port, $version, $uuid, $ip_address);
        $update_stmt->execute();

        // Retrieve the salt from the database
        $salt_query = $conn->prepare("SELECT salt FROM server_data WHERE IP = ?");
        $salt_query->bind_param("s", $ip_address);
        $salt_query->execute();
        $salt_result = $salt_query->get_result();
        $row = $salt_result->fetch_assoc();
        $salt = $row['salt'];

        echo "Your Server is connected to databse with the ID: ", $salt;
    } else {
        // Server doesn't exist, insert new row
        $name = isset($_POST["name"]) ? $_POST["name"] : "Default Server Name";
        $users = isset($_POST["users"]) ? (int)$_POST["users"] : 0;
        $max = isset($_POST["max"]) ? (int)$_POST["max"] : 0;
        $ispublic = isset($_POST["public"]) ? filter_var($_POST["public"], FILTER_VALIDATE_BOOLEAN) : false;
        $port = isset($_POST["port"]) ? (int)$_POST["port"] : 0;
        $version = isset($_POST["version"]) ? (int)$_POST["version"] : 0;
        $uuid = isset($_POST["uuid"]) ? $_POST["uuid"] : null;
        
        $insert_stmt = $conn->prepare("INSERT INTO server_data (IP, salt, name, users, max, is_public, port, version, uuid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssiiiii", $ip_address, $salt, $name, $users, $max, $ispublic, $port, $version, $uuid);
        $insert_stmt->execute();

        echo "New server added with IP address: ", $ip_address, " and salt: ", $salt;
    }

    // Close the statements
    if ($existing_server_stmt) {
        $existing_server_stmt->close();
    }
    if ($update_stmt) {
        $update_stmt->close();
    }
    if ($insert_stmt) {
        $insert_stmt->close();
    }
    if ($salt_query) {
        $salt_query->close();
    }

    // Close the connection
    $conn->close();
} else {
    echo "Invalid request.";
}

// Function to generate a new salt (you can modify this method as needed)
function generateSalt() {
    return uniqid(); // This is just a simple example; use a more secure method for generating salts
}
?>
