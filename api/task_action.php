<?php
// api/task_action.php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

// TAMBAH TUGAS
if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name'];
    $urgency = $_POST['urgency'];
    $deadline = $_POST['deadline'];
    $is_routine = isset($_POST['is_routine']) ? 1 : 0;
    $routine_interval = $is_routine ? $_POST['routine_interval'] : 0;
    $last_generated = $is_routine ? date('Y-m-d') : NULL;

    $sql = "INSERT INTO tasks (user_id, task_name, urgency, deadline, is_routine, routine_interval, last_generated) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $task_name, $urgency, $deadline, $is_routine, $routine_interval, $last_generated]);
    
    header("Location: ../index.php?success=Tugas ditambahkan");
}

// UPDATE STATUS (DONE/UNDO)
if (isset($_GET['toggle_id'])) {
    $id = $_GET['toggle_id'];
    // Cek status dulu
    $stmt = $pdo->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $task = $stmt->fetch();
    
    if ($task) {
        $new_status = ($task['status'] == 'ongoing') ? 'done' : 'ongoing';
        $update = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $update->execute([$new_status, $id]);
    }
    header("Location: ../index.php");
}

// DELETE TUGAS
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    header("Location: ../index.php?success=Tugas dihapus");
}
?>