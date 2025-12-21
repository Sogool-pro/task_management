<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'employee') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require 'DB_connection.php';

$user_id = $_SESSION['id'];

// Check if there is already an open attendance (no time_out yet)
$sql = "SELECT id FROM attendance WHERE user_id = ? AND time_out IS NULL ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    echo json_encode(['status' => 'error', 'message' => 'Already timed in']);
    exit;
}

// Create new time-in record
$sql = "INSERT INTO attendance (user_id, time_in) VALUES (?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$attendance_id = $conn->lastInsertId();

echo json_encode([
    'status' => 'success',
    'attendance_id' => $attendance_id
]);

