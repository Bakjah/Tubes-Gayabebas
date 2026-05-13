<?php
/**
 * PHOTO UPLOAD & SAVE
 * File: upload.php
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

require_login();

$user = get_current_user();
$response = ['success' => false, 'error' => 'Unknown error'];

try {
    // Ensure upload directory exists
    ensure_upload_dir();

    // Handle file upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Check if photo exists
        if (empty($_FILES['photo']) && empty($_POST['photo'])) {
            throw new Exception('Tidak ada file photo');
        }

        $upload_dir = __DIR__ . '/uploads/';
        $filename = generate_filename('jpg');
        $filepath = $upload_dir . $filename;

        // Handle base64 from camera
        if (!empty($_POST['photo'])) {
            $photo_data = $_POST['photo'];
            
            // Check if it's base64
            if (strpos($photo_data, 'data:image') === 0) {
                // Extract base64 data
                $photo_data = substr($photo_data, strpos($photo_data, ',') + 1);
                $photo_data = base64_decode($photo_data);
            } else {
                $photo_data = base64_decode($photo_data);
            }

            if (!file_put_contents($filepath, $photo_data)) {
                throw new Exception('Gagal menyimpan foto');
            }

        } elseif (!empty($_FILES['photo'])) {
            // Handle multipart file upload from editor
            $file = $_FILES['photo'];

            // Validate
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Upload error: ' . $file['error']);
            }

            if (!in_array($file['type'], ['image/jpeg', 'image/png'])) {
                throw new Exception('Format file tidak didukung');
            }

            if ($file['size'] > 5242880) { // 5MB
                throw new Exception('File terlalu besar');
            }

            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Gagal move file');
            }
        }

        // Compress image
        $this->compressImage($filepath);

        // Get filter & frame info
        $filter = sanitize($_POST['filter'] ?? 'normal');
        $frame = sanitize($_POST['frame'] ?? '');
        $text = sanitize($_POST['text'] ?? '');

        // Save to database
        $relative_path = 'uploads/' . $filename;
        
        $stmt = $db->prepare("
            INSERT INTO photos (user_id, photo_path, frame_name, filter_name, text_content) 
            VALUES (?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception('Database error: ' . $db->error);
        }

        $stmt->bind_param(
            'issss',
            $user['id'],
            $relative_path,
            $frame,
            $filter,
            $text
        );

        if (!$stmt->execute()) {
            throw new Exception('Gagal simpan ke database: ' . $stmt->error);
        }

        $photo_id = $db->insert_id;

        $response = [
            'success' => true,
            'photo_id' => $photo_id,
            'photo_path' => $relative_path,
            'message' => 'Foto berhasil disimpan!'
        ];

    } else {
        throw new Exception('Method tidak didukung');
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    http_response_code(400);
}

echo json_encode($response);
exit;

/**
 * Compress image size
 */
function compressImage($filepath, $quality = 80) {
    $image = imagecreatefromjpeg($filepath);
    if ($image) {
        imagejpeg($image, $filepath, $quality);
        imagedestroy($image);
    }
}

?>
