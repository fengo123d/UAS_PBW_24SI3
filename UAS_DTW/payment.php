<?php
session_start();
include 'koneksi.php';

// Proteksi Login
if (!isset($_SESSION['username'])) { 
    header("Location: index.php");
    exit();
}
$u = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | GOBU Coffee Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #432818;
            --secondary: #99582a;
            --bg: #fdf5e6;
            --white: #ffffff;
            --black: #1a1a1a;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; transition: all 0.3s ease; }
        
        body { 
            margin: 0; 
            background-color: var(--bg); 
            color: var(--black);
            background-image: radial-gradient(#43281810 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }

        /* HEADER PAKAI GAMBAR */
        .brand-header { text-align: center; margin-bottom: 40px; }
        .brand-header img { 
            width: 100%; 
            max-width: 400px; /* Sesuaikan ukuran gambar header kamu */
            height: auto;
        }

        .payment-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; }

        .card { 
            background: var(--white); 
            border-radius: 24px; 
            padding: 35px; 
            box-shadow: 0 10px 30px rgba(67, 40, 24, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        h3 { font-weight: 800; font-size: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        h3 i { color: var(--secondary); }

        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; text-transform: uppercase; opacity: 0.6; }
        .input-box { 
            width: 100%; 
            padding: 15px; 
            border: 2px solid #eee; 
            border-radius: 12px; 
            font-size: 15px; 
            outline: none;
        }
        .input-box:focus { border-color: var(--primary); background: #fff; }

        .method-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 15px;
            margin-bottom: 12px;
            cursor: pointer;
        }
        .method-option:hover { border-color: var(--secondary); background: #fff9f5; }
        .method-option input { margin-right: 15px; accent-color: var(--primary); width: 18px; height: 18px; }
        .method-option i { font-size: 20px; margin-right: 15px; width: 25px; text-align: center; color: var(--primary); }

        .summary-card { background: var(--primary); color: var(--white); position: sticky; top: 20px; }
        .summary-card h3 { color: var(--white); }
        
        .item-list { margin-bottom: 20px; max-height: 300px; overflow-y: auto; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; opacity: 0.9; }
        
        .total-box { 
            border-top: 1px solid rgba(255,255,255,0.2); 
            padding-top: 20px; 
            margin-top: 20px;
        }
        .total-box h2 { margin: 0; font-size: 28px; font-weight: 800; color: #d4a373; }

        .btn-pesan { 
            width: 100%; 
            background: #d4a373; 
            color: var(--primary); 
            border: none; 
            padding: 20px; 
            border-radius: 15px; 
            font-weight: 800; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-pesan:hover { background: var(--white); transform: translateY(-3px); }

        .modal { 
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0; top: 0; width: 100%; height: 100%; 
            background: rgba(26, 26, 26, 0.9); 
            backdrop-filter: blur(8px);
            align-items: center; justify-content: center; 
        }
        .modal-content { 
            background: var(--white); 
            padding: 40px; 
            border-radius: 30px; 
            text-align: center; 
            max-width: 400px; 
            width: 90%;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="brand-header">
            <img src="HEADER.PNG" alt="GOBU Coffee Header">
        </div>
        
        <form id="formCheckout">
            <div class="payment-grid">
                <div class="card">
                    <h3><i class="fa-solid fa-truck-fast"></i> Informasi Pengiriman</h3>
                    
                    <div class="input-group">
                        <label>Nama Lengkap Penerima</label>
                        <input type="text" name="nama" class="input-box" placeholder="Contoh: Fernando" required>
                    </div>

                    <div class="input-group">
                        <label>Nomor WhatsApp (Aktif)</label>
                        <input type="number" name="no_telp" class="input-box" placeholder="0812xxxx" required>
                    </div>

                    <div class="input-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" class="input-box" style="height: 100px; resize: none;" placeholder="Nama jalan, nomor rumah, atau gedung..." required></textarea>
                    </div>

                    <h3 style="margin-top: 40px;"><i class="fa-solid fa-credit-card"></i> Metode Pembayaran</h3>
                    
                    <label class="method-option">
                        <input type="radio" name="payment_method" value="QRIS" checked>
                        <i class="fa-solid fa-qrcode"></i>
                        <span>QRIS</span>
                    </label>

                    <label class="method-option">
                        <input type="radio" name="payment_method" value="BCA">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>BCA Virtual Account</span>
                    </label>

                    <label class="method-option">
                        <input type="radio" name="payment_method" value="COD">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>COD (Bayar di Tempat)</span>
                    </label>
                </div>

                <div class="card summary-card">
                    <h3><i class="fa-solid fa-receipt"></i> Ringkasan Pesanan</h3>
                    <div id="list-ringkasan" class="item-list"></div>

                    <div class="total-box">
                        <p style="margin:0; opacity: 0.7; font-size: 14px;">Total Bayar:</p>
                        <h2 id="txt-total">Rp 0</h2>
                    </div>

                    <input type="hidden" name="total_harga" id="inp-total">
                    <button type="submit" class="btn-pesan">Konfirmasi Pesanan</button>
                </div>
            </div>
        </form>
    </div>

    <div id="modalBayar" class="modal">
        <div class="modal-content">
            <div id="isiModal"></div>
            <button onclick="bayarSekarang()" class="btn-pesan">SAYA SUDAH BAYAR / KONFIRMASI</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
            let total = 0;
            let html = '';

            if(cart.length === 0) {
                alert("Keranjang kosong!");
                window.location.href = 'page2.php';
                return;
            }

            cart.forEach(item => {
                let sub = item.price * item.qty;
                total += sub;
                html += `
                    <div class="item-row">
                        <span>${item.name} <b>x${item.qty}</b></span>
                        <span>Rp ${sub.toLocaleString('id-ID')}</span>
                    </div>`;
            });

            document.getElementById('list-ringkasan').innerHTML = html;
            document.getElementById('txt-total').innerText = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('inp-total').value = total;

            document.getElementById('formCheckout').addEventListener('submit', function(e) {
                e.preventDefault();
                let method = document.querySelector('input[name="payment_method"]:checked').value;
                let modal = document.getElementById('modalBayar');
                let isi = document.getElementById('isiModal');

                if(method === "QRIS") {
                    isi.innerHTML = `<h2>Scan QRIS</h2><img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=GOBU" style="width:200px; margin:20px 0;"><p>Silakan scan untuk membayar.</p>`;
                } else if(method === "BCA") {
                    isi.innerHTML = `<h2>Transfer BCA</h2><p style="font-size:24px; font-weight:bold; color:#432818; margin:20px 0;">8830 0852 7813</p><p>A/N GOBU COFFEE</p>`;
                } else {
                    isi.innerHTML = `<h2>Konfirmasi COD</h2><p style="margin:20px 0;">Pesanan akan diproses dan dibayar saat kurir sampai di alamat Anda.</p>`;
                }

                modal.style.display = 'flex';
            });
        });

        function bayarSekarang() {
            const cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
            const form = document.getElementById('formCheckout');
            const formData = new FormData(form);
            formData.append('cart_data', JSON.stringify(cart));

            fetch('proses_bayar.php', { method: 'POST', body: formData })
            .then(() => {
                alert("✨ Pesanan Diterima! Cek Riwayat di Admin.");
                localStorage.removeItem('gobuCart');
                window.location.href = 'page2.php';
            })
            .catch(() => { window.location.href = 'page2.php'; });
        }
    </script>
</body>
</html>