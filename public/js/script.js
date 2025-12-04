/**
 * public/js/script.js
 * Menangani interaksi UI, SweetAlert, dan Logika Modal
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. LOGIKA TOGGLE TUGAS RUTINAN (Di Modal Tambah Tugas)
    // Memindahkan script inline dari index.php ke sini agar lebih rapi
    const routineCheck = document.getElementById('isRoutineCheck');
    const routineOptions = document.getElementById('routineOptions');

    if (routineCheck && routineOptions) {
        routineCheck.addEventListener('change', function() {
            if (this.checked) {
                routineOptions.classList.remove('d-none');
                // Fokus ke input interval saat dicentang
                routineOptions.querySelector('input').focus();
            } else {
                routineOptions.classList.add('d-none');
            }
        });
    }

    // 2. AUTO-HIDE NOTIFIKASI (ALERT)
    // Alert bootstrap akan hilang otomatis setelah 4 detik
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            // Efek fade out menggunakan CSS transition manual atau class bootstrap
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // Hapus dari DOM setelah fade
        }, 4000);
    });

    // 3. SWEETALERT2 UNTUK KONFIRMASI HAPUS
    // Menggantikan confirm() bawaan browser yang jelek
    // Cari semua elemen dengan attribute onclick="return confirm(...)" atau class tertentu
    // Note: Di index.php tadi ada onclick="return confirm...", 
    // script ini akan meng-intercept link tersebut jika kita kasih class 'btn-delete'
    
    // Agar script ini jalan, tambahkan class="btn-delete" pada tombol hapus di index.php
    // Contoh: <a href="..." class="btn ... btn-delete">
    
    const deleteButtons = document.querySelectorAll('a[href*="delete_id"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah link langsung jalan
            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Tugas yang dihapus tidak bisa dikembalikan loh!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E57373', // Warna merah lembut sesuai tema
                cancelButtonColor: '#8D6E63', // Warna coklat tema
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#FAF9F6', // Warna background warm
                color: '#4E342E' // Warna teks dark brown
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href; // Lanjut ke link penghapusan
                }
            });
        });
    });

    // 4. ANIMASI CEKLIS (Opsional)
    // Memberikan feedback visual instan saat tombol 'Selesai' ditekan
    const doneButtons = document.querySelectorAll('a[href*="toggle_id"]');
    doneButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Biarkan link jalan, tapi kasih efek sebentar (opsional)
            // Bisa tambahkan loading state disini jika perlu
        });
    });

});