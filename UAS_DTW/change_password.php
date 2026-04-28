<?php
include 'koneksi.php';

if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $old_pass = $_POST['old_password'];
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Cari user berdasarkan username
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($old_pass, $row['password'])) {
        // Jika password lama cocok, update ke yang baru
        mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE username='$username'");
        echo "<script>alert('Password berhasil diubah! Silakan Sign In kembali.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Data tidak cocok! Periksa kembali username atau password lama Anda.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1a1a1a;
            --bg-light: #fafafa;
            --input-border: #eee;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
             font-family: 'Poppins', sans-serif;
             background: #f7f1e6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .logo-img {
            max-width: 180px;
            margin-bottom: 25px;
        }

        h2 {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 25px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--primary-dark);
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 12px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            background: #fdfdfd;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-dark);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background-color: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #000;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #888;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="logo gobu.png" alt="Gobu Coffee Logo" class="logo-img">
        
        <h2>CHANGE PASSWORD</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="old_password" placeholder="Old Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="update" class="btn">UPDATE PASSWORD</button>
        </form>

        <a href="login.php" class="back-link">Back to Sign In</a>
    </div>
</body>
</html>