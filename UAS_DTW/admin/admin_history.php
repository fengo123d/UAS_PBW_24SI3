<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Riwayat Pesanan GOBU</title>
    <style>
        body { font-family: sans-serif; background: #fdf5e6; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        th { background: #432818; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; }
        tr:hover { background: #fff9f5; }
        .status { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #d35400; color: white; }
    </style>
</head>
<body>

    <h2 style="color: #432818;">Data Pesanan Masuk (Admin)</h2>
    
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pembeli & WA</th>
                <th>Alamat</th>
                <th>Produk & Qty</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM history_pesanan ORDER BY tanggal DESC");
            while($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                    <td>{$row['tanggal']}</td>
                    <td>
                        <b>{$row['nama_pembeli']}</b><br>
                        <small>{$row['no_telp']}</small>
                    </td>
                    <td style='max-width:200px;'>{$row['alamat']}</td>
                    <td>{$row['nama_produk']}</td>
                    <td><b>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</b></td>
                    <td>{$row['metode_bayar']}</td>
                    <td><span class='status'>{$row['status_pengiriman']}</span></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>