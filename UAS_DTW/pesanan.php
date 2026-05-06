<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// LOGIKA UPDATE STATUS
if(isset($_GET['update_id'])){
    $id = (int)$_GET['update_id'];
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    mysqli_query($conn, "UPDATE history_pesanan SET status_pengiriman='$status' WHERE id_pesanan=$id");
    header("Location: pesanan.php");
    exit;
}

// Ambil data pesanan
$data_pesanan = mysqli_query($conn, "SELECT * FROM history_pesanan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Masuk | GOBU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f0f0f;
            --sidebar: #161616;
            --card: #1c1c1c;
            --border: #2a2a2a;
            --accent: #c8973a;
            --accent-dim: rgba(200, 151, 58, 0.12);
            --text: #f0ece4;
            --muted: #6b6560;
            --success: #3ecf8e;
            --danger: #f06565;
            --pending: #e8a94b;
            --paid: #3ecf8e;
            --cancelled: #f06565;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ───── SIDEBAR ───── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo h4 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 3px;
            color: var(--accent);
            text-transform: uppercase;
        }

        .sidebar-logo p {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 4px;
            transition: all 0.2s;
        }

        .nav-link:hover { background: var(--border); color: var(--text); }
        .nav-link.active { background: var(--accent-dim); color: var(--accent); font-weight: 600; }

        .sidebar-logout {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        /* ───── MAIN ───── */
        .main-content {
            margin-left: 240px;
            flex: 1;
            padding: 40px 48px;
            max-width: calc(100vw - 240px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 32px;
        }

        .page-header h2 {
            font-size: 26px;
            font-weight: 800;
        }

        .page-header p {
            color: var(--muted);
            font-size: 13px;
            margin-top: 4px;
        }

        /* ───── STAT PILLS ───── */
        .stat-row {
            display: flex;
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-pill {
            flex: 1;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px 20px;
        }

        .stat-pill .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .stat-pill .value {
            font-size: 22px;
            font-weight: 800;
        }

        /* ───── ORDER CARDS ───── */
        .order-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .order-card:hover {
            border-color: #3a3a3a;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            user-select: none;
        }

        .order-id {
            font-size: 13px;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: 1px;
        }

        .order-customer {
            font-size: 15px;
            font-weight: 700;
            margin-top: 2px;
        }

        .order-date {
            font-size: 11.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .total-badge {
            font-size: 16px;
            font-weight: 800;
            color: var(--text);
        }

        /* ───── STATUS BADGE ───── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-Pending {
            background: rgba(232, 169, 75, 0.15);
            color: var(--pending);
            border: 1px solid rgba(232, 169, 75, 0.3);
        }
        .status-Pending::before { background: var(--pending); }

        .status-Paid, .status-Lunas {
            background: rgba(62, 207, 142, 0.12);
            color: var(--paid);
            border: 1px solid rgba(62, 207, 142, 0.25);
        }
        .status-Paid::before, .status-Lunas::before { background: var(--paid); }

        .status-Selesai {
            background: rgba(62, 207, 142, 0.12);
            color: var(--paid);
            border: 1px solid rgba(62, 207, 142, 0.25);
        }
        .status-Selesai::before { background: var(--paid); }

        .status-Dibatalkan {
            background: rgba(240, 101, 101, 0.12);
            color: var(--danger);
            border: 1px solid rgba(240, 101, 101, 0.25);
        }
        .status-Dibatalkan::before { background: var(--danger); }

        /* ───── ORDER BODY (Collapsible) ───── */
        .order-body {
            display: none;
            padding: 24px;
            gap: 24px;
        }

        .order-body.open { display: grid; grid-template-columns: 1fr 1fr; }

        .info-section h6 {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--muted);
            margin-bottom: 14px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px;
        }

        .info-row:last-child { border-bottom: none; }

        .info-row .key {
            color: var(--muted);
            font-weight: 500;
            flex-shrink: 0;
            margin-right: 12px;
        }

        .info-row .val {
            text-align: right;
            font-weight: 600;
            word-break: break-word;
        }

        /* ───── PRODUCT TAG ───── */
        .product-item {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 8px;
        }

        .product-item .pname {
            font-weight: 700;
            font-size: 14px;
        }

        .product-item .pmeta {
            display: flex;
            gap: 8px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .tag {
            background: var(--border);
            color: var(--muted);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .tag.accent {
            background: var(--accent-dim);
            color: var(--accent);
        }

        /* ───── ACTION BUTTONS ───── */
        .order-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 14px 24px;
            border-top: 1px solid var(--border);
            background: rgba(255,255,255,0.01);
        }

        .btn-action {
            padding: 7px 18px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1.5px solid;
            transition: all 0.18s;
        }

        .btn-selesai {
            background: rgba(62, 207, 142, 0.1);
            border-color: rgba(62, 207, 142, 0.4);
            color: var(--success);
        }
        .btn-selesai:hover {
            background: rgba(62, 207, 142, 0.2);
            color: var(--success);
        }

        .btn-batal {
            background: transparent;
            border-color: var(--border);
            color: var(--muted);
        }
        .btn-batal:hover {
            border-color: rgba(240, 101, 101, 0.4);
            color: var(--danger);
        }

        /* ───── PAYMENT METHOD ICON ───── */
        .pay-method {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--border);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ───── EMPTY STATE ───── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--muted);
        }

        .empty-state .icon { font-size: 48px; margin-bottom: 16px; }
        .empty-state h5 { font-size: 16px; font-weight: 600; margin-bottom: 6px; color: var(--text); }

        /* ───── CHEVRON ───── */
        .chevron {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 12px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .order-card.expanded .chevron { transform: rotate(180deg); background: var(--accent-dim); color: var(--accent); border-color: rgba(200,151,58,0.3); }

        @media (max-width: 900px) {
            .order-body.open { grid-template-columns: 1fr; }
            .main-content { padding: 24px 20px; margin-left: 0; }
            .sidebar { display: none; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-logo">
        <h4>GOBU</h4>
        <p>Admin Dashboard</p>
    </div>
    <div class="sidebar-nav">
        <a class="nav-link" href="dashboard.php">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Daftar Produk
        </a>
        <a class="nav-link active" href="pesanan.php">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Pesanan Masuk
        </a>
    </div>
    <div class="sidebar-logout">
        <a class="nav-link" href="logout.php" style="color: var(--danger);">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            Logout
        </a>
    </div>
</div>

<!-- MAIN -->
<div class="main-content">
    <div class="page-header">
        <div>
            <h2>Pesanan Masuk</h2>
            <p>Kelola dan pantau semua pesanan pelanggan</p>
        </div>
    </div>

    <?php
    // Hitung statistik
    mysqli_data_seek($data_pesanan, 0);
    $total_pesanan = 0;
    $total_revenue = 0;
    $pending_count = 0;
    $selesai_count = 0;
    $all_rows = [];

    while($r = mysqli_fetch_assoc($data_pesanan)){
        $all_rows[] = $r;
        $total_pesanan++;
        $total_revenue += $r['total_bayar'];
        if($r['status_pengiriman'] == 'Selesai') $selesai_count++;
        if($r['status_pengiriman'] == 'Pending') $pending_count++;
    }
    ?>

    <!-- STAT PILLS -->
    <div class="stat-row">
        <div class="stat-pill">
            <div class="label">Total Pesanan</div>
            <div class="value"><?= $total_pesanan ?></div>
        </div>
        <div class="stat-pill">
            <div class="label">Total Revenue</div>
            <div class="value" style="color: var(--accent); font-size: 17px;">Rp <?= number_format($total_revenue, 0, ',', '.') ?></div>
        </div>
        <div class="stat-pill">
            <div class="label">Pending</div>
            <div class="value" style="color: var(--pending);"><?= $pending_count ?></div>
        </div>
        <div class="stat-pill">
            <div class="label">Selesai</div>
            <div class="value" style="color: var(--success);"><?= $selesai_count ?></div>
        </div>
    </div>

    <!-- ORDER LIST -->
    <?php if(empty($all_rows)): ?>
    <div class="empty-state">
        <div class="icon">📭</div>
        <h5>Belum Ada Pesanan</h5>
        <p>Pesanan baru akan muncul di sini</p>
    </div>
    <?php else: ?>

    <?php foreach($all_rows as $i => $row):
        $status = $row['status_pengiriman'];
        $status_class = 'status-' . str_replace(' ', '', $status);

        // Parse detail pesanan (JSON atau string biasa)
        $detail_raw = $row['detail_pesanan'] ?? '';
        $detail_json = json_decode($detail_raw, true);

        // Cek kolom yang ada di tabel kamu
        $nama        = htmlspecialchars($row['username'] ?? $row['nama_pembeli'] ?? '-');
        $total       = $row['total_bayar'];
        $metode      = htmlspecialchars($row['metode_bayar'] ?? '-');
        $tanggal     = $row['tanggal'] ?? '-';
        $email       = htmlspecialchars($row['email_pembeli'] ?? '-');
        $notelp      = htmlspecialchars($row['no_telp'] ?? $row['nomor_hp'] ?? '-');
        $alamat      = htmlspecialchars($row['alamat'] ?? $row['alamat_pengiriman'] ?? '-');
        $id_pesanan  = $row['id_pesanan'];

        // Nama produk, varian, tipe — bisa dari kolom terpisah atau dari JSON detail
        $nama_produk = htmlspecialchars($row['nama_produk'] ?? '');
        $varian      = htmlspecialchars($row['varian'] ?? '');       // 100g / 250g
        $tipe        = htmlspecialchars($row['tipe'] ?? $row['jenis_kopi'] ?? ''); // halus / kasar
        $qty         = htmlspecialchars($row['qty'] ?? $row['jumlah'] ?? '');

        // Jika detail tersimpan sebagai JSON array of items
        $produk_items = [];
        if($detail_json && isset($detail_json['items'])) {
            $produk_items = $detail_json['items'];
        } elseif($detail_json && is_array($detail_json)) {
            $produk_items = $detail_json;
        }
    ?>

    <div class="order-card" id="card-<?= $i ?>">
        <!-- HEADER (klik untuk expand) -->
        <div class="order-header" onclick="toggleCard(<?= $i ?>)">
            <div>
                <div class="order-id">#GB-<?= str_pad($id_pesanan, 4, '0', STR_PAD_LEFT) ?></div>
                <div class="order-customer"><?= $nama ?></div>
                <div class="order-date"><?= $tanggal ?></div>
            </div>
            <div class="header-right">
                <span class="status-badge <?= $status_class ?>"><?= $status ?></span>
                <div class="total-badge">Rp <?= number_format($total, 0, ',', '.') ?></div>
                <div class="chevron">▾</div>
            </div>
        </div>

        <!-- BODY (collapsible) -->
        <div class="order-body" id="body-<?= $i ?>">
            <!-- KOLOM KIRI: Info Produk -->
            <div class="info-section">
                <h6>Detail Produk</h6>

                <?php if(!empty($produk_items)): ?>
                    <?php foreach($produk_items as $item): ?>
                    <div class="product-item">
                        <div class="pname"><?= htmlspecialchars($item['nama'] ?? $item['name'] ?? 'Produk') ?></div>
                        <div class="pmeta">
                            <?php if(!empty($item['varian'])): ?>
                                <span class="tag accent">⚖️ <?= htmlspecialchars($item['varian']) ?></span>
                            <?php endif; ?>
                            <?php if(!empty($item['tipe'])): ?>
                                <span class="tag">☕ <?= htmlspecialchars($item['tipe']) ?></span>
                            <?php endif; ?>
                            <?php if(!empty($item['qty'])): ?>
                                <span class="tag">×<?= htmlspecialchars($item['qty']) ?></span>
                            <?php endif; ?>
                            <?php if(!empty($item['harga'])): ?>
                                <span class="tag">Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                <?php elseif(!empty($nama_produk)): ?>
                    <!-- Fallback: kolom terpisah -->
                    <div class="product-item">
                        <div class="pname"><?= $nama_produk ?></div>
                        <div class="pmeta">
                            <?php if($varian): ?><span class="tag accent">⚖️ <?= $varian ?></span><?php endif; ?>
                            <?php if($tipe): ?><span class="tag">☕ <?= $tipe ?></span><?php endif; ?>
                            <?php if($qty): ?><span class="tag">×<?= $qty ?></span><?php endif; ?>
                        </div>
                    </div>

                <?php elseif(!empty($detail_raw)): ?>
                    <!-- Fallback: tampilkan raw detail -->
                    <div class="product-item">
                        <div class="pname" style="font-weight:500; font-size:13px; color:var(--muted);"><?= htmlspecialchars($detail_raw) ?></div>
                    </div>

                <?php else: ?>
                    <div style="color: var(--muted); font-size: 13px;">Detail produk tidak tersedia</div>
                <?php endif; ?>

                <!-- Total -->
                <div class="info-row" style="margin-top: 12px; border-top: 1px solid var(--border); padding-top: 12px;">
                    <span class="key">Total Bayar</span>
                    <span class="val" style="color: var(--accent);">Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>
                <div class="info-row">
                    <span class="key">Metode Bayar</span>
                    <span class="val">
                        <span class="pay-method">
                            <?php
                            $metode_lower = strtolower($metode);
                            if(strpos($metode_lower, 'qris') !== false) echo '📱 ';
                            elseif(strpos($metode_lower, 'transfer') !== false || strpos($metode_lower, 'bank') !== false) echo '🏦 ';
                            elseif(strpos($metode_lower, 'cod') !== false || strpos($metode_lower, 'tunai') !== false) echo '💵 ';
                            else echo '💳 ';
                            echo $metode;
                            ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="key">Status</span>
                    <span class="val"><span class="status-badge <?= $status_class ?>"><?= $status ?></span></span>
                </div>
            </div>

            <!-- KOLOM KANAN: Info Pelanggan -->
            <div class="info-section">
                <h6>Info Pelanggan & Pengiriman</h6>

                <div class="info-row">
                    <span class="key">Nama</span>
                    <span class="val"><?= $nama ?></span>
                </div>
                <div class="info-row">
                    <span class="key">Email</span>
                    <span class="val"><?= $email != '-' ? '<a href="mailto:'.$email.'" style="color:var(--accent); text-decoration:none;">'.htmlspecialchars($email).'</a>' : '-' ?></span>
                </div>
                <div class="info-row">
                    <span class="key">No. HP</span>
                    <span class="val">
                        <?php if($notelp != '-'): ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $notelp) ?>" target="_blank"
                               style="color: #25D366; text-decoration:none; font-weight:700;">
                                📱 <?= $notelp ?>
                            </a>
                        <?php else: ?>-<?php endif; ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="key">Alamat</span>
                    <span class="val" style="max-width: 200px;"><?= $alamat != '-' ? $alamat : '-' ?></span>
                </div>
                <div class="info-row">
                    <span class="key">Tanggal Pesan</span>
                    <span class="val"><?= $tanggal ?></span>
                </div>
            </div>
        </div>

        <!-- FOOTER: Action Buttons -->
        <div class="order-footer">
            <?php if($status != 'Selesai' && $status != 'Dibatalkan'): ?>
                <a href="?update_id=<?= $id_pesanan ?>&status=Selesai" class="btn-action btn-selesai"
                   onclick="return confirm('Tandai pesanan ini sebagai Selesai?')">✓ Selesai</a>
                <a href="?update_id=<?= $id_pesanan ?>&status=Dibatalkan" class="btn-action btn-batal"
                   onclick="return confirm('Batalkan pesanan ini?')">Batalkan</a>
            <?php else: ?>
                <span style="font-size: 12px; color: var(--muted); padding: 7px 0;">
                    <?= $status == 'Selesai' ? '✓ Pesanan telah selesai' : '✗ Pesanan dibatalkan' ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function toggleCard(i) {
    const card = document.getElementById('card-' + i);
    const body = document.getElementById('body-' + i);
    card.classList.toggle('expanded');
    body.classList.toggle('open');
}

// Auto-buka card pertama
document.addEventListener('DOMContentLoaded', function(){
    if(document.getElementById('card-0')) toggleCard(0);
});
</script>
</body>
</html>
