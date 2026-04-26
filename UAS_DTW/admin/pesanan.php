<?php
session_start();
// Proteksi Admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include include 'koneksi.php';

// LOGIKA UPDATE STATUS (Selesai/Batal)
if(isset($_GET['update_id'])){
    $id = (int)$_GET['update_id'];
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    mysqli_query($conn, "UPDATE history_pesanan SET status_pengiriman='$status' WHERE id_pesanan=$id");
    header("Location: pesanan.php");
    exit;
}

// Ambil data dari tabel kamu: history_pesanan
$data_pesanan = mysqli_query($conn, "SELECT * FROM history_pesanan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders | GOBU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8f9fa; }
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #1a1a1a; padding-top: 20px; color: white; }
        .main-content { margin-left: 250px; padding: 40px; }
        .nav-link { color: rgba(255,255,255,0.7); margin: 5px 20px; border-radius: 10px; }
        .nav-link.active { background: #432818; color: white; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4 class="text-center mb-4">GOBU ADMIN</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php">Daftar Produk</a>
            <a class="nav-link active" href="pesanan.php">Pesanan Masuk</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h2 class="fw-800 mb-4">Pesanan Masuk</h2>
        
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Customer</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($data_pesanan)) { 
                            $status = $row['status_pengiriman'];
                            $badge = ($status == 'Selesai') ? 'success' : 'warning';
                        ?>
                        <tr>
                            <td class="ps-4">#GB-<?= $row['id_pesanan'] ?></td>
                            <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                            <td class="fw-bold">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                            <td><?= $row['metode_bayar'] ?></td>
                            <td><span class="badge bg-<?= $badge ?>"><?= $status ?></span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="?update_id=<?= $row['id_pesanan'] ?>&status=Selesai" class="btn btn-sm btn-success">Selesai</a>
                                    <a href="?update_id=<?= $row['id_pesanan'] ?>&status=Dibatalkan" class="btn btn-sm btn-outline-danger">Batal</a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>