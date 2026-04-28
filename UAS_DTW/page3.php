<?php 
// Konfigurasi untuk memanggil CSS style3.css dan navigasi aktif
$css_file = "style3.css"; 
$current_page = "tentang"; 
include 'header.php'; 
?>

    <section class="section-one">
        <div class="text-box">
            <h2>
                <span class="bold">PERJALANAN</span>
                <span class="regular">KAMI</span>
            </h2>

            <p>
                Di balik setiap biji kopi Gobu, ada perjalanan panjang yang dimulai dari tanah subur dan tangan para petani yang merawatnya dengan sepenuh hati.
            </p>

            <p>
                Kami percaya bahwa kopi bukan hanya minuman, tapi pengalaman.
            </p>
        </div>

        <div class="GOBU_COFFEE">
            <p>GOBU COFFEE</p>
        </div>
    </section>

    <div class="pembatas"></div>

    <section class="section-two">
        <div class="container">
            <h2>
                <span class="bold">TOKO</span>
                <span class="regular">KAMI</span>
            </h2>

            <div class="shop-wrapper">
                
                <div class="shop-card">
                    <img src="image 2.png" alt="Shopee">
                    <p class="shop-text">gobucoffee</p>
                </div>

                <div class="shop-card">
                    <img src="image 3.png" alt="Tokopedia">
                    <p class="shop-text">gobucoffee</p>
                </div>

            </div>
        </div>
    </section>

     <?php include 'floating_buttons.php'; ?>

<?php include 'footer.php'; ?>