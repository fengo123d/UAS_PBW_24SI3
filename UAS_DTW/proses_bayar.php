<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_SESSION['username'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_telp']); // Menangkap No HP
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $total = intval($_POST['total_harga']);
    $metode = $_POST['payment_method'];
    
    $cart_json = $_POST['cart_data'];
    $items = json_decode($cart_json, true);

    // Membuat ringkasan teks untuk Admin (Contoh: "Gobu 100g (2x), Gobu 250g (1x)")
    $ringkasan_produk = "";
    foreach ($items as $item) {
        $ringkasan_produk .= $item['name'] . " (" . $item['qty'] . "x), ";
    }
    $ringkasan_produk = rtrim($ringkasan_produk, ", ");

    // Simpan ke history_pesanan dengan kolom lengkap
    $query = "INSERT INTO history_pesanan 
              (username, nama_pembeli, no_telp, alamat, nama_produk, total_bayar, metode_bayar, status_pengiriman, detail_pesanan, tanggal) 
              VALUES 
              ('$u', '$nama', '$no_hp', '$alamat', '$ringkasan_produk', '$total', '$metode', 'Diproses', '$cart_json', NOW())";
    
    if(mysqli_query($conn, $query)) {
        // Potong stok
        foreach ($items as $item) {
            $id_p = intval($item['id']);
            $qty_beli = intval($item['qty']);
            mysqli_query($conn, "UPDATE produk SET stok = stok - $qty_beli WHERE id = $id_p");
        }
        echo "success";
    }
}
?>