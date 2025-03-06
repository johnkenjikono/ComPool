<?php
    //Connecting to our database
    $host = "localhost"; 
    $user = "root"; 
    $password = ""; 
    $database = "app-db";

    // Establish the connection
    $db = new mysqli($host, $user, $password, $database);

    // Check for connection errors
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

//any file that needs connection use this: require 'db_connect.php'
?>
