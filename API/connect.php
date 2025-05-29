<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "table_editing1";
$conn = new mysqli($servername, $username, $password, $db_name);
?>