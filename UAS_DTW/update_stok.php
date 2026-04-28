<?php
// Tampilkan error kalau ada masalah script
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

    if ($id > 0 && $qty > 0) {
        // Jalankan Query
        $sql = "UPDATE produk SET stok = stok - $qty WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            echo "sukses";
        } else {
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        echo "Data ID atau QTY tidak valid. ID: $id, QTY: $qty";
    }
} else {
    echo "Hanya menerima POST request";
}
?>