<?php
session_start();
include 'koneksi.php';

// Otomatis mendeteksi nama file ini agar tidak 404 saat redirect
$current_file = basename($_SERVER['PHP_SELF']);

// --- LOGIKA PEMROSESAN FORM ---

// 1. SIGN UP (Daftar)
if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' OR email='$email'");
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username atau Email sudah terdaftar!'); window.location.href='$current_file';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$pass')");
        // Setelah daftar, kembali ke tampilan awal (Sign In)
        echo "<script>alert('Akun berhasil dibuat! Silakan Sign In.'); window.location.href='$current_file';</script>";
    }
    exit();
}

// 2. SIGN IN (Login)
if (isset($_POST['login'])) {
    $user_input = mysqli_real_escape_string($conn, $_POST['user_input']);
    $pass = $_POST['password'];
    
    // Cek berdasarkan username ATAU email agar fleksibel
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user_input' OR email='$user_input'");
    $row = mysqli_fetch_assoc($result);
    
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['username'] = $row['username'];
        header("Location: landingpage.php");
        exit();
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location.href='$current_file';</script>";
    }
    exit();
}

// 3. RESET PASSWORD (Update MySQL)
if (isset($_POST['update_pass'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email_target']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.location.href='$current_file';</script>";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        // Update database berdasarkan email yang diinput di Step 1
        $update = mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE email='$email'");
        
        if($update) {
            echo "<script>alert('Password berhasil diubah! Silakan Sign In kembali.'); window.location.href='$current_file';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui database!'); window.location.href='$current_file';</script>";
        }
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access - GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background:#f7f1e6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 380px; text-align: center; }
        .logo-img { max-width: 140px; margin-bottom: 20px; }
        .form-section { display: none; }
        .form-section.active { display: block; }
        .pass-wrapper { position: relative; width: 100%; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #eee; border-radius: 8px; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        .toggle-btn { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; }
        .btn { width: 100%; padding: 14px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; margin-top: 10px; transition: 0.3s; font-family: 'Poppins', sans-serif; }
        .btn-black { background: #1a1a1a; color: white; }
        .btn-gray { background: #f4f4f4; color: #333; margin-top: 15px; }
        .link-text { font-size: 0.8rem; color: #888; margin-top: 15px; cursor: pointer; text-decoration: underline; }
        h2 { font-weight: 700; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="auth-card">
    <img src="logo gobu.png" alt="Gobu Coffee Logo" class="logo-img">
    
    <div id="signin-section" class="form-section active">
        <h2>SIGN IN</h2>
        <form method="POST">
            <input type="text" name="user_input" placeholder="Username or Email" required>
            <div class="pass-wrapper">
                <input type="password" name="password" class="pass-input" placeholder="Password" required>
                <i class="fa-solid fa-eye toggle-btn" onclick="togglePass(this)"></i>
            </div>
            <button type="submit" name="login" class="btn btn-black">SIGN IN</button>
        </form>
        <p class="link-text" onclick="showSection('signup-section')">Don't have an account? Let's create a new one!</p>
        <p class="link-text" onclick="showSection('forgot-section')">Forgot Password?</p>
    </div>

    <div id="signup-section" class="form-section">
        <h2>SIGN UP</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Your Email Address" required>
            <input type="text" name="username" placeholder="New Username" required>
            <div class="pass-wrapper">
                <input type="password" name="password" class="pass-input" placeholder="New Password" required>
                <i class="fa-solid fa-eye toggle-btn" onclick="togglePass(this)"></i>
            </div>
            <button type="submit" name="register" class="btn btn-black">CREATE ACCOUNT</button>
        </form>
        <button class="btn btn-gray" onclick="showSection('signin-section')">BACK TO SIGN IN</button>
    </div>

    <div id="forgot-section" class="form-section">
        <h2>RESET PASSWORD</h2>
        
        <div id="step-1">
            <p style="font-size: 0.8rem; color: #666;">Enter your registered email.</p>
            <input type="email" id="forgot-email-input" placeholder="Email@example.com">
            <button type="button" class="btn btn-black" onclick="goToStep2()">SEND OTP</button>
        </div>

        <div id="step-2" style="display: none;">
            <p style="font-size: 0.8rem; color: #666;">OTP sent! Hint: 123456</p>
            <input type="text" id="otp-input" placeholder="Enter OTP" maxlength="6" style="text-align: center; letter-spacing: 5px;">
            <button type="button" class="btn btn-black" onclick="goToStep3()">VERIFY OTP</button>
        </div>

        <div id="step-3" style="display: none;">
            <form method="POST">
                <input type="hidden" name="email_target" id="hidden-email">
                <div class="pass-wrapper">
                    <input type="password" name="new_password" class="pass-input" placeholder="New Password" required>
                    <i class="fa-solid fa-eye toggle-btn" onclick="togglePass(this)"></i>
                </div>
                <div class="pass-wrapper">
                    <input type="password" name="confirm_password" class="pass-input" placeholder="Repeat Password" required>
                    <i class="fa-solid fa-eye toggle-btn" onclick="togglePass(this)"></i>
                </div>
                <button type="submit" name="update_pass" class="btn btn-black">UPDATE PASSWORD</button>
            </form>
        </div>
        <button class="btn btn-gray" onclick="showSection('signin-section')">CANCEL</button>
    </div>
</div>

<script>
    function showSection(id) {
        document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
        document.getElementById(id).classList.add('active');
        // Reset steps jika masuk ke forgot password
        if(id === 'forgot-section') {
            document.getElementById('step-1').style.display = 'block';
            document.getElementById('step-2').style.display = 'none';
            document.getElementById('step-3').style.display = 'none';
        }
    }

    function goToStep2() {
        const email = document.getElementById('forgot-email-input').value;
        if(email === "" || !email.includes('@')) {
            alert("Masukkan email yang valid!");
            return;
        }
        document.getElementById('hidden-email').value = email;
        document.getElementById('step-1').style.display = 'none';
        document.getElementById('step-2').style.display = 'block';
    }

    function goToStep3() {
        const otp = document.getElementById('otp-input').value;
        if(otp === "123456") {
            document.getElementById('step-2').style.display = 'none';
            document.getElementById('step-3').style.display = 'block';
        } else {
            alert("OTP Salah! Gunakan 123456");
        }
    }

    function togglePass(btn) {
        const input = btn.parentElement.querySelector('.pass-input');
        if (input.type === "password") {
            input.type = "text";
            btn.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            input.type = "password";
            btn.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
</script>

</body>
</html>