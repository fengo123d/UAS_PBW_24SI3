<?php
session_start();
include 'koneksi.php';

// Matikan error reporting visual
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_SESSION['username'];
    $cart_json = isset($_POST['cart_data']) ? $_POST['cart_data'] : '';
    $cart_items = json_decode($cart_json, true);

    if (empty($cart_items)) {
        die("Gagal: Keranjang kosong.");
    }

    $id_gagal = "";
    $berhasil_update = 0;

    foreach ($cart_items as $item) {
        $id = (int)$item['id'];
        $qty = (int)$item['qty'];

        // QUERY UPDATE
        $sql = "UPDATE produk 
        SET stok = stok - $qty 
        WHERE id = $id AND stok >= $qty";
        mysqli_query($conn, $sql);

        if (mysqli_affected_rows($conn) > 0) {
            $berhasil_update++;
        } else {
            $id_gagal .= $id . " ";
        }
    }

    if ($berhasil_update > 0) {
        // Catat history
        $total = $_POST['total_harga'];
        $metode = $_POST['payment_method'];
        mysqli_query($conn, "INSERT INTO history_pesanan (username, total_bayar, metode_bayar, status_pengiriman) VALUES ('$u', '$total', '$metode', 'Diproses')");
        
        echo "success";
    } else {
        echo "Gagal: ID ($id_gagal) tidak ditemukan di database. Cek tabel produk kamu!";
    }
    // Tambahkan ini di paling atas proses_bayar.php setelah include koneksi
file_put_contents('debug.txt', print_r($_POST, true));
}
?>
