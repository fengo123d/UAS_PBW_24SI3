<?php
session_start();
include 'koneksi.php';

// Proteksi Admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Logika Update Status
if (isset($_POST['update_status'])) {
    $id_p = $_POST['id_pesanan'];
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE history_pesanan SET status_pengiriman = '$status_baru' WHERE id_pesanan = '$id_p'");
    header("Location: pesanan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Riwayat Pesanan GOBU</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #432818;
            --accent: #99582a;
            --success: #27ae60;
            --bg: #fdf5e6;
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); margin: 0; padding: 0; }
        
        /* NAVBAR / TAB STYLING */
        .admin-nav { background: var(--primary); padding: 15px 0; margin-bottom: 30px; }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; gap: 20px; padding: 0 40px; }
        .nav-link { color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600; padding: 10px 20px; border-radius: 8px; }
        .nav-link.active { background: var(--accent); color: white; }
        .nav-link:hover { color: white; }

        .admin-container { max-width: 1400px; margin: 0 auto; padding: 0 40px 40px; }
        
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h2 { color: var(--primary); font-weight: 800; border-left: 5px solid var(--accent); padding-left: 15px; margin: 0; }
        
        .table-wrapper { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        
        th { background: var(--primary); color: white; padding: 20px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 18px 20px; border-bottom: 1px solid #f2f2f2; font-size: 14px; vertical-align: top; }
        tr:hover { background: #fffaf5; }

        .col-time { white-space: nowrap; color: #888; font-size: 13px; }
        .col-time b { color: var(--primary); font-size: 14px; display: block; }

        .col-pembeli b { color: var(--primary); font-size: 15px; text-transform: uppercase; display: block; }
        .col-pembeli span { font-size: 12px; color: #999; }

        .col-phone { font-weight: 800; color: var(--success); font-size: 15px; background: #f0fff4; padding: 5px 10px; border-radius: 8px; display: inline-block; border: 1px solid #c6f6d5; }

        .alamat-text { max-width: 250px; line-height: 1.5; color: #555; font-size: 13px; }

        .col-produk { color: #6b4c35; }
        .produk-item { padding: 5px 0; border-bottom: 1px dashed #eee; }
        .produk-item:last-child { border: none; }
        .produk-item b { color: var(--primary); }

        .price-tag { font-weight: 800; color: var(--primary); font-size: 16px; }
        
        /* DROPDOWN STYLING */
        .status-form select {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-family: inherit;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            outline: none;
        }
        .btn-update {
            background: var(--success);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 5px;
            font-size: 12px;
        }
        .btn-update:hover { opacity: 0.8; }
        
        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .status-selesai { background: #d1e7dd; color: #0f5132; }
        .status-proses { background: #fff3e0; color: #e67e22; }
    </style>
</head>
<body>

<div class="admin-nav">
    <div class="nav-container">
        <a href="user_history.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'user_history.php') ? 'active' : '' ?>">Daftar User</a>
        <a href="dashboard.php" class="nav-link">Daftar Produk</a>
        <a href="pesanan.php" class="nav-link active">Pesanan Masuk</a>
        <a href="logout.php" class="nav-link" style="margin-left: auto; opacity: 0.5;">Logout</a>
    </div>
</div>

<div class="admin-container">
    <div class="header-section">
        <h2><i class="fa-solid fa-clipboard-list"></i> Manajemen Pesanan Masuk</h2>
        <div style="color: #888; font-size: 14px;">Update: <?php echo date('d M Y H:i'); ?></div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama Pembeli</th>
                    <th>WhatsApp</th>
                    <th>Alamat Lengkap</th>
                    <th>Detail Pesanan</th>
                    <th>Total</th>
                    <th>Status & Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT * FROM history_pesanan ORDER BY tanggal DESC");
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $current_status = $row['status_pengiriman'];
                        $badge_class = ($current_status == 'Selesai') ? 'status-selesai' : 'status-proses';
                ?>
                    <tr>
                        <td class="col-time">
                            <b><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></b>
                            <?php echo date('H:i', strtotime($row['tanggal'])); ?> WIB
                        </td>

                        <td class="col-pembeli">
                            <b><?php echo $row['nama_pembeli']; ?></b>
                            <span>User: @<?php echo $row['username']; ?></span>
                        </td>

                        <td>
                            <div class="col-phone">
                                <i class="fa-brands fa-whatsapp"></i> 
                                <?php echo !empty($row['no_telp']) ? $row['no_telp'] : '-'; ?>
                            </div>
                        </td>

                        <td>
                            <div class="alamat-text">
                                <?php echo $row['alamat']; ?>
                            </div>
                        </td>

                        <td class="col-produk">
                            <?php 
                            $details = json_decode($row['detail_pesanan'], true);
                            if ($details) {
                                foreach ($details as $item) {
                                    echo "<div class='produk-item'>";
                                    echo "• " . $item['name'] . " <br>";
                                    echo "&nbsp;&nbsp;<b>x" . $item['qty'] . "</b> | <small>Tipe: " . ($item['grind'] ?? 'Kasar') . "</small>";
                                    echo "</div>";
                                }
                            } else {
                                echo $row['nama_produk'];
                            }
                            ?>
                        </td>

                        <td class="price-tag">
                            Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?>
                        </td>

                        <td>
                            <div class="badge-status <?php echo $badge_class; ?>">
                                <?php echo $current_status; ?>
                            </div>
                            
                            <form action="" method="POST" class="status-form">
                                <input type="hidden" name="id_pesanan" value="<?php echo $row['id_pesanan']; ?>">
                                <select name="status_baru">
                                    <option value="Diproses" <?php if($current_status == 'Diproses') echo 'selected'; ?>>Diproses</option>
                                    <option value="Selesai" <?php if($current_status == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                    <option value="Dibatalkan" <?php if($current_status == 'Dibatalkan') echo 'selected'; ?>>Batal</option>
                                </select>
                                <button type="submit" name="update_status" class="btn-update">✔</button>
                            </form>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding:100px; color:#aaa;'>Belum ada pesanan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>