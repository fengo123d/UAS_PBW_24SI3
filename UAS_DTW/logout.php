
<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<body>
    <script>
        // Hapus data di browser saat ini agar tidak tertukar akun
        localStorage.removeItem('gobuCart');
        
        alert('Logout berhasil!');
        // GANTI 'index.php' jika nama file login kamu berbeda
        window.location.href = 'login.php'; 
    </script>
</body>
</html>