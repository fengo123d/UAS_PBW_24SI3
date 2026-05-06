<?php
// 1. Diagnosa error (biar ketahuan kalau ada yang salah)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. Proteksi Halaman Admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// 3. Koneksi ke Database
include 'koneksi.php';

// 4. Ambil ID dari URL dan cek datanya
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
    $row = mysqli_fetch_assoc($data);

    // Jika data tidak ditemukan di database
    if(!$row){
        echo "<script>alert('Data tidak ditemukan!'); window.location='dashboard.php';</script>";
        exit;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - GOBU Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning py-3">
                        <h4 class="mb-0 text-dark">📝 Edit Produk GOBU</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="proses.php" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Produk</label>
                                <input type="text" name="nama" value="<?= htmlspecialchars($row['nama_produk']) ?>" class="form-control" placeholder="Contoh: Robusta Premium" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Varian Berat</label>
                                <select name="varian" class="form-select" required>
                                    <option value="100g" <?= ($row['varian'] == '100g') ? 'selected' : '' ?>>100g</option>
                                    <option value="250g" <?= ($row['varian'] == '250g') ? 'selected' : '' ?>>250g</option>
                                </select>
                                <small class="text-muted text-italic">*Varian ini menentukan penempatan di halaman produk user.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Harga (Rp)</label>
                                    <input type="number" name="harga" value="<?= $row['harga'] ?>" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stok</label>
                                    <input type="number" name="stok" value="<?= $row['stok'] ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Gambar Produk</label>
                                <div class="mb-2">
                                    <?php 
                                    // Cek apakah file benar-benar ada di folder upload
                                    $file_gambar = "../upload/" . $row['gambar'];
                                    if(!empty($row['gambar']) && file_exists($file_gambar)): ?>
                                        <img src="<?= $file_gambar ?>" width="120" class="img-thumbnail shadow-sm rounded">
                                        <p class="text-muted mt-1" style="font-size: 12px;">File saat ini: <?= $row['gambar'] ?></p>
                                    <?php else: ?>
                                        <div class="alert alert-danger py-2" style="font-size: 13px;">
                                            ⚠️ File gambar tidak ditemukan di folder <b>upload/</b>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="gambar" class="form-control">
                                <small class="text-secondary">Pilih file baru jika ingin mengganti foto, kosongkan jika tidak.</small>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="dashboard.php" class="btn btn-outline-secondary px-4">Batal</a>
                                <button type="submit" name="update" class="btn btn-warning px-5 fw-bold text-dark shadow-sm">Update Produk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>