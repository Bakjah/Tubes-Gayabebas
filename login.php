<?php
/**
 * PHOTOBOOTH PAGE
 * File: index.php atau photobooth.php
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

require_login();

$user = get_current_user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhotoBooth - Ambil Foto Sekarang</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .photobooth-page {
            min-height: 100vh;
            padding: 2rem;
            background: linear-gradient(135deg, #FFF8F0 0%, #E8D5F2 100%);
        }

        .navbar {
            margin-bottom: 2rem;
        }

        .photobooth-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }

        .camera-section {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .camera-title {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .camera-title h2 {
            color: #2C3E50;
        }

        .camera-container {
            position: relative;
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 4 / 3;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #canvas {
            display: none;
        }

        .filter-section {
            margin-bottom: 2rem;
        }

        .filter-label {
            display: block;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2C3E50;
        }

        .filter-controls {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.8rem;
        }

        .filter-btn {
            padding: 0.8rem 1rem;
            border: 2px solid #E0E0E0;
            background: white;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            border-color: #FFB6D9;
            background: #FFF8F0;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #FFB6D9 0%, #E8D5F2 100%);
            color: white;
            border-color: #FFB6D9;
            box-shadow: 0 4px 15px rgba(255, 182, 217, 0.3);
        }

        .capture-section {
            margin-bottom: 2rem;
            text-align: center;
        }

        .capture-label {
            display: block;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2C3E50;
        }

        .capture-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }

        .capture-btn {
            padding: 1rem;
            background: white;
            border: 3px solid #FFB6D9;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: #FFB6D9;
        }

        .capture-btn:hover:not(:disabled) {
            background: #FFB6D9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 182, 217, 0.3);
        }

        .capture-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .info-section {
            background: rgba(255, 182, 217, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #FFB6D9;
            text-align: center;
            margin-top: 2rem;
        }

        .info-section p {
            color: #666;
            margin: 0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .action-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .action-btn-primary {
            background: linear-gradient(135deg, #FFB6D9 0%, #E8D5F2 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(255, 182, 217, 0.3);
        }

        .action-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 182, 217, 0.4);
        }

        .action-btn-secondary {
            background: white;
            color: #FFB6D9;
            border: 2px solid #FFB6D9;
        }

        .action-btn-secondary:hover {
            background: #FFB6D9;
            color: white;
        }

        @media (max-width: 768px) {
            .photobooth-page {
                padding: 1rem;
            }

            .camera-section {
                padding: 1rem;
            }

            .camera-container {
                aspect-ratio: 3 / 4;
            }

            .filter-controls {
                grid-template-columns: repeat(3, 1fr);
            }

            .capture-buttons {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">📸 PhotoBooth</div>
            <div class="navbar-menu">
                <a href="photobooth.php">Ambil Foto</a>
                <a href="album.php">Album Saya</a>
                <a href="order-history.php">Pesanan</a>
            </div>
            <div class="navbar-user">
                <div class="user-avatar"><?php echo strtoupper($user['username'][0]); ?></div>
                <a href="logout.php" style="color: #FF6B6B;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="photobooth-page">
        <div class="photobooth-wrapper">
            <div class="camera-section">
                <div class="camera-title">
                    <h2>✨ Ambil Foto Sekarang</h2>
                    <p>Pilih filter dan jumlah foto yang diinginkan</p>
                </div>

                <!-- Camera Feed -->
                <div class="camera-container">
                    <video id="video" autoplay playsinline></video>
                </div>

                <!-- Hidden Canvas -->
                <canvas id="canvas"></canvas>

                <!-- Filter Controls -->
                <div class="filter-section">
                    <label class="filter-label">🎨 Pilih Filter:</label>
                    <div class="filter-controls">
                        <button class="filter-btn active" data-filter="normal">Normal</button>
                        <button class="filter-btn" data-filter="bw">Black & White</button>
                        <button class="filter-btn" data-filter="sepia">Sepia</button>
                        <button class="filter-btn" data-filter="vintage">Vintage</button>
                        <button class="filter-btn" data-filter="bright">Terang</button>
                        <button class="filter-btn" data-filter="cool">Cool Tone</button>
                    </div>
                </div>

                <!-- Capture Options -->
                <div class="capture-section">
                    <label class="capture-label">📷 Pilih Jumlah Foto:</label>
                    <div class="capture-buttons">
                        <button class="capture-btn" data-photos="2">2 Foto</button>
                        <button class="capture-btn" data-photos="3">3 Foto</button>
                        <button class="capture-btn" data-photos="4">4 Foto</button>
                        <button class="capture-btn" data-photos="6">6 Foto</button>
                    </div>
                </div>

                <!-- Info -->
                <div class="info-section">
                    <p>💡 Sistem akan otomatis mengambil foto dengan countdown 3 detik</p>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="action-btn action-btn-primary">
                        <a href="album.php" style="color: white; text-decoration: none;">
                            📸 Lihat Album Saya
                        </a>
                    </button>
                    <button class="action-btn action-btn-secondary">
                        <a href="order-history.php" style="color: inherit; text-decoration: none;">
                            📦 Pesanan Saya
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Countdown Modal -->
    <div id="countdownModal" class="modal">
        <div class="modal-content" style="text-align: center; max-width: 300px;">
            <h2>📸 Siap! Tersenyum!</h2>
            <div style="font-size: 3rem; margin: 2rem 0;" id="countdownText">3</div>
            <p>Foto akan diambil dengan otomatis</p>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <script src="js/camera.js"></script>
</body>
</html>
