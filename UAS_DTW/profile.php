<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Jika belum login, tendang balik ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Sesuaikan dengan nama file login kamu
    exit();
}

$current_user = $_SESSION['username'];
$self = basename($_SERVER['PHP_SELF']);

// Ambil data user dari database untuk ditampilkan di form
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$current_user'");
$data = mysqli_fetch_assoc($query_user);

// --- LOGIKA UPDATE USERNAME ---
if (isset($_POST['update_profile'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    
    // Cek apakah username baru sudah dipakai orang lain
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$new_username' AND username != '$current_user'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location.href='$self';</script>";
    } else {
        $update = mysqli_query($conn, "UPDATE users SET username='$new_username' WHERE username='$current_user'");
        if ($update) {
            $_SESSION['username'] = $new_username; // Update session agar tetap login
            echo "<script>alert('Username berhasil diperbarui!'); window.location.href='$self';</script>";
        }
    }
    exit();
}

// --- LOGIKA RESET PASSWORD ---
if (isset($_POST['update_pass'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email_target']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.location.href='$self';</script>";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE email='$email'");
        
        if($update) {
            echo "<script>alert('Password berhasil diubah!'); window.location.href='$self';</script>";
        } else {
            echo "<script>alert('Gagal update database!'); window.location.href='$self';</script>";
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
    <title>My Profile - GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background:#f7f1e6; margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: white; width: 90%; max-width: 500px; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); text-align: center; }
        .profile-icon { font-size: 60px; color: #69452d; margin-bottom: 10px; }
        h1 { font-weight: 700; color: #333; margin-bottom: 5px; }
        p.user-email { color: #888; font-size: 0.9rem; margin-bottom: 30px; }
        
        .section-title { text-align: left; font-weight: 600; color: #69452d; margin: 20px 0 10px 0; font-size: 0.9rem; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #eee; border-radius: 8px; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        .pass-wrapper { position: relative; width: 100%; }
        .toggle-btn { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; }
        
        .btn { width: 100%; padding: 14px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; margin-top: 10px; transition: 0.3s; font-family: 'Poppins', sans-serif; }
        .btn-black { background: #1a1a1a; color: white; }
        .btn-black:hover { opacity: 0.9; }
        .btn-outline { background: transparent; border: 1px solid #1a1a1a; color: #1a1a1a; margin-top: 15px; }
        
        .step-box { background: #fafafa; padding: 15px; border-radius: 10px; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-icon"><i class="fa-solid fa-circle-user"></i></div>
    <h1>MY PROFILE</h1>
    <p class="user-email"><?php echo $data['email']; ?></p>

    <div class="section-title">Edit Personal Info</div>
    <form method="POST">
        <input type="text" name="new_username" value="<?php echo $data['username']; ?>" placeholder="Username" required>
        <button type="submit" name="update_profile" class="btn btn-black">SAVE CHANGES</button>
    </form>

    <div class="section-title">Security Settings</div>
    
    <div id="pw-step-1">
        <button type="button" class="btn btn-outline" onclick="startPwReset()">CHANGE PASSWORD</button>
    </div>

    <div id="pw-step-2" style="display:none;" class="step-box">
        <p style="font-size: 0.8rem;">Confirm your email to get OTP</p>
        <input type="email" id="email-confirm" value="<?php echo $data['email']; ?>" readonly style="background:#eee;">
        <button type="button" class="btn btn-black" onclick="sendOtp()">SEND OTP</button>
    </div>

    <div id="pw-step-3" style="display:none;" class="step-box">
        <p style="font-size: 0.8rem; font-weight:bold; color:#d35400;">OTP: 123456</p>
        <input type="text" id="otp-input" placeholder="Enter 6-digit OTP" maxlength="6" style="text-align:center; letter-spacing:3px;">
        <button type="button" class="btn btn-black" onclick="verifyOtp()">VERIFY</button>
    </div>

    <div id="pw-step-4" style="display:none;" class="step-box">
        <form method="POST">
            <input type="hidden" name="email_target" value="<?php echo $data['email']; ?>">
            <div class="pass-wrapper">
                <input type="password" name="new_password" class="pass-input" placeholder="New Password" required>
                <i class="fa-solid fa-eye toggle-btn" onclick="togglePass(this)"></i>
            </div>
            <div class="pass-wrapper">
                <input type="password" name="confirm_password" class="pass-input" placeholder="Confirm Password" required>
                <i class="fa-solid fa-eye toggle-btn" onclick="togglePass(this)"></i>
            </div>
            <button type="submit" name="update_pass" class="btn btn-black">UPDATE PASSWORD</button>
        </form>
    </div>

    <a href="landingpage.php" style="display:block; margin-top:30px; color:#888; text-decoration:none; font-size:0.8rem;">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
    </a>
</div>

<script>
    function startPwReset() {
        document.getElementById('pw-step-1').style.display = 'none';
        document.getElementById('pw-step-2').style.display = 'block';
    }

    function sendOtp() {
        document.getElementById('pw-step-2').style.display = 'none';
        document.getElementById('pw-step-3').style.display = 'block';
    }

    function verifyOtp() {
        const otp = document.getElementById('otp-input').value;
        if(otp === "123456") {
            document.getElementById('pw-step-3').style.display = 'none';
            document.getElementById('pw-step-4').style.display = 'block';
        } else {
            alert("OTP Salah!");
        }
    }

    function togglePass(btn) {
        const input = btn.parentElement.querySelector('.pass-input');
        input.type = (input.type === "password") ? "text" : "password";
        btn.classList.toggle("fa-eye");
        btn.classList.toggle("fa-eye-slash");
    }
</script>

</body>
</html>