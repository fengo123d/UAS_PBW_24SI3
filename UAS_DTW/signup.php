<?php
include 'koneksi.php';

if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi Otomatis

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Akun berhasil dibuat! Silakan Sign In.'); window.location='signin.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>

        body { font-family: 'Poppins', sans-serif; background:#f7f1e6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 380px; text-align: center; }
        .logo { font-weight: 800; font-size: 1.5rem; letter-spacing: 3px; margin-bottom: 20px; }
        .form-section { display: none; }
        .form-section.active { display: block; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #eee; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 14px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-black { background: #1a1a1a; color: white; }
        .btn-gray { background: #f4f4f4; color: #333; margin-top: 5px; }
        .link-text { font-size: 0.8rem; color: #888; margin-top: 15px; cursor: pointer; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <img src="logo gobu.png" alt="Gobu Coffee Logo" class="logo-img">
        <h2>SIGN UP</h2>
        <p class="msg">Don't have any account? Let's create a new one!</p>
        <form method="POST">
            <input type="text" name="username" placeholder="Create Username" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="signup" class="btn">CREATE ACCOUNT</button>
        </form>
    </div>
</body>
</html>