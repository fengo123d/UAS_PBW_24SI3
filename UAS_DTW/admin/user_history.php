<?php
// 1. Aktifkan laporan error untuk tahu salahnya di mana
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'koneksi.php';

// 2. Proteksi Admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// 3. Ambil data (Ganti 'user' jadi nama tabelmu jika berbeda, misal 'users')
$nama_tabel = "users"; 
$query = mysqli_query($conn, "SELECT * FROM $nama_tabel ORDER BY id DESC");

// Jika query gagal, tampilkan error database-nya apa
if (!$query) {
    die("Gagal mengambil data database: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User History | GOBU Coffee Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary: #432818; --accent: #99582a; --bg: #fdf5e6; --white: #ffffff; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); margin: 0; padding: 0; }
        .admin-nav { background: var(--primary); padding: 15px 0; margin-bottom: 30px; }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; gap: 20px; padding: 0 40px; }
        .nav-link { color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600; padding: 10px 20px; border-radius: 8px; }
        .nav-link.active { background: var(--accent); color: white; }
        .nav-link:hover { color: white; }
        .admin-container { max-width: 1000px; margin: 0 auto; padding: 0 40px 40px; }
        h2 { color: var(--primary); font-weight: 800; border-left: 5px solid var(--accent); padding-left: 15px; margin: 0; }
        .table-wrapper { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--primary); color: white; padding: 20px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 18px 20px; border-bottom: 1px solid #f2f2f2; font-size: 14px; color: #333; }
        tr:hover { background: #fffaf5; }
        .user-icon { background: #efebe9; color: var(--primary); padding: 10px; border-radius: 50%; margin-right: 10px; }
    </style>
</head>
<body>

<div class="admin-nav">
    <div class="nav-container">
        <a href="dashboard.php" class="nav-link">Daftar Produk</a>
        <a href="pesanan.php" class="nav-link">Pesanan Masuk</a>
        <a href="user_history.php" class="nav-link active">Daftar User</a>
        <a href="logout.php" class="nav-link" style="margin-left: auto; opacity: 0.5;">Logout</a>
    </div>
</div>

<div class="admin-container">
    <div class="header-section" style="margin-bottom: 30px;">
        <h2><i class="fa-solid fa-users"></i> History Register User</h2>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Waktu Daftar</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                while($row = mysqli_fetch_assoc($query)) { 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <i class="fa-solid fa-user user-icon"></i>
                        <strong><?= htmlspecialchars($row['username']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td style="color: #888; font-size: 12px;">
                        <?= isset($row['created_at']) ? $row['created_at'] : 'ID: '.$row['id'] ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>