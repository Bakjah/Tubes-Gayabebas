<?php
/**
 * LOGIN PAGE
 * File: login.php
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

start_secure_session();

// Jika sudah login, redirect ke home
if (is_logged_in()) {
    if (is_admin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$error = '';

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password tidak boleh kosong';
    } else {
        $login_result = login_user($username, $password);
        
        if ($login_result['success']) {
            $redirect = $login_result['user']['role'] === 'admin' ? 'admin/dashboard.php' : 'index.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = $login_result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PhotoBooth Studio</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #FFF8F0 0%, #E8D5F2 100%);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #999;
            font-size: 0.9rem;
        }

        .login-form {
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2C3E50;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #E0E0E0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #FFB6D9;
            box-shadow: 0 0 0 3px rgba(255, 182, 217, 0.1);
        }

        .error-message {
            color: #FF6B6B;
            background: #FFE5E5;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #FF6B6B;
        }

        .login-button {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #FFB6D9 0%, #E8D5F2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 182, 217, 0.3);
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 182, 217, 0.4);
        }

        .login-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #E0E0E0;
        }

        .login-footer p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .login-footer a {
            color: #FFB6D9;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #E8D5F2;
        }

        .demo-info {
            background: #FFF3E0;
            border-left: 4px solid #FF9800;
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1.5rem;
            font-size: 0.85rem;
        }

        .demo-info p {
            margin-bottom: 0.3rem;
        }

        .demo-code {
            background: white;
            padding: 0.5rem;
            border-radius: 6px;
            font-family: monospace;
            margin-top: 0.3rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>📸 PhotoBooth</h1>
                <p>Ambil foto & ciptakan kenangan indah</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    ⚠️ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="login-button">Masuk</button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun?</p>
                <a href="register.php">Daftar sekarang</a>
            </div>

            <div class="demo-info">
                <p><strong>🎉 Test Account:</strong></p>
                <div class="demo-code">
                    User: user123<br>
                    Pass: password123
                </div>
                <div class="demo-code" style="margin-top: 0.5rem;">
                    Admin: admin<br>
                    Pass: admin123
                </div>
            </div>
        </div>
    </div>
</body>
</html>
