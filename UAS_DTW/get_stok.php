<?php
include 'koneksi.php';

$q1 = mysqli_query($conn, "SELECT stok FROM produk WHERE varian='100g' ORDER BY id DESC LIMIT 1");
$q2 = mysqli_query($conn, "SELECT stok FROM produk WHERE varian='250g' ORDER BY id DESC LIMIT 1");

$r1 = mysqli_fetch_assoc($q1);
$r2 = mysqli_fetch_assoc($q2);

echo json_encode([
    "stok100" => $r1['stok'],
    "stok250" => $r2['stok']
]);