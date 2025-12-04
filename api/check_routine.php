<?php
// api/check_routine.php
// Pastikan $pdo dan session user_id tersedia

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $today = date('Y-m-d');

    // Ambil tugas rutinan user ini
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND is_routine = 1");
    $stmt->execute([$uid]);
    $routines = $stmt->fetchAll();

    foreach ($routines as $task) {
        // Kapan terakhir digenerate? Jika null, anggap created_at
        $last_gen = $task['last_generated'] ?? date('Y-m-d', strtotime($task['created_at']));
        $interval = $task['routine_interval'];
        
        // Hitung target tanggal generate berikutnya
        $next_due_date = date('Y-m-d', strtotime($last_gen . " + $interval days"));

        // Jika hari ini sudah melewati atau sama dengan tanggal jadwal berikutnya
        if ($today >= $next_due_date) {
            // Buat deadline baru (misal deadline = hari ini + interval juga, atau sesuai selera)
            $new_deadline = date('Y-m-d', strtotime($today . " + $interval days"));
            
            // 1. Insert Tugas Baru ke Ongoing
            $insert = $pdo->prepare("INSERT INTO tasks (user_id, task_name, status, urgency, deadline, is_routine, routine_interval, last_generated) VALUES (?, ?, 'ongoing', ?, ?, 0, 0, NULL)");
            // Tugas hasil generate BUKAN parent rutinan (is_routine=0) agar tidak beranak lagi
            $insert->execute([$uid, $task['task_name'] . " (Rutin)", $task['urgency'], $new_deadline]);

            // 2. Update last_generated di tugas MASTER (induknya)
            $update = $pdo->prepare("UPDATE tasks SET last_generated = ? WHERE id = ?");
            $update->execute([$today, $task['id']]);
        }
    }
}
?>