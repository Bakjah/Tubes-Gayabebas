<?php
/**
 * REGISTER PAGE
 * File: register.php
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

start_secure_session();

// Jika sudah login, redirect ke home
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi
    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Semua field harus diisi';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $password_confirm) {
        $error = 'Password tidak cocok';
    } else {
        // Register
        $register_result = register_user($username, $email, $password);
        
        if ($register_result['success']) {
            $success = 'Registrasi berhasil! Silakan login';
            // Redirect after 2 seconds
            header('refresh:2;url=login.php');
        } else {
            $error = $register_result['error'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PhotoBooth Studio</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #E8D5F2 0%, #FFF8F0 100%);
            padding: 1rem;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #999;
            font-size: 0.9rem;
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
            border-color: #E8D5F2;
            box-shadow: 0 0 0 3px rgba(232, 213, 242, 0.3);
        }

        .error-message {
            color: #FF6B6B;
            background: #FFE5E5;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #FF6B6B;
        }

        .success-message {
            color: #51CF66;
            background: #E7F5E7;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #51CF66;
        }

        .register-button {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #E8D5F2 0%, #FFB6D9 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(232, 213, 242, 0.3);
        }

        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(232, 213, 242, 0.4);
        }

        .register-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #E0E0E0;
        }

        .register-footer p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .register-footer a {
            color: #E8D5F2;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-footer a:hover {
            color: #FFB6D9;
        }

        .password-strength {
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        .strength-bar {
            height: 4px;
            background: #E0E0E0;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #FF6B6B;
            transition: width 0.3s ease, background 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1>📸 PhotoBooth</h1>
                <p>Buat akun untuk mulai mengambil foto</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">✅ <?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Pilih username unik"
                        minlength="3"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Masukkan email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Min 6 karakter"
                        minlength="6"
                        required
                    >
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        placeholder="Ketik ulang password"
                        minlength="6"
                        required
                    >
                </div>

                <button type="submit" class="register-button">Buat Akun</button>
            </form>

            <div class="register-footer">
                <p>Sudah punya akun?</p>
                <a href="login.php">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthFill = document.querySelector('.strength-fill');

        if (passwordInput) {
            passwordInput.addEventListener('input', (e) => {
                const password = e.target.value;
                let strength = 0;

                if (password.length >= 6) strength += 25;
                if (password.length >= 10) strength += 25;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password) || /[^a-zA-Z0-9]/.test(password)) strength += 25;

                strengthFill.style.width = strength + '%';
                
                if (strength <= 25) {
                    strengthFill.style.background = '#FF6B6B';
                } else if (strength <= 50) {
                    strengthFill.style.background = '#FFA940';
                } else if (strength <= 75) {
                    strengthFill.style.background = '#FFD700';
                } else {
                    strengthFill.style.background = '#51CF66';
                }
            });
        }
    </script>
</body>
</html>
