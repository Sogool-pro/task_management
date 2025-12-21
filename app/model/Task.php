<?php

/* ---------------------------------------------
   INSERT, UPDATE, DELETE TASKS
--------------------------------------------- */

function insert_task($conn, $data){
    // $data can be: [$title, $description, $assigned_to, $due_date] or [$title, $description, $assigned_to, $due_date, $template_file]
    // Check if template_file column exists
    $has_template_file_column = false;
    try {
        $check_sql = "SHOW COLUMNS FROM tasks LIKE 'template_file'";
        $check_stmt = $conn->query($check_sql);
        $has_template_file_column = $check_stmt->rowCount() > 0;
    } catch (Exception $e) {
        $has_template_file_column = false;
    }
    
    if ($has_template_file_column && count($data) >= 5 && isset($data[4])) {
        // Insert with template_file
        $sql = "INSERT INTO tasks (title, description, assigned_to, due_date, template_file) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    } else {
        // Insert without template_file (backward compatible)
        $sql = "INSERT INTO tasks (title, description, assigned_to, due_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data[0], $data[1], $data[2], $data[3]]);
    }
}

function update_task($conn, $data){
    // Admin update: can also change status and review comment, and records reviewer + time
    // $data = [$title, $description, $assigned_to, $due_date, $status, $review_comment, $reviewed_by, $id] 
    // or [$title, $description, $assigned_to, $due_date, $status, $review_comment, $reviewed_by, $template_file, $id]
    
    // Check if template_file column exists
    $has_template_file_column = false;
    try {
        $check_sql = "SHOW COLUMNS FROM tasks LIKE 'template_file'";
        $check_stmt = $conn->query($check_sql);
        $has_template_file_column = $check_stmt->rowCount() > 0;
    } catch (Exception $e) {
        $has_template_file_column = false;
    }
    
    if ($has_template_file_column && count($data) >= 9) {
        // Update with template_file (can be null to keep existing or new value)
        $sql = "UPDATE tasks 
                   SET title=?, 
                       description=?, 
                       assigned_to=?, 
                       due_date=?, 
                       status=?, 
                       review_comment=?, 
                       reviewed_by=?, 
                       reviewed_at=NOW(),
                       template_file=?
                     WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    } else {
        // Update without template_file (backward compatible)
        $sql = "UPDATE tasks 
                   SET title=?, 
                       description=?, 
                       assigned_to=?, 
                       due_date=?, 
                       status=?, 
                       review_comment=?, 
                       reviewed_by=?, 
                       reviewed_at=NOW() 
                     WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
    }
}

function update_task_status($conn, $data){
    $sql = "UPDATE tasks SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

// When an employee submits their work/file, we store the file path and
// mark the task as "in_progress" so the admin knows there is something to review.
// $data = [$submission_file, $id]
function update_task_submission($conn, $data){
    $sql = "UPDATE tasks 
               SET submission_file=?, 
                   status='in_progress',
                   review_comment=NULL,
                   reviewed_by=NULL,
                   reviewed_at=NULL
             WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function delete_task($conn, $data){
    $sql = "DELETE FROM tasks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function unassign_completed_tasks($conn, $user_id){
    // Set assigned_to to NULL for completed tasks before user deletion
    $sql = "UPDATE tasks SET assigned_to = NULL WHERE assigned_to = ? AND status = 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->rowCount();
}

/* ---------------------------------------------
   SINGLE & ALL TASK FETCHING
--------------------------------------------- */

function get_task_by_id($conn, $id){
    $sql = "SELECT * FROM tasks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : 0;
}

function get_task_by_title($conn, $title){
    // Find task by matching title (for notifications that don't have task_id)
    $sql = "SELECT * FROM tasks WHERE title=? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title]);
    return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : 0;
}

function get_all_tasks($conn){
    $sql = "SELECT * FROM tasks ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}


/* ---------------------------------------------
   DUE TODAY TASKS
--------------------------------------------- */

function get_all_tasks_due_today($conn){
    $sql = "SELECT * FROM tasks WHERE due_date = CURDATE() AND status != 'completed' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function count_tasks_due_today($conn){
    $sql = "SELECT id FROM tasks WHERE due_date = CURDATE() AND status != 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount();
}

/* ---------------------------------------------
   OVERDUE TASKS
--------------------------------------------- */

function get_all_tasks_overdue($conn){
    $sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function count_tasks_overdue($conn){
    $sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount();
}

/* ---------------------------------------------
   NO DEADLINE TASKS
--------------------------------------------- */

function get_all_tasks_NoDeadline($conn){
    $sql = "SELECT * FROM tasks WHERE (due_date IS NULL OR due_date = '0000-00-00') AND status != 'completed' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function count_tasks_NoDeadline($conn){
    $sql = "SELECT id FROM tasks WHERE (due_date IS NULL OR due_date = '0000-00-00') AND status != 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount();
}

/* ---------------------------------------------
   STATUS-BASED TASKS
--------------------------------------------- */

function get_all_tasks_pending($conn){
    $sql = "SELECT * FROM tasks WHERE status = 'Pending' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
}

function count_pending_tasks($conn){
    $sql = "SELECT id FROM tasks WHERE status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount();
}

function get_all_tasks_in_progress($conn){
    $sql = "SELECT * FROM tasks WHERE status = 'in_progress' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
}

function count_in_progress_tasks($conn){
    $sql = "SELECT id FROM tasks WHERE status = 'in_progress'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount();
}

function get_all_tasks_completed($conn){
    $sql = "SELECT * FROM tasks WHERE status = 'Completed' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
}

function count_completed_tasks($conn){
    $sql = "SELECT id FROM tasks WHERE status = 'completed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([]);
    return $stmt->rowCount();
}

/* ---------------------------------------------
   USER-SPECIFIC TASK COUNTS
--------------------------------------------- */

function count_my_tasks($conn, $id){
    $sql = "SELECT id FROM tasks WHERE assigned_to=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount();
}

function count_my_tasks_overdue($conn, $id){
    $sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND assigned_to=? AND due_date != '0000-00-00'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount();
}

function count_my_tasks_NoDeadline($conn, $id){
    $sql = "SELECT id FROM tasks WHERE assigned_to=? AND ((due_date IS NULL OR due_date = '0000-00-00') AND status != 'completed')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount();
}

function count_my_pending_tasks($conn, $id){
    $sql = "SELECT id FROM tasks WHERE status = 'pending' AND assigned_to=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount();
}

function count_my_in_progress_tasks($conn, $id){
    $sql = "SELECT id FROM tasks WHERE status = 'in_progress' AND assigned_to=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount();
}

function count_my_completed_tasks($conn, $id){
    $sql = "SELECT id FROM tasks WHERE status = 'completed' AND assigned_to=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount();
}

/* ---------------------------------------------
   USER TASK RETRIEVAL
--------------------------------------------- */

function get_all_tasks_by_id($conn, $id){
    $sql = "SELECT * FROM tasks WHERE assigned_to=? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function get_my_tasks_overdue($conn, $id){
    $sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND assigned_to=? AND due_date != '0000-00-00' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function get_my_tasks_NoDeadline($conn, $id){
    $sql = "SELECT * FROM tasks WHERE assigned_to=? AND ((due_date IS NULL OR due_date = '0000-00-00') AND status != 'completed') ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function get_my_tasks_pending($conn, $id){
    $sql = "SELECT * FROM tasks WHERE status = 'pending' AND assigned_to=? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function get_my_tasks_in_progress($conn, $id){
    $sql = "SELECT * FROM tasks WHERE status = 'in_progress' AND assigned_to=? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

function get_my_tasks_completed($conn, $id){
    $sql = "SELECT * FROM tasks WHERE status = 'completed' AND assigned_to=? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : 0;
}

/* ---------------------------------------------
   ALL TASK COUNT
--------------------------------------------- */
function count_tasks($conn){
    $sql = "SELECT id FROM tasks";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

