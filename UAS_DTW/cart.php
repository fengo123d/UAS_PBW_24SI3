<?php 
session_start();
include 'koneksi.php';

// 1. Proteksi login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$u = $_SESSION['username'];

// 2. Ambil data database untuk cadangan (jika localStorage kosong)
$get_cart = mysqli_query($conn, "SELECT * FROM keranjang WHERE username='$u'");
$db_items = [];
if($get_cart) {
    while($row = mysqli_fetch_assoc($get_cart)) {
        $db_items[] = [
            'name' => $row['nama_produk'],
            'price' => (int)$row['harga'],
            'qty' => (int)$row['qty'],
            'image' => $row['image']
        ];
    }
}

$css_file = "style2.css"; 
$current_page = "cart"; 
include 'header.php'; 
?>

<div style="background-color: #fdf5e6; min-height: 100vh; padding: 40px 20px; font-family: 'Poppins', sans-serif;">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        <div style="text-align: left; margin-bottom: 40px;">
            <h1 style="margin:0; font-weight: 800; letter-spacing: 2px; color: #432818;">GOBU</h1>
            <p style="margin:0; font-size: 12px; letter-spacing: 8px; color: #432818; margin-top: -5px;">COFFEE</p>
        </div>

        <h2 style="font-size: 24px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 30px; font-weight: bold;">Shopping Cart</h2>

        <div id="cart-items-list"></div>

        <div style="margin-top: 50px;">
            <button onclick="goToPayment()" style="display: block; width: 100%; background: #1a1a1a; color: white; border: none; padding: 18px; font-size: 18px; font-weight: bold; letter-spacing: 2px; cursor: pointer; text-transform: uppercase;">
                LANJUT KE PEMBAYARAN
            </button>
        </div>

        <div style="margin-top: 80px;">
            <h2 style="font-size: 24px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 25px; font-weight: bold; color: #432818;">History Pesanan & Lacak</h2>
            <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                <?php
                $history = mysqli_query($conn, "SELECT * FROM history_pesanan WHERE username='$u' ORDER BY tanggal DESC");
                if ($history && mysqli_num_rows($history) > 0) {
                    while ($row = mysqli_fetch_assoc($history)) {
                        $id_p = $row['id_pesanan'];
                        $status = $row['status_pengiriman'];
                        $color = ($status == 'Diproses') ? '#d35400' : '#27ae60';
                        echo "
                        <div style='display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding: 15px 0;'>
                            <div>
                                <b style='display:block;'>Order #GB-$id_p</b>
                                <small style='color:#888;'>{$row['tanggal']}</small>
                            </div>
                            <div style='text-align:center;'>
                                <span style='color:$color; font-weight:bold; font-size:12px;'>$status</span><br>
                                <small style='text-decoration:underline; cursor:pointer; color:#69452d;' onclick='bukaLacak(\"$id_p\")'>Lacak Lokasi</small>
                            </div>
                            <b style='color:#432818;'>Rp " . number_format($row['total_bayar'], 0, ',', '.') . "</b>
                        </div>";
                    }
                } else {
                    echo "<p style='text-align:center; color:#888;'>Belum ada riwayat pesanan.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div id="modalLacak" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 20px; width: 90%; max-width: 400px; position: relative;">
        <span onclick="tutupLacak()" style="position: absolute; right: 20px; top: 15px; cursor: pointer; font-size: 24px; font-weight: bold; color: #888;">&times;</span>
        <h3 style="color: #432818; margin-top: 0;">Lacak Order <span id="lacakID"></span></h3>
        <hr style="opacity: 0.2;">
        <div style="border-left: 3px solid #e0d8cc; margin-left: 15px; padding-left: 25px; position: relative; margin-top: 20px;">
            <div style="margin-bottom: 30px; position: relative;">
                <div style="position: absolute; left: -32px; top: 0; width: 12px; height: 12px; background: #27ae60; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #27ae60;"></div>
                <b style="display: block; font-size: 14px; color: #27ae60;">Pesanan Dikonfirmasi</b>
                <p style="margin: 0; font-size: 12px; color: #888;">Tim GOBU Coffee sedang memproses pesananmu.</p>
            </div>
            <div style="margin-bottom: 30px; position: relative;">
                <div style="position: absolute; left: -32px; top: 0; width: 12px; height: 12px; background: #d35400; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #d35400;"></div>
                <b style="display: block; font-size: 14px;">Kurir Menuju Alamat</b>
                <p style="margin: 0; font-size: 12px; color: #888;">Paket berada di daerah <b>Medan Utara</b>.</p>
            </div>
        </div>
        <button onclick="tutupLacak()" style="margin-top: 30px; width: 100%; background: #432818; color: white; border: none; padding: 15px; border-radius: 10px; cursor: pointer; font-weight: bold;">Tutup</button>
    </div>
</div>

<script>
// 1. Render Keranjang
function renderCart() {
    let cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
    if (cart.length === 0) {
        cart = <?php echo json_encode($db_items); ?>;
        localStorage.setItem('gobuCart', JSON.stringify(cart));
    }

    let listDiv = document.getElementById('cart-items-list');
    if (cart.length === 0) {
        listDiv.innerHTML = '<div style="text-align:center; padding: 50px; background:white; border-radius:15px;"><p style="color:#888;">Keranjang belanja Anda kosong.</p></div>';
        return;
    }

    listDiv.innerHTML = '';
    cart.forEach((item, index) => {
        let subtotal = item.price * item.qty;
        
        // --- LOGIKA GAMBAR LAMA ---
        // Jika nama produk mengandung kata "250", pakai poster 250, selain itu pakai Gobu KOPI
        let imgSrc = (item.name.includes("250")) ? "GOBU 250G POSTER.png" : "Gobu KOPI.PNG";

        listDiv.innerHTML += `
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; align-items: center; margin-bottom: 20px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="${imgSrc}" style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px;">
                    <div>
                        <h4 style="margin: 0; font-size: 17px; text-transform: uppercase;">${item.name}</h4>
                        <p style="margin: 5px 0 0; font-size: 13px; color: #6b4c35; font-weight: bold;">
                            Tipe: ${item.grind ? item.grind : 'KASAR'}
                        </p>
                    </div>
                </div>
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                    <button onclick="updateQty(${index}, -1)" style="background: #432818; color: white; border: none; width: 25px; height: 25px; border-radius: 5px; cursor: pointer;">-</button>
                    <span style="font-weight: bold;">${item.qty}</span>
                    <button onclick="updateQty(${index}, 1)" style="background: #432818; color: white; border: none; width: 25px; height: 25px; border-radius: 5px; cursor: pointer;">+</button>
                </div>
                <div style="text-align: center;">Rp ${item.price.toLocaleString('id-ID')}</div>
                <div style="text-align: right; font-weight: 800; color: #432818;">Rp ${subtotal.toLocaleString('id-ID')}</div>
            </div>`;
    });
}

// 2. Update Qty
function updateQty(index, change) {
    let cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
    cart[index].qty += change;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    
    localStorage.setItem('gobuCart', JSON.stringify(cart));
    renderCart();

    let formData = new FormData();
    formData.append('name', cart[index] ? cart[index].name : '');
    formData.append('qty', change);
    fetch('save_to_cart.php', { method: 'POST', body: formData });
}

// 3. Modal Lacak & Navigasi
function bukaLacak(id) {
    document.getElementById('lacakID').innerText = "#GB-" + id;
    document.getElementById('modalLacak').style.display = 'flex';
}

function tutupLacak() {
    document.getElementById('modalLacak').style.display = 'none';
}

function goToPayment() {
    let cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
    if(cart.length === 0) return alert("Keranjang kosong!");
    window.location.href = 'payment.php';
}

document.addEventListener('DOMContentLoaded', renderCart);
</script>

<?php include 'footer.php'; ?>