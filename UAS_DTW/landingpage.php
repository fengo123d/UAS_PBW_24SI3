<?php 
// Konfigurasi untuk landing page
$css_file = "landingpage.css"; 
$current_page = "home"; 
include 'header.php'; 
?>

    <section class="promo-banner">

        <div class="promo-bg-shape"></div>
        <div class="bean-bg"></div>

        <div class="promo-content">
        
            <div class="product-item left-item">
                <a href="page2.php">
                     <img src="gonu250g'.png" alt="Gobu Robusta 250g" class="promo-img big-pack" >
                </a>

                <div class="price-tag1 tag-left">
                    <div class="price-row">
                        <div class="price-strikethrough1">25.000</div> <div class="price-final">21.000</div> </div>
                     <h2>
                    <div class="a">
                        <span class="regular">GOBU</span>
                        <span class="bold">Robusta 250G</span>
                    </div>
                    </h2>
                </div>
            </div>

            <div class="product-item right-item">

                <div class="price-tag2 tag-right">
                    <div class="price-row right-align">
                        <div class="price-strikethrough2">15.000</div> <div class="price-final">12.000</div> </div>
                     <h2>
                     <div class="b">
                        <span class="regular">GOBU</span>
                        <span class="bold">Robusta 100G</span>
                    </h2>
                    
                </div>

                <a href="page2.php">
                    <img src="gobu100g 1.png" alt="Gobu Robusta 100g" class="promo-img small-pack">
                </a>
            </div>

        </div>
    </section>
    <?php include 'floating_buttons.php'; ?>
<?php include 'footer.php'; ?>