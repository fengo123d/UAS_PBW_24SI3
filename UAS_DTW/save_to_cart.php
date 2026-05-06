<?php
session_start();
include 'koneksi.php';

// Pastikan koneksi tidak mati
if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// 1. Cek Login
if (!isset($_SESSION['username'])) {
    echo "ERROR: Anda belum login!";
    exit();
}

// 2. Cek apakah data dikirim dari page2.php
if (isset($_POST['name'])) {
    $u = $_SESSION['username'];
    $n = mysqli_real_escape_string($conn, $_POST['name']);
    $h = (int)$_POST['price'];
    $q = (int)$_POST['qty'];
    $i = mysqli_real_escape_string($conn, $_POST['image']);

    // 3. LOGIKA CEK ISI TABEL
    $check = mysqli_query($conn, "SELECT * FROM keranjang WHERE username='$u' AND nama_produk='$n'");

    if (mysqli_num_rows($check) > 0) {
        // Update jika sudah ada
        $sql = "UPDATE keranjang SET qty = qty + $q WHERE username='$u' AND nama_produk='$n'";
    } else {
        // Insert jika baru
        $sql = "INSERT INTO keranjang (username, nama_produk, harga, qty, image) VALUES ('$u', '$n', '$h', '$q', '$i')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        // INI PENTING: Jika error, dia akan sebutkan kolom mana yang salah
        echo "MySQL Error: " . mysqli_error($conn);
    }
} else {
    echo "ERROR: Data 'name' tidak diterima oleh PHP. Cek script di page2.php kamu!";
}
?>