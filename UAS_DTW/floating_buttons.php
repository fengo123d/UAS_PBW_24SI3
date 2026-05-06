<div class="gobu-floating-container">
    <a href="javascript:void(0);" onclick="openLogoutModal()" class="gobu-action-btn gobu-logout-btn" title="Logout">
        <i class="fa-solid fa-right-from-bracket"></i>
    </a>

    <a href="profile.php" class="gobu-action-btn gobu-profile-btn" title="Profile">
        <i class="fa-solid fa-user"></i>
    </a>

    <a href="cart.php" class="gobu-cart-btn">
        <div class="gobu-cart-badge" id="global-cart-count">0</div>
        <img src="cart.png" alt="Cart" style="width: 30px !important; height: 30px !important; display: block !important; filter: brightness(0) invert(1);">
    </a>

    <a href="https://wa.me/6285278134976" target="_blank" class="gobu-wa-btn">
        <img src="10464236.png" alt="WhatsApp" style="width: 80px !important; height: 80px !important; display: block !important;">
    </a>
</div>

<div id="logoutModal" class="gobu-modal-overlay">
    <div class="gobu-modal-content">
        <div class="gobu-modal-header">
            <div class="gobu-modal-icon">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
        </div>
        <div class="gobu-modal-body">
            <h3>Ingin Istirahat?</h3>
            <p>Apakah Anda yakin ingin keluar dari akun <strong>GOBU Coffee</strong> Anda?</p>
        </div>
        <div class="gobu-modal-footer">
            <button class="gobu-btn-cancel" onclick="closeLogoutModal()">BATAL</button>
            <button class="gobu-btn-confirm" onclick="proceedLogout()">LOGOUT</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* --- Floating Container --- */
.gobu-floating-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 999999;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.gobu-action-btn {
    width: 50px;
    height: 50px;
    background-color: #69452d;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 20px;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}

.gobu-logout-btn {
    background-color: #a0785a; /* Cokelat muda agar tidak terlalu mencolok seperti merah */
}

.gobu-wa-btn img {
    border-radius: 50%;
    background-color: #69452d;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}

.gobu-cart-btn {
    width: 65px;
    height: 65px;
    background-color: #69452d;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    transition: 0.3s ease;
    text-decoration: none;
}

.gobu-action-btn:hover, .gobu-cart-btn:hover, .gobu-wa-btn:hover img {
    transform: scale(1.1);
}

.gobu-cart-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #ff4d4d;
    color: white;
    font-size: 12px;
    font-weight: bold;
    min-width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px solid white;
}

/* --- Custom Modal Logout --- */
.gobu-modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Gelap transparan */
    backdrop-filter: blur(5px); /* Efek blur estetik */
    display: none; /* Default hidden */
    justify-content: center;
    align-items: center;
    z-index: 1000000;
    transition: opacity 0.3s ease;
}

.gobu-modal-content {
    background: white;
    padding: 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 350px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.gobu-modal-icon {
    width: 70px;
    height: 70px;
    background: #f7f1e6;
    color: #69452d;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 30px;
    margin: 0 auto 20px auto;
}

.gobu-modal-body h3 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 1.4rem;
}

.gobu-modal-body p {
    color: #777;
    font-size: 0.95rem;
    margin-bottom: 25px;
    line-height: 1.5;
}

.gobu-modal-footer {
    display: flex;
    gap: 12px;
}

.gobu-modal-footer button {
    flex: 1;
    padding: 12px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    transition: 0.2s;
}

.gobu-btn-cancel {
    background: #f4f4f4;
    color: #666;
}

.gobu-btn-confirm {
    background: #69452d;
    color: white;
}

.gobu-btn-cancel:hover { background: #e8e8e8; }
.gobu-btn-confirm:hover { background: #523623; }
</style>

<script>
// Fungsi update angka keranjang
function updateGlobalCartCount() {
    let cart = JSON.parse(localStorage.getItem('gobuCart')) || [];
    let total = 0;
    cart.forEach(item => total += item.qty);
    let badge = document.getElementById('global-cart-count');
    if(badge) badge.innerText = total;
}

// Buka Modal
function openLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
}

// Tutup Modal
function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}

// Proses Logout
function proceedLogout() {
    window.location.href = "logout.php";
}

// Tutup modal jika klik di luar kotak modal
window.onclick = function(event) {
    let modal = document.getElementById('logoutModal');
    if (event.target == modal) {
        closeLogoutModal();
    }
}

document.addEventListener('DOMContentLoaded', updateGlobalCartCount);
</script>