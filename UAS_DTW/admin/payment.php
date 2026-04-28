<?php
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $detail = mysqli_real_escape_string($conn, $_POST['keranjang']); 
    $total = (int)$_POST['total'];

    $query = "INSERT INTO pesanan (nama_pembeli, email_pembeli, detail_pesanan, total_harga, status) 
              VALUES ('$nama', '$email', '$detail', '$total', 'Pending')";
    
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Pesanan berhasil dikirim!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit;
}

$css_file = "payment.css"; 
$current_page = "pembayaran";
include 'header.php'; 
?>

<div class="payment-wrapper">
    <div class="container mt-5">
        <h2>Konfirmasi Pembayaran</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <input type="hidden" name="keranjang" value="Contoh: Robusta 250g (1)">
            <input type="hidden" name="total" value="21000">
            
            <button type="submit" class="btn btn-primary">Konfirmasi & Pesan Sekarang</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>