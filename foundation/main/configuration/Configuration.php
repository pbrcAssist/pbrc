<?php
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');
require_once('Session.php');
require_once('EnvironmentVariable.php');
require_once('DatabaseConfiguration.php');

$db = new DatabaseConfiguration;
$conn = $db->conn;
?>