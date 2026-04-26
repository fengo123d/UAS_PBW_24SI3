<?php
// 1. Tampilkan error jika ada masalah
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. Proteksi Admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// 3. Koneksi Database
include 'koneksi.php';

// 4. Ambil data produk
$data = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - GOBU Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">GOBU COFFEE - Admin Panel</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container">
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">Daftar Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">Pesanan Masuk</a>
            </li>
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manajemen Produk</h2>
            <a href="tambah_produk.php" class="btn btn-success">+ Tambah Produk</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            while($row = mysqli_fetch_assoc($data)) { 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php 
                                    // Cek apakah file gambarnya ada beneran di folder upload
                                    $path_gambar = "../upload/" . $row['gambar'];
                                    if(!empty($row['gambar']) && file_exists($path_gambar)): 
                                    ?>
                                        <img src="<?= $path_gambar ?>" class="img-preview" alt="Produk">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white text-center rounded" style="width:60px; height:60px; line-height:60px; font-size:10px;">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($row['nama_produk']) ?></strong></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['varian'] ?? '-') ?></span></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['stok']) ?></td>
                                <td class="text-center">
                                    <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>