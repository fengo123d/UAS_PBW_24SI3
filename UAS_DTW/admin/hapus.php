<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include include 'koneksi.php';

// Casting (int) untuk mencegah SQL Injection
$id = (int)$_GET['id'];

// ambil data gambar dulu
$data = mysqli_query($conn, "SELECT gambar FROM produk WHERE id=$id");
if($row = mysqli_fetch_assoc($data)){
    // hapus file gambar jika ada
    if(file_exists("../upload/" . $row['gambar']) && $row['gambar'] != ''){
        unlink("../upload/" . $row['gambar']);
    }
}

// hapus dari database
mysqli_query($conn, "DELETE FROM produk WHERE id=$id");

header("Location: dashboard.php");
exit;
?>