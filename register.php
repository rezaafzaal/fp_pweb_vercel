<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card card-custom p-4">
            <div class="card-body text-center">
                <h2 class="brand-font mb-3">Buat Akun Baru</h2>
                <p class="text-muted mb-4">Mulai perjalanan produktifmu hari ini</p>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>

                <form action="api/auth.php" method="POST" class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary-custom w-100 mb-3">Daftar Sekarang</button>
                    <div class="text-center">
                        <small>Sudah punya akun? <a href="login.php" class="text-brown fw-bold">Login disini</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>