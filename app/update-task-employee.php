<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['id']) && $_SESSION['role'] == 'employee') {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$id = validate_input($_POST['id']);

    // Handle file upload (submission_file)
    if (!isset($_FILES['submission_file']) || $_FILES['submission_file']['error'] == UPLOAD_ERR_NO_FILE) {
        $em = "Please attach a file before submitting.";
	    header("Location: ../edit-task-employee.php?error=$em&id=$id");
	    exit();
    }

    $file = $_FILES['submission_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $em = "File upload failed. Please try again.";
	    header("Location: ../edit-task-employee.php?error=$em&id=$id");
	    exit();
    }

    $allowed_extensions = ['pdf','doc','docx','xls','xlsx','png','jpg','jpeg','zip'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        $em = "Invalid file type. Allowed: pdf, doc, docx, xls, xlsx, png, jpg, jpeg, zip.";
	    header("Location: ../edit-task-employee.php?error=$em&id=$id");
	    exit();
    }

    // Max 10MB
    if ($file['size'] > 10 * 1024 * 1024) {
        $em = "File is too large. Maximum allowed size is 10MB.";
	    header("Location: ../edit-task-employee.php?error=$em&id=$id");
	    exit();
    }

    // Ensure uploads directory exists
    $upload_dir = "../uploads";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $new_filename = "task_" . $id . "_" . time() . "." . $file_ext;
    $destination = $upload_dir . "/" . $new_filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $em = "Failed to save the uploaded file.";
	    header("Location: ../edit-task-employee.php?error=$em&id=$id");
	    exit();
    }

    include "Model/Task.php";
    include "Model/Notification.php";
    include "Model/User.php";

    // Store relative path so it works from web root
    $relative_path = "uploads/" . $new_filename;
    $data = array($relative_path, $id);
    update_task_submission($conn, $data);

    // Notify all admins that this task has a new submission
    $task = get_task_by_id($conn, $id);
    $employee = get_user_by_id($conn, $_SESSION['id']);

    if ($task != 0 && $employee != 0) {
        $task_title = $task['title'];
        $employee_name = $employee['full_name'];

        // Fetch all admins
        $stmtAdmins = $conn->prepare("SELECT id FROM users WHERE role='admin'");
        $stmtAdmins->execute();
        $admins = $stmtAdmins->fetchAll(PDO::FETCH_ASSOC);

        if ($admins) {
            foreach ($admins as $admin) {
                $message = "'$task_title' has a new submission from $employee_name. Please review the uploaded file.";
                $notif_data = array($message, $admin['id'], 'Task Submitted', $id);
                insert_notification($conn, $notif_data);
            }
        }
    }

    $em = "File submitted successfully. Waiting for admin review.";
	header("Location: ../edit-task-employee.php?success=$em&id=$id");
	exit();
}else {
   $em = "Unknown error occurred";
   header("Location: ../edit-task-employee.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit();
}