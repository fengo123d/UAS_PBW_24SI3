<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $css_file; ?>">
</head>
<body>
    <div class="top-rectangle"></div> 
    <header class="header">
        <a href="landingpage.php">
            <img src="HEADER.png" alt="Gobu Coffee Logo" class="logo-img">
        </a>
    </header>
    <nav class="navbar">
        <a href="page1.php" class="<?php echo ($current_page == 'kopi') ? 'active' : 'tes'; ?>">KOPI</a>
        <a href="page2.php" class="<?php echo ($current_page == 'produk') ? 'active' : 'tes'; ?>">PRODUK</a>
        <a href="page3.php" class="<?php echo ($current_page == 'tentang') ? 'active' : 'tes'; ?>">TENTANG KAMI</a>
    </nav>