<?php
session_start();

// 1. Proteksi Admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// 2. Koneksi Database
include 'koneksi.php';

// 3. Ambil data produk
$data = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #432818;
            --accent: #99582a;
            --success: #27ae60;
            --bg: #fdf5e6;
            --white: #ffffff;
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); margin: 0; padding: 0; }
        
        /* NAVBAR / TAB STYLING (Sama dengan pesanan.php) */
        .admin-nav { background: var(--primary); padding: 15px 0; margin-bottom: 30px; }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; gap: 20px; padding: 0 40px; }
        .nav-link { color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600; padding: 10px 20px; border-radius: 8px; }
        .nav-link.active { background: var(--accent); color: white; }
        .nav-link:hover { color: white; }

        .admin-container { max-width: 1400px; margin: 0 auto; padding: 0 40px 40px; }
        
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h2 { color: var(--primary); font-weight: 800; border-left: 5px solid var(--accent); padding-left: 15px; margin: 0; }

        /* BUTTON STYLE */
        .btn-tambah { 
            background: var(--success); 
            color: white; 
            text-decoration: none; 
            padding: 12px 24px; 
            border-radius: 12px; 
            font-weight: 800; 
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);
        }
        .btn-tambah:hover { transform: translateY(-2px); opacity: 0.9; }

        /* TABLE STYLING */
        .table-wrapper { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        
        th { background: var(--primary); color: white; padding: 20px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 15px 20px; border-bottom: 1px solid #f2f2f2; font-size: 14px; color: #333; }
        tr:hover { background: #fffaf5; }

        .img-preview {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #f0f0f0;
        }

        .badge-varian {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .price-tag { font-weight: 800; color: var(--primary); font-size: 16px; }
        .stok-text { font-weight: 600; color: #666; }

        /* ACTION BUTTONS */
        .btn-edit { color: #f39c12; background: #fef5e7; padding: 8px; border-radius: 8px; text-decoration: none; margin-right: 5px; }
        .btn-hapus { color: #e74c3c; background: #fdeaea; padding: 8px; border-radius: 8px; text-decoration: none; }
        .btn-edit:hover, .btn-hapus:hover { opacity: 0.7; }
    </style>
</head>
<body>

<div class="admin-nav">
    <div class="nav-container">
        <a href="user_history.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'user_history.php') ? 'active' : '' ?>">Daftar User</a>
        <a href="dashboard.php" class="nav-link active">Daftar Produk</a>
        <a href="pesanan.php" class="nav-link">Pesanan Masuk</a>
        <a href="logout.php" class="nav-link" style="margin-left: auto; opacity: 0.5;">Logout</a>
    </div>
</div>

<div class="admin-container">
    <div class="header-section">
        <h2><i class="fa-solid fa-layer-group"></i> Manajemen Produk</h2>
        <a href="tambah_produk.php" class="btn-tambah">
            <i class="fa-solid fa-plus"></i> Tambah Produk Baru
        </a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Varian</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th style="text-align: center;">Aksi</th>
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
                        $path_gambar = "../upload/" . $row['gambar'];
                        if(!empty($row['gambar'])): 
                        ?>
                            <img src="<?= $path_gambar ?>" class="img-preview" alt="Produk">
                        <?php else: ?>
                            <div style="width:70px; height:70px; background:#eee; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#aaa;">No Image</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color: var(--primary); font-size: 15px;"><?= htmlspecialchars($row['nama_produk']) ?></strong>
                    </td>
                    <td>
                        <span class="badge-varian"><?= htmlspecialchars($row['varian'] ?? '-') ?></span>
                    </td>
                    <td class="price-tag">
                        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                    </td>
                    <td class="stok-text">
                        <?= htmlspecialchars($row['stok']) ?> <small>pcs</small>
                    </td>
                    <td style="text-align: center;">
                        <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn-edit" title="Edit Produk">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-hapus" title="Hapus Produk" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>