<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
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
			<h4 class="title">Edit Task <a href="tasks.php">Tasks</a></h4>
			<form class="form-1"
			      method="POST"
			      enctype="multipart/form-data"
			      action="app/update-task.php">
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
					<lable>Title</lable>
					<input type="text" name="title" class="input-1" placeholder="Full Name" value="<?=$task['title']?>"><br>
				</div>
				<div class="input-holder">
					<lable>Description</lable>
					<textarea name="description" rows="5" class="input-1" ><?=$task['description']?></textarea><br>
				</div>
				<div class="input-holder">
					<lable>Snooze</lable>
					<input type="date" name="due_date" class="input-1" placeholder="Snooze" value="<?=$task['due_date']?>"><br>
				</div>
				
            <div class="input-holder">
					<lable>Assigned to</lable>
					<select name="assigned_to" class="input-1">
						<option value="0">Select employee</option>
						<?php if ($users !=0) { 
							foreach ($users as $user) {
								if ($task['assigned_to'] == $user['id']) { ?>
									<option selected value="<?=$user['id']?>"><?=$user['full_name']?></option>
						<?php }else{ ?>
                  <option value="<?=$user['id']?>"><?=$user['full_name']?></option>
						<?php } } } ?>
					</select><br>
				</div>

            <?php if (!empty($task['template_file'])) { ?>
            <div class="input-holder">
					<lable>Template/Guide File</lable>
					<p>
						<a href="<?=$task['template_file']?>" target="_blank" style="color: #007bff; text-decoration: none;">
							<i class="fa fa-download"></i> View Current Template/Guide
						</a>
					</p>
					<small style="color: #666; font-size: 12px;">Upload a new file to replace the existing template</small>
					<input type="file" name="template_file" class="input-1" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt">
				</div>
            <?php } else { ?>
            <div class="input-holder">
					<lable>Template/Guide File (Optional)</lable>
					<input type="file" name="template_file" class="input-1" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.zip,.txt">
					<small style="color: #666; font-size: 12px;">Upload templates, guides, or reference materials for the employee</small>
				</div>
            <?php } ?>

            <?php if (!empty($task['submission_file'])) { ?>
            <div class="input-holder">
					<lable>Submitted File</lable>
					<p><a href="<?=$task['submission_file']?>" target="_blank">View submission</a></p>
				</div>
            <?php } ?>

            <div class="input-holder">
					<lable>Status</lable>
					<select name="status" class="input-1">
						<option value="pending" <?php if($task['status'] == "pending") echo "selected"; ?>>pending</option>
						<option value="in_progress" <?php if($task['status'] == "in_progress") echo "selected"; ?>>in_progress</option>
						<option value="completed" <?php if($task['status'] == "completed") echo "selected"; ?>>completed</option>
						<option value="rejected" <?php if($task['status'] == "rejected") echo "selected"; ?>>rejected</option>
					</select><br>
				</div>

            <div class="input-holder">
					<lable>Review Comment (visible to employee)</lable>
					<textarea name="review_comment" rows="4" class="input-1"><?=$task['review_comment']?></textarea><br>
				</div>
				<input type="text" name="id" value="<?=$task['id']?>" hidden>

				<button class="edit-btn">Update</button>
			</form>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
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