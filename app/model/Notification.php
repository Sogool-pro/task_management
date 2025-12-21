<?php  

function get_all_my_notifications($conn, $id){
	$sql = "SELECT * FROM notifications WHERE recipient=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$notifications = $stmt->fetchAll();
	}else $notifications = 0;

	return $notifications;
}


function count_notification($conn, $id){
	$sql = "SELECT id FROM notifications WHERE recipient=? AND is_read=0";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function insert_notification($conn, $data){
	// Automatically set the current date when inserting a notification
	// $data should be: [$message, $recipient, $type] or [$message, $recipient, $type, $task_id]
	
	// Check if task_id column exists in the table
	$has_task_id_column = false;
	try {
		$check_sql = "SHOW COLUMNS FROM notifications LIKE 'task_id'";
		$check_stmt = $conn->query($check_sql);
		$has_task_id_column = $check_stmt->rowCount() > 0;
	} catch (Exception $e) {
		$has_task_id_column = false;
	}
	
	// Check if task_id is provided
	$task_id = (count($data) >= 4 && isset($data[3])) ? $data[3] : null;
	
	if ($has_task_id_column && $task_id !== null) {
		// Insert with task_id
		$sql = "INSERT INTO notifications (message, recipient, type, date, task_id) VALUES(?,?,?,CURDATE(),?)";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$data[0], $data[1], $data[2], $task_id]);
	} else if ($has_task_id_column) {
		// Insert with NULL task_id
		$sql = "INSERT INTO notifications (message, recipient, type, date, task_id) VALUES(?,?,?,CURDATE(),NULL)";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$data[0], $data[1], $data[2]]);
	} else {
		// Old format without task_id column
		$sql = "INSERT INTO notifications (message, recipient, type, date) VALUES(?,?,?,CURDATE())";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$data[0], $data[1], $data[2]]);
	}
}

function notification_make_read($conn, $recipient_id, $notification_id){
	$sql = "UPDATE notifications SET is_read=1 WHERE id=? AND recipient=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$notification_id, $recipient_id]);
}