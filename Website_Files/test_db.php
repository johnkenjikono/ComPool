<?php
require 'db_connect.php';
//Testing to see if db is succesfuly connected
if ($db) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed: " . $db->connect_error;
}
?>
