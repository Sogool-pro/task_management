<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'employee') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require 'DB_connection.php';

$user_id = $_SESSION['id'];

// Check if there is an open attendance (no time_out yet)
$sql = "SELECT id FROM attendance WHERE user_id = ? AND time_out IS NULL ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

if ($attendance) {
    echo json_encode([
        'status' => 'success',
        'has_active_attendance' => true,
        'attendance_id' => $attendance['id']
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'has_active_attendance' => false
    ]);
}

