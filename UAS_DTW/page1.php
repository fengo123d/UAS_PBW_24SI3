<?php 
$css_file = "style.css"; 
$current_page = "kopi"; 
include 'header.php'; 
?>

    <section class="section-one">
        <div class="text-box fade-up">
            <h2>
                <span class="bold">KOPI</span>
                <span class="regular">KAMI</span>
            </h2>
            <p>
                Di GOBU Coffee, setiap tegukan Robusta adalah cerita tentang keberanian. 
                Kami menghadirkan kopi yang tegas, pekat, dan lahir dari tanah terbaik 
                yang dirawat petani lokal dengan kesungguhan.
            </p>
        </div>

        <div class="image-box fade-up">
            <img src="Gobu KOPI.PNG" class="main-image" alt="Gobu Coffee Pack">
        </div>
    </section>

    <section class="section-two" id="produk">
        <div class="section-two-left fade-up">
            <h2>
                <span class="bold">TEMUKAN</span>
                <span class="regular">KOPIMU</span>
            </h2>
            <p>
                Cari kopi yang pas? Tenang, kami sudah menemukannya untukmu. Satu racikan, dipanggang dengan tepat, digiling dengan penuh perhatian, dan cocok untuk suasana apa pun.
            </p>
        </div>

        <div class="section-two-right">
            <img src="ICON COFFEE.png" class="middle-image rotate-enter">
        </div>
    </section>

    <section class="brew-section">
        <h2 class="fade-up">
            <span class="bold">PETUNJUK</span>
            <span class="regular">PENYEDUHAN</span>
        </h2>

        <div class="brew-grid">
            <div class="brew-card fade-up">
                <img src="i1.png">
                <h3>COFFEE PRESS</h3>
                <p>Rasa tebal, aroma dalam, cocok untuk tiap hari.</p>
            </div>
            <div class="brew-card fade-up">
                <img src="i2.png">
                <h3>POUR-OVER</h3>
                <p>Bersih, seimbang, dan ringan.</p>
            </div>
            <div class="brew-card fade-up">
                <img src="i3.png">
                <h3>ICED BREW</h3>
                <p>Disajikan lebih segar dan dingin.</p>
            </div>
            <div class="brew-card fade-up">
                <img src="i4.png">
                <h3>ANYWAY YOU LIKE</h3>
                <p>Tidak ada aturan. Eksperimen saja.</p>
            </div>
        </div>
    </section>

    <?php include 'floating_buttons.php'; ?>

<?php include 'footer.php'; ?>