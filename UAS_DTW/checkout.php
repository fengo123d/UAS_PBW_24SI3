<?php
include 'koneksi.php';

// Contoh: User membeli produk dengan ID tertentu
$id_produk = $_POST['id_produk']; // ID dari keranjang
$jumlah_beli = $_POST['qty']; // Jumlah yang dibeli

// SQL untuk mengurangi stok: STOK LAMA - JUMLAH BELI
$query = "UPDATE produk SET stok = stok - $jumlah_beli WHERE id_produk = '$id_produk'";

if(mysqli_query($conn, $query)) {
    echo "Pesanan berhasil, stok telah diperbarui!";
} else {
    echo "Gagal memperbarui stok: " . mysqli_error($conn);
}
?>