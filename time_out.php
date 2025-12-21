<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'employee') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require 'DB_connection.php';

$user_id = $_SESSION['id'];

// Find latest open attendance
$sql = "SELECT id FROM attendance WHERE user_id = ? AND time_out IS NULL ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$attendance) {
    echo json_encode(['status' => 'error', 'message' => 'No open attendance']);
    exit;
}

$sql = "UPDATE attendance SET time_out = NOW() WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$attendance['id']]);

echo json_encode(['status' => 'success']);


