<?php

$sName = "127.0.0.1";
$uName = "root";
$pass  = "";
$db_name = "task_management_db";
$port = 3307; // phpMyAdmin shows MySQL is running on port 3307

try {
	$dsn = "mysql:host=$sName;port=$port;dbname=$db_name;charset=utf8mb4";
	$conn = new PDO($dsn, $uName, $pass, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
	exit;
}