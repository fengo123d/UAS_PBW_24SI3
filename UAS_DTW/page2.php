<?php 
header("Cache-Control: no-cache, must-revalidate");
include 'koneksi.php'; 

// Ambil data produk terbaru dari database
$query100 = mysqli_query($conn, "SELECT * FROM produk WHERE varian = '100g' ORDER BY id DESC LIMIT 1");
$row100 = mysqli_fetch_assoc($query100);

$query250 = mysqli_query($conn, "SELECT * FROM produk WHERE varian = '250g' ORDER BY id DESC LIMIT 1");
$row250 = mysqli_fetch_assoc($query250);

$css_file = "style2.css"; 
$current_page = "produk"; 
include 'header.php'; 
?>

<style>
    /* FIX TAMPILAN AGAR TIDAK NIMPA FOTO */
    .product-container, .product-container1 {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        max-width: 1200px;
        margin: 0 auto;
        gap: 20px;
    }
    .product-details, .product-details1 {
        flex: 1.2 !important;
        text-align: left !important;
        padding: 20px !important;
    }
    /* Memastikan elemen produk bawah (250g) berbaris rapi ke kiri */
    .product-details1 h2, .product-details1 p, .counter-box2, .total2, .price2, .grind-options2 {
        margin-right: 0 !important;
        margin-left: 0 !important;
        text-align: left !important;
        justify-content: flex-start !important;
    }
    .product-image-box, .product-image-box1 {
        flex: 1 !important;
        display: flex !important;
        justify-content: center !important;
    }
    .prod-img { width: 100%; max-width: 480px; border-radius: 15px; }

    /* STYLE TOMBOL PILIHAN GILINGAN (GRIND) */
    .grind-options, .grind-options2 { display: flex; gap: 10px; margin: 15px 0; }
    .grind-btn {
        padding: 8px 20px;
        border: 2px solid #6b4c35;
        background: #e6d9c8;
        color: #3e220e;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }
    .grind-btn.active { background: #3e220e; color: #fff; }
    .type-label { font-weight: bold; color: #3a2c17; display: block; margin-top: 10px; }
</style>

<section class="product-section-100g">
    <div class="product-container">
        <div class="product-image-box">
            <img src="upload/<?php echo $row100['gambar']; ?>" class="prod-img">
        </div>
        <div class="product-details">
            <h2><?php echo strtoupper($row100['nama_produk']); ?></h2>
            <p><?php echo $row100['deskripsi']; ?></p>
            
            <span class="type-label">Pilih Tipe Kopi:</span>
            <div class="grind-options">
                <button class="grind-btn active" onclick="selectGrind(this, 'grind100')">KASAR</button>
                <button class="grind-btn" onclick="selectGrind(this, 'grind100')">HALUS</button>
                <input type="hidden" id="grind100" value="KASAR">
            </div>

            <div class="price1">Harga: Rp <?php echo number_format($row100['harga'], 0, ',', '.'); ?></div>
            <div class="counter-box">
                <button class="counter-btn" onclick="changeQty100('qty100', -1, <?php echo $row100['harga']; ?>, 'total100')">−</button>
                <span id="qty100">0</span>
                <button class="counter-btn" onclick="changeQty100('qty100', 1, <?php echo $row100['harga']; ?>, 'total100')">+</button>
            </div>
            <div class="total1">Total: Rp <span id="total100">0</span></div>
            <div class="action-area">
                <button class="buy-btn" onclick="addToCart(<?php echo (int)$row100['id']; ?>, '<?php echo $row100['nama_produk']; ?>', <?php echo $row100['harga']; ?>, '<?php echo $row100['gambar']; ?>', 'qty100', 'grind100')">Tambahkan Pesanan</button>
            </div>
        </div>
    </div>
</section>

<div class="pembatas"></div>

<section class="product-section-250g">
    <div class="product-container1">
        <div class="product-details1">
            <h2><?php echo strtoupper($row250['nama_produk']); ?></h2>
            <p><?php echo $row250['deskripsi']; ?></p>

            <span class="type-label">Pilih Tipe Kopi:</span>
            <div class="grind-options2">
                <button class="grind-btn active" onclick="selectGrind(this, 'grind250')">KASAR</button>
                <button class="grind-btn" onclick="selectGrind(this, 'grind250')">HALUS</button>
                <input type="hidden" id="grind250" value="KASAR">
            </div>

            <div class="price2">Harga: Rp <?php echo number_format($row250['harga'], 0, ',', '.'); ?></div>
            <div class="counter-box2">
                <button class="counter-btn" onclick="changeQty250('qty250', -1, <?php echo $row250['harga']; ?>, 'total250')">−</button>
                <span id="qty250">0</span>
                <button class="counter-btn" onclick="changeQty250('qty250', 1, <?php echo $row250['harga']; ?>, 'total250')">+</button>
            </div>
            <div class="total2">Total: Rp <span id="total250">0</span></div>
            <div class="action-area">
                <button class="buy-btn" onclick="addToCart(<?php echo (int)$row250['id']; ?>, '<?php echo $row250['nama_produk']; ?>', <?php echo $row250['harga']; ?>, '<?php echo $row250['gambar']; ?>', 'qty250', 'grind250')">Tambahkan Pesanan</button>
            </div>
        </div>
        <div class="product-image-box1">
            <img src="upload/<?php echo $row250['gambar']; ?>" class="prod-img">
        </div>
    </div>
</section>

<?php include 'floating_buttons.php'; ?>
<script src="animasi.js"></script>

<script>
// Fungsi toggle tombol Kasar/Halus
function selectGrind(btn, inputId) {
    let container = btn.parentElement;
    container.querySelectorAll('.grind-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(inputId).value = btn.innerText;
}

function addToCart(id, name, price, image, qtyId, grindId) {
    let chosenQty = parseInt(document.getElementById(qtyId).innerText);
    let chosenGrind = document.getElementById(grindId).value;

    if (chosenQty <= 0) { 
        alert("Pilih jumlah dulu!"); 
        return; 
    }

    let cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
    let pID = parseInt(id); 

    // Cari apakah barang dengan ID dan Tipe Gilingan yang sama sudah ada
    let index = cart.findIndex(item => item.id === pID && item.grind === chosenGrind);

    if (index !== -1) {
        cart[index].qty += chosenQty;
    } else {
        cart.push({ 
            id: pID, 
            name: name + " (" + chosenGrind + ")", 
            price: price, 
            image: image, 
            qty: chosenQty,
            grind: chosenGrind 
        });
    }

    localStorage.setItem('gobuCart', JSON.stringify(cart));
    alert(name + " " + chosenGrind + " berhasil masuk keranjang!");
    
    // Reset angka counter setelah berhasil
    document.getElementById(qtyId).innerText = "0";
    let totalTarget = (qtyId === 'qty100') ? 'total100' : 'total250';
    document.getElementById(totalTarget).innerText = "0";
}
</script>

<?php include 'footer.php'; ?>