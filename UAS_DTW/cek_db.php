<?php
include 'koneksi.php';

// Cek apakah produk dengan ID 1 ada
$res = mysqli_query($conn, "SELECT * FROM produk LIMIT 1");
$data = mysqli_fetch_assoc($res);

if ($data) {
    echo "Produk ditemukan: " . $data['nama_produk'] . " | Stok: " . $data['stok'] . "<br>";
    $id_tes = $data['id'];
    
    // Coba update paksa kurangi 1
    $update = mysqli_query($conn, "UPDATE produk SET stok = stok - 1 WHERE id = $id_tes");
    
    if (mysqli_affected_rows($conn) > 0) {
        echo "<b>BERHASIL!</b> Stok di database beneran berkurang. Berarti jalur koneksi aman.";
    } else {
        echo "<b>GAGAL!</b> Query jalan tapi stok gak berubah. Cek nama kolom 'id' atau 'stok' di DB kamu.";
    }
} else {
    echo "Tabel produk kosong!";
}
?>