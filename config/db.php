<?php
// config/db.php

// Ambil konfigurasi dari Environment Variables Vercel
// Supabase menggunakan driver pgsql (PostgreSQL)

$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '5432'; // Port default Postgre
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME') ?: 'postgres';

// Validasi jika Environment Variables belum di-set (Mencegah error fatal di local tanpa env)
if (!$host) {
    // Kamu bisa mengisi nilai default di sini JIKA ingin testing di localhost (XAMPP)
    // Tapi JANGAN di-commit ke GitHub jika berisi password asli.
    // $host = 'db.zxy...supabase.co';
}

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Membuat koneksi PDO menggunakan driver pgsql
    $pdo = new PDO($dsn, $user, $pass);
    
    // Konfigurasi Error Mode agar muncul pesan error jika query salah
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Default fetch mode menjadi Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Matikan Emulate Prepares (Disarankan untuk keamanan PostgreSQL)
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException $e) {
    // Tampilkan pesan error yang jelas jika gagal konek
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>