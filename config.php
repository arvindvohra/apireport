<?php 
session_start();
$servername = "terraform-20200330104350448200000004.cdph73l9uiyc.ap-southeast-2.rds.amazonaws.com";
$username = "mytiapp";
$password = "0kMk2HqADi11";
$dbname = "myti";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
define("API_KEY","enggnslkfjsdjkfal456556476");
?>
