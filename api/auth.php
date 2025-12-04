<?php
// api/auth.php
session_start();
require_once '../config/db.php';

// REGISTER
if (isset($_POST['register'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        header("Location: ../register.php?error=Email tidak valid");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password]);
        header("Location: ../login.php?success=Registrasi berhasil, silakan login");
    } catch (PDOException $e) {
        header("Location: ../register.php?error=Email sudah digunakan");
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        // TRIGGER CEK RUTINAN SAAT LOGIN
        include 'check_routine.php'; // File ini akan kita buat di bawah
        
        header("Location: ../index.php");
    } else {
        header("Location: ../login.php?error=Email atau password salah");
    }
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php");
}
?>