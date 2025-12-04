<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card card-custom p-4">
            <div class="card-body text-center">
                <h2 class="brand-font mb-3">Selamat Datang</h2>
                <p class="text-muted mb-4">Silakan masuk untuk mengelola tugasmu</p>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                <?php endif; ?>

                <form action="api/auth.php" method="POST" class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary-custom w-100 mb-3">Login</button>
                    <div class="text-center">
                        <small>Belum punya akun? <a href="register.php" class="text-brown fw-bold">Daftar disini</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>