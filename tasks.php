<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    $text = "All Task";

    // ---- Due Date Filters ----
    if (isset($_GET['due_date']) && $_GET['due_date'] == "Due Today") {
        $text = "Due Today";
        $tasks = get_all_tasks_due_today($conn);
        $num_task = count_tasks_due_today($conn);

    } else if (isset($_GET['due_date']) && $_GET['due_date'] == "Overdue") {
        $text = "Overdue";
        $tasks = get_all_tasks_overdue($conn);
        $num_task = count_tasks_overdue($conn);

    } else if (isset($_GET['due_date']) && $_GET['due_date'] == "No Deadline") {
        $text = "No Deadline";
        $tasks = get_all_tasks_NoDeadline($conn);
        $num_task = count_tasks_NoDeadline($conn);

    // ---- Status Filters ----
    } else if (isset($_GET['status']) && $_GET['status'] == "Pending") {
        $text = "Pending";
        $tasks = get_all_tasks_pending($conn);
        $num_task = count_pending_tasks($conn);

    } else if (isset($_GET['status']) && $_GET['status'] == "in_progress") {
        $text = "in_progress";
        $tasks = get_all_tasks_in_progress($conn);
        $num_task = count_in_progress_tasks($conn);

    } else if (isset($_GET['status']) && $_GET['status'] == "Completed") {
        $text = "Completed";
        $tasks = get_all_tasks_completed($conn);
        $num_task = count_completed_tasks($conn);

    } else {
        $tasks = get_all_tasks($conn);
        $num_task = count_tasks($conn);
    }

    $users = get_all_users($conn);
    
    // Calculate completion statistics for progress bar
    $total_tasks = count_tasks($conn);
    $completed_tasks = count_completed_tasks($conn);
    $completion_percentage = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <div class="task-header-section">
                <h4 class="title-2"><?=$text?> (<?=$num_task?>)</h4>
                <div class="task-header-actions">
                    <a href="create_task.php" class="btn create-task-btn">Create Task</a>
                </div>
            </div>
            
            <div class="task-filters-container">
                <div class="filter-buttons">
                    <a href="tasks.php?due_date=Due Today" class="filter-btn <?= (isset($_GET['due_date']) && $_GET['due_date'] == 'Due Today') ? 'active' : '' ?>">Due Today</a>
                    <a href="tasks.php?due_date=Overdue" class="filter-btn <?= (isset($_GET['due_date']) && $_GET['due_date'] == 'Overdue') ? 'active' : '' ?>">Overdue</a>
                    <a href="tasks.php?due_date=No Deadline" class="filter-btn <?= (isset($_GET['due_date']) && $_GET['due_date'] == 'No Deadline') ? 'active' : '' ?>">No Deadline</a>
                    <a href="tasks.php?status=Pending" class="filter-btn <?= (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'active' : '' ?>">Pending</a>
                    <a href="tasks.php?status=in_progress" class="filter-btn <?= (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'active' : '' ?>">In Progress</a>
                    <a href="tasks.php?status=Completed" class="filter-btn <?= (isset($_GET['status']) && $_GET['status'] == 'Completed') ? 'active' : '' ?>">Completed</a>
                    <a href="tasks.php" class="filter-btn <?= (!isset($_GET['status']) && !isset($_GET['due_date'])) ? 'active' : '' ?>">All Tasks</a>
                </div>
            </div>
            
            <!-- Task Completion Progress Bar -->
            <?php if (!isset($_GET['status']) && !isset($_GET['due_date'])) { ?>
            <div class="task-progress-container">
                <div class="task-progress-header">
                    <span class="task-progress-label">Overall Task Completion</span>
                    <span class="task-progress-percentage"><?=$completion_percentage?>%</span>
                </div>
                <div class="task-progress-bar-wrapper">
                    <div class="task-progress-bar" style="width: <?=$completion_percentage?>%">
                        <span class="progress-bar-fill"></span>
                    </div>
                </div>
                <div class="task-progress-stats">
                    <span><?=$completed_tasks?> of <?=$total_tasks?> tasks completed</span>
                </div>
            </div>
            <?php } ?>


            <?php if ($tasks != 0) { ?>
                <table class="main-table">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php $i = 0; foreach ($tasks as $task) { ?>
                        <tr>
                            <td><?= ++$i ?></td>
                            <td><?= $task['title'] ?></td>
                            <td><?= $task['description'] ?></td>
                            <td>
                                <?php 
                                foreach ($users as $user) {
                                    if ($user['id'] == $task['assigned_to']) {
                                        echo $user['full_name'];
                                    }
                                }
                                ?>
                            </td>
                            <td><?= $task['due_date'] ?: 'No Deadline' ?></td>
                            <td><?= $task['status'] ?></td>
                            <td>
                                <a href="edit-task.php?id=<?= $task['id'] ?>" class="edit-btn">Edit</a>
                                <a href="delete-task.php?id=<?= $task['id'] ?>" class="delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <h3>Empty</h3>
            <?php } ?>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php include 'inc/modals.php'; ?>

    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(4)");
        active.classList.add("active");
    </script>
</body>
</html>
<?php 
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
