<?php require_once 'includes/header.php'; ?>
<?php require_once 'config/db.php'; ?>
<?php 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Trigger cek rutinan setiap buka dashboard agar update
include 'api/check_routine.php';

$user_id = $_SESSION['user_id'];

// Ambil Data Ongoing
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND status = 'ongoing' ORDER BY deadline ASC, urgency DESC");
$stmt->execute([$user_id]);
$ongoing_tasks = $stmt->fetchAll();

// Ambil Data Done
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND status = 'done' ORDER BY deadline DESC");
$stmt->execute([$user_id]);
$done_tasks = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h2 class="brand-font">Dashboard Tugas</h2>
        <p class="text-muted">Kelola produktivitasmu hari ini</p>
        <button class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            <i class="fas fa-plus me-2"></i>Tambah Tugas Baru
        </button>
    </div>
</div>

<!-- Tabs Navigasi -->
<ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4" id="pills-ongoing-tab" data-bs-toggle="pill" data-bs-target="#pills-ongoing" type="button" role="tab">Ongoing (<?= count($ongoing_tasks) ?>)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" id="pills-done-tab" data-bs-toggle="pill" data-bs-target="#pills-done" type="button" role="tab">Selesai (<?= count($done_tasks) ?>)</button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">
    <!-- ONGOING TASKS -->
    <div class="tab-pane fade show active" id="pills-ongoing" role="tabpanel">
        <div class="row">
            <?php if(empty($ongoing_tasks)): ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-3 text-secondary" style="opacity: 0.5;">
                        <i class="fas fa-clipboard-list fa-5x"></i>
                    </div>
                    <h5 class="text-muted">Wah, masih nganggur nih!</h5>
                </div>
            <?php endif; ?>

            <?php foreach($ongoing_tasks as $task): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card card-custom h-100 position-relative">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge rounded-pill badge-urgency-<?= $task['urgency'] ?> text-capitalize px-3 py-2">
                                <?= $task['urgency'] ?> Priority
                            </span>
                            <?php if($task['is_routine']): ?>
                                <span class="badge bg-info text-white" title="Tugas Rutinan"><i class="fas fa-sync-alt"></i></span>
                            <?php endif; ?>
                        </div>
                        
                        <h5 class="card-title mt-3"><?= htmlspecialchars($task['task_name']) ?></h5>
                        
                        <div class="text-muted small mb-3">
                            <i class="far fa-calendar-alt me-1"></i> Deadline: 
                            <span class="<?= (strtotime($task['deadline']) < time()) ? 'text-danger fw-bold' : '' ?>">
                                <?= date('d M Y', strtotime($task['deadline'])) ?>
                            </span>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="api/task_action.php?toggle_id=<?= $task['id'] ?>" class="btn btn-outline-success btn-sm w-100 rounded-pill">
                                <i class="fas fa-check me-1"></i> Selesai
                            </a>
                            <a href="api/task_action.php?delete_id=<?= $task['id'] ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- DONE TASKS -->
    <div class="tab-pane fade" id="pills-done" role="tabpanel">
        <div class="row">
            <?php foreach($done_tasks as $task): ?>
            <div class="col-12 mb-3">
                <div class="card card-custom p-3 d-flex flex-row align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle p-2 me-3">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 task-done-text"><?= htmlspecialchars($task['task_name']) ?></h5>
                            <small class="text-muted">Selesai pada: <?= date('d M Y', strtotime($task['deadline'])) ?></small>
                        </div>
                    </div>
                    <a href="api/task_action.php?delete_id=<?= $task['id'] ?>" class="text-danger"><i class="fas fa-trash"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Tambah Tugas -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title brand-font">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="api/task_action.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Tugas</label>
                        <input type="text" name="task_name" class="form-control" required placeholder="Contoh: Mengerjakan Laporan Web">
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Prioritas</label>
                            <select name="urgency" class="form-select form-control">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" name="deadline" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 bg-light p-3 rounded-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isRoutineCheck" name="is_routine">
                            <label class="form-check-label" for="isRoutineCheck">Jadikan Tugas Rutinan?</label>
                        </div>
                        <div id="routineOptions" class="mt-3 d-none">
                            <label class="form-label small text-muted">Ulangi setiap (hari):</label>
                            <input type="number" name="routine_interval" class="form-control" min="1" placeholder="Misal: 7 (Seminggu sekali)">
                        </div>
                    </div>

                    <button type="submit" name="add_task" class="btn btn-primary-custom w-100">Simpan Tugas</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Simple Script untuk Modal Rutinan -->
<script>
    document.getElementById('isRoutineCheck').addEventListener('change', function() {
        const options = document.getElementById('routineOptions');
        if(this.checked) {
            options.classList.remove('d-none');
        } else {
            options.classList.add('d-none');
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>

