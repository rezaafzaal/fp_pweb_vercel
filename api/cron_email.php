<?php
// api/cron_email.php
require_once '../config/db.php';

// Load PHPMailer (Sesuaikan path jika kamu pakai manual download)
// require '../vendor/autoload.php'; 
// use PHPMailer\PHPMailer\PHPMailer;

// Untuk demo, kita gunakan echo dulu agar bisa dites di browser.
// Nanti aktifkan SMTP Gmail untuk production.

$today = date('Y-m-d');
$h_min_3 = date('Y-m-d', strtotime($today . ' + 3 days'));

// Cari tugas yang deadline-nya 3 hari lagi DAN masih ongoing
$sql = "SELECT t.*, u.email, u.name 
        FROM tasks t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.deadline = ? AND t.status = 'ongoing'";

$stmt = $pdo->prepare($sql);
$stmt->execute([$h_min_3]);
$tasks = $stmt->fetchAll();

if (count($tasks) > 0) {
    foreach ($tasks as $task) {
        $to = $task['email'];
        $subject = "Reminder: Tugas H-3 Deadline!";
        $message = "Halo " . $task['name'] . ",\n\n";
        $message .= "Tugas '" . $task['task_name'] . "' akan tenggat waktu pada " . $task['deadline'] . ".\n";
        $message .= "Segera selesaikan ya!\n\nSalam,\nStudyPlanner Bot";
        
        // Kirim Email (Gunakan mail() bawaan PHP atau PHPMailer)
        // Note: Vercel serverless sering memblokir port 25 default mail()
        // Kamu WAJIB pake SMTP Relay (Gmail/SendGrid) dengan PHPMailer untuk production.
        
        // Simulasi Log
        echo "Mengirim email ke: $to untuk tugas: " . $task['task_name'] . "<br>";
    }
} else {
    echo "Tidak ada tugas H-3 hari ini.";
}
?>