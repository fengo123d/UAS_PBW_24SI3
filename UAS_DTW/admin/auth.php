<?php
// Aktifkan error reporting agar tidak putih
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Cek apakah file koneksi ada di folder yang sama
if (!file_exists('koneksi.php')) {
    die("STOP! File koneksi.php tidak ditemukan di dalam folder admin. Silakan copy file tersebut ke sini.");
}

include 'koneksi.php';

if (isset($_POST['username'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = md5($_POST['password']); 

    $query = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
    $data = mysqli_query($conn, $query);

    if (!$data) {
        die("Kesalahan Query: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($data) > 0) {
        $_SESSION['admin'] = true;
        $_SESSION['username'] = $user;
        
        // Coba redirect
        header("Location: dashboard.php");
        // Jika header gagal, kita pakai Javascript sebagai cadangan
        echo "<script>window.location='dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Login Gagal! Akun tidak ditemukan.'); window.location='login.php';</script>";
        exit;
    }
} else {
    die("Data dari form login tidak diterima.");
}
?>