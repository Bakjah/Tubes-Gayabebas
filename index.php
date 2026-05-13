<?php
/**
 * USER ALBUM PAGE
 * File: album.php
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

require_login();

$user = get_current_user();
$photos = get_user_photos($user['id']);

// Handle delete photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_photo_id'])) {
    $photo_id = intval($_POST['delete_photo_id']);
    $photo = get_photo_by_id($photo_id);
    
    // Verify ownership
    if ($photo && $photo['user_id'] === $user['id']) {
        delete_photo($photo_id);
        header('Location: album.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Saya - PhotoBooth</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .album-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #FFF8F0 0%, #E8D5F2 100%);
            padding: 2rem;
        }

        .album-header {
            max-width: 1200px;
            margin: 0 auto 2rem;
            text-align: center;
        }

        .album-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .album-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .photo-item {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 1;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .photo-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            padding: 1rem;
        }

        .photo-item:hover .photo-overlay {
            opacity: 1;
        }

        .overlay-btn {
            background: white;
            color: #FFB6D9;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .overlay-btn:hover {
            background: #FFB6D9;
            color: white;
        }

        .overlay-btn.delete {
            background: #FF6B6B;
            color: white;
        }

        .overlay-btn.delete:hover {
            background: #FF5252;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h2 {
            color: #999;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 2rem;
        }

        .create-btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #FFB6D9 0%, #E8D5F2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 182, 217, 0.3);
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 182, 217, 0.4);
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .action-bar-left h2 {
            margin: 0;
        }

        .stat-info {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-info strong {
            color: #FFB6D9;
        }

        @media (max-width: 768px) {
            .album-page {
                padding: 1rem;
            }

            .album-header h1 {
                font-size: 1.8rem;
            }

            .photo-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }

            .action-bar {
                flex-direction: column;
            }

            .action-bar-left {
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
                <a href="index.php">Ambil Foto</a>
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
    <div class="album-page">
        <div class="album-header">
            <h1>📷 Album Saya</h1>
            <p>Koleksi foto-foto indah Anda</p>
        </div>

        <div class="album-content">
            <?php if (count($photos) > 0): ?>
                <div class="action-bar">
                    <div class="action-bar-left">
                        <h2>Total Foto: <strong><?php echo count($photos); ?></strong></h2>
                    </div>
                    <a href="index.php" class="create-btn">📸 Ambil Foto Baru</a>
                </div>

                <div class="photo-grid">
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-item">
                            <img src="<?php echo htmlspecialchars($photo['photo_path']); ?>" 
                                 alt="Photo">
                            <div class="photo-overlay">
                                <button class="overlay-btn" onclick="downloadPhoto('<?php echo htmlspecialchars($photo['photo_path']); ?>')">
                                    ⬇️ Download
                                </button>
                                <button class="overlay-btn" onclick="viewPhoto(<?php echo $photo['id']; ?>)">
                                    👁️ Lihat
                                </button>
                                <button class="overlay-btn" onclick="orderPrint(<?php echo $photo['id']; ?>)">
                                    🖨️ Pesan Cetak
                                </button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_photo_id" value="<?php echo $photo['id']; ?>">
                                    <button type="submit" class="overlay-btn delete" 
                                            onclick="return confirm('Yakin hapus foto ini?')">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">📸</div>
                    <h2>Album Masih Kosong</h2>
                    <p>Belum ada foto yang disimpan. Ayo ambil foto pertama Anda sekarang!</p>
                    <a href="index.php" class="create-btn">Mulai Ambil Foto</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Photo Viewer Modal -->
    <div id="photoModal" class="modal">
        <div class="modal-content" style="max-width: 90%; max-height: 90%;">
            <div class="modal-header">
                <h2>Lihat Foto</h2>
                <span class="modal-close" onclick="closePhotoModal()">&times;</span>
            </div>
            <img id="photoImage" src="" alt="Photo" style="width: 100%; border-radius: 12px;">
        </div>
    </div>

    <script>
        function downloadPhoto(photoPath) {
            const link = document.createElement('a');
            link.href = photoPath;
            link.download = photoPath.split('/').pop();
            link.click();
        }

        function viewPhoto(photoId) {
            fetch(`get-photo.php?id=${photoId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('photoImage').src = data.photo_path;
                        document.getElementById('photoModal').classList.add('active');
                    }
                });
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.remove('active');
        }

        function orderPrint(photoId) {
            window.location.href = `checkout.php?photo_id=${photoId}`;
        }

        // Close modal when clicking outside
        document.getElementById('photoModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'photoModal') {
                closePhotoModal();
            }
        });
    </script>
</body>
</html>
