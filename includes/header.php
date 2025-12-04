<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Study Planner</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php if(isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand brand-font fs-3" href="index.php">
                <i class="fa-solid fa-book-open me-2 text-brown"></i>StudyPlanner
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <span class="text-muted">Halo, <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong></span>
                    </li>
                    <li class="nav-item">
                        <a href="api/auth.php?logout=true" class="btn btn-outline-danger btn-sm rounded-pill px-4">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="container mt-4 mb-5">