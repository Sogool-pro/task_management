<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: tasks.php");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
    	 header("Location: tasks.php");
    	 exit();
    }
   $users = get_all_users($conn);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Task</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Edit Task <a href="my_task.php">Tasks</a></h4>
			<form class="form-1"
			      method="POST"
			      enctype="multipart/form-data"
			      action="app/update-task-employee.php">
			      <?php if (isset($_GET['error'])) {?>
      	  	<div class="danger" role="alert">
			  <?php echo stripcslashes($_GET['error']); ?>
			</div>
      	  <?php } ?>

      	  <?php if (isset($_GET['success'])) {?>
      	  	<div class="success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
      	  <?php } ?>
				<div class="input-holder">
					<lable></lable>
					<p><b>Title: </b><?=$task['title']?></p>
				</div>
            <div class="input-holder">
					<lable></lable>
					<p><b>Description: </b><?=$task['description']?></p>
				</div><br>
            <?php if (!empty($task['template_file'])) { ?>
            <div class="input-holder">
					<lable><b>Template/Guide File:</b></lable>
					<p>
						<a href="<?=$task['template_file']?>" target="_blank" style="color: #007bff; text-decoration: none;">
							<i class="fa fa-download"></i> Download Template/Guide
						</a>
					</p>
				</div>
            <?php } ?>
            <div class="input-holder">
					<lable><b>Status:</b></lable>
					<p><?=$task['status']?></p>
				</div>
            <?php if (!empty($task['review_comment'])) { ?>
            <div class="input-holder">
					<lable><b>Admin Comment:</b></lable>
					<p><?=$task['review_comment']?></p>
				</div>
            <?php } ?>
            
            <?php if ($task['status'] !== 'completed') { ?>
            <div class="input-holder">
					<lable>Submit File</lable>
					<input type="file" name="submission_file" class="input-1" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip">
				</div>
				<input type="text" name="id" value="<?=$task['id']?>" hidden>

				<button class="edit-btn">Update</button>
            <?php } ?>
			</form>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(2)");
	active.classList.add("active");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>