<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin yang bisa akses
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// ================= FUNGSI UPDATE =================
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $varian = mysqli_real_escape_string($conn, $_POST['varian']);
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah ada file yang diupload
    if($_FILES['gambar']['error'] === 4){
        // Jika tidak upload foto baru, pakai nama foto lama
        $namaGambarSekarang = $gambar_lama;
    } else {
        // Jika upload foto baru
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        
        // Buat nama unik
        $ekstensi = pathinfo($gambar, PATHINFO_EXTENSION);
        $namaGambarSekarang = time() . "_" . bin2hex(random_bytes(4)) . "." . $ekstensi;

        // Tentukan lokasi folder upload (Keluar dari folder admin, masuk ke folder upload)
        $target_dir = "../upload/";
        $target_file = $target_dir . $namaGambarSekarang;

        // PROSES PEMINDAHAN FILE
        if(move_uploaded_file($tmp, $target_file)){
            // Jika berhasil pindah, hapus foto lama jika ada
            if(!empty($gambar_lama) && file_exists("../upload/" . $gambar_lama)){
                unlink("../upload/" . $gambar_lama);
            }
        } else {
            // JIKA GAGAL PINDAH, TAMPILKAN ERROR DAN STOP
            die("<b>Gagal Upload!</b><br>
                 Penyebab: Folder <b>" . $target_dir . "</b> mungkin tidak ditemukan atau tidak punya izin tulis.<br>
                 Solusi: Pastikan folder 'upload' ada di luar folder admin.");
        }
    }

    // Update Database
    $query = "UPDATE produk SET 
              nama_produk = '$nama', 
              deskripsi = '$deskripsi', 
              harga = '$harga', 
              stok = '$stok', 
              varian = '$varian', 
              gambar = '$namaGambarSekarang' 
              WHERE id = $id";

    if(mysqli_query($conn, $query)){
        header("Location: dashboard.php?status=sukses");
        exit;
    } else {
        die("Gagal Database: " . mysqli_error($conn));
    }
}
?>