<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - GOBU Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1a1a1a;
            --secondary-gray: #f4f4f4;
            --text-main: #333;
            --accent: #555;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-main);
        }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
        }

        .logo-placeholder {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 3px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .logo-placeholder span {
            font-weight: 300;
        }

        h2 {
            font-size: 1rem;
            font-weight: 400;
            color: #888;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--accent);
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #eee;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-dark);
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 10px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Tombol tetap bisa dipencet (type="submit") */
        .btn-login {
            background-color: var(--primary-dark);
            color: white;
        }

        .btn-login:hover {
            background-color: #000;
            transform: translateY(-1px);
        }

        .btn-signup {
            background-color: var(--secondary-gray);
            color: var(--text-main);
        }

        .btn-signup:hover {
            background-color: #e8e8e8;
            transform: translateY(-1px);
        }

        .footer-links {
            margin-top: 25px;
            font-size: 0.8rem;
        }

        .footer-links a {
            color: #888;
            text-decoration: none;
            transition: 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="logo-placeholder">
            <span>GOBU</span> COFFEE
        </div>
        <h2>SILAKAN MASUK ATAU DAFTAR</h2>

        <form method="POST" action="">
            <div class="input-group">
                <label for="username">USERNAME</label>
                <input type="text" name="username" id="username" placeholder="Masukkan username Anda" required>
            </div>

            <div class="input-group">
                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password Anda" required>
            </div>

            <div class="btn-container">
                <button type="submit" name="signin" class="btn btn-login">Sign In</button>
                <button type="submit" name="signup" class="btn btn-signup">Sign Up</button>
            </div>
        </form>

        <hr>

        <div class="footer-links">
            <a href="change_password.php">Change Password</a> • <a href="landingpage.php">Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>