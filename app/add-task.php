<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && $_SESSION['role'] == 'admin' && isset($_POST['due_date'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$title = validate_input($_POST['title']);
	$description = validate_input($_POST['description']);
	$assigned_to = validate_input($_POST['assigned_to']);
	$due_date = validate_input($_POST['due_date']);

	if (empty($title)) {
		$em = "Title is required";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else if (empty($description)) {
		$em = "Description is required";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else if ($assigned_to == 0) {
		$em = "Select User";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}else {
    
       include "Model/Task.php";
       include "Model/Notification.php";

       // Handle template file upload (optional)
       $template_file_path = null;
       if (isset($_FILES['template_file']) && $_FILES['template_file']['error'] == UPLOAD_ERR_OK) {
           $file = $_FILES['template_file'];
           
           // Validate file type
           $allowed_extensions = ['pdf','doc','docx','xls','xlsx','png','jpg','jpeg','zip','txt'];
           $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
           
           if (!in_array($file_ext, $allowed_extensions)) {
               $em = "Invalid template file type. Allowed: pdf, doc, docx, xls, xlsx, png, jpg, jpeg, zip, txt.";
               header("Location: ../create_task.php?error=$em");
               exit();
           }
           
           // Max 10MB
           if ($file['size'] > 10 * 1024 * 1024) {
               $em = "Template file is too large. Maximum allowed size is 10MB.";
               header("Location: ../create_task.php?error=$em");
               exit();
           }
           
           // Ensure uploads directory exists
           $upload_dir = "../uploads";
           if (!is_dir($upload_dir)) {
               mkdir($upload_dir, 0777, true);
           }
           
           // Generate unique filename
           $new_filename = "template_" . time() . "_" . basename($file['name']);
           $destination = $upload_dir . "/" . $new_filename;
           
           if (move_uploaded_file($file['tmp_name'], $destination)) {
               $template_file_path = "uploads/" . $new_filename;
           } else {
               $em = "Failed to upload template file. Please try again.";
               header("Location: ../create_task.php?error=$em");
               exit();
           }
       }

       $data = array($title, $description, $assigned_to, $due_date, $template_file_path);
       insert_task($conn, $data);

       // Get the task ID that was just inserted
       $stmt = $conn->prepare("SELECT id FROM tasks WHERE title=? AND assigned_to=? ORDER BY id DESC LIMIT 1");
       $stmt->execute([$title, $assigned_to]);
       $task = $stmt->fetch(PDO::FETCH_ASSOC);
       $task_id = $task ? $task['id'] : null;

       $notif_data = array("'$title' has been assigned to you. Please review and start working on it", $assigned_to, 'New Task Assigned', $task_id);
       insert_notification($conn, $notif_data);


       $em = "Task created successfully";
	    header("Location: ../create_task.php?success=$em");
	    exit();

    
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../create_task.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../create_task.php?error=$em");
   exit();
}