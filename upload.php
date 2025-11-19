<?php
session_start();
require_once 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    
    $fileName = $_FILES['file']['name'];
    $tmpName = $_FILES['file']['tmp_name'];
    
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($tmpName, $targetPath)) {
            $message = "File đã được upload thành công!";
            $messageType = 'success';
            $uploadedFile = $fileName;
        } else {
            $message = "Lỗi khi upload file!";
            $messageType = 'error';
        }
    } else {
        $message = "Lỗi upload: " . $_FILES['file']['error'];
        $messageType = 'error';
    }
}

$uploadDir = 'uploads/';
$uploadedFiles = [];
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $uploadedFiles[] = $file;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload - Vulnerable App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .message.success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .message.error {
            background-color: #ffebee;
            color: #c62828;
        }
        .file-list {
            margin-top: 30px;
        }
        .file-item {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-item a {
            color: #4CAF50;
            text-decoration: none;
        }
        .file-item a:hover {
            text-decoration: underline;
        }
        .info {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 14px;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📤 Upload File</h1>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
                <?php if (isset($uploadedFile)): ?>
                    <br><a href="uploads/<?php echo htmlspecialchars($uploadedFile); ?>" target="_blank">Xem file đã upload</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Chọn file để upload:</label>
                <input type="file" id="file" name="file" required>
            </div>
            
            <button type="submit">Upload File</button>
        </form>
        
        <div class="file-list">
            <h2>Danh sách file đã upload</h2>
            <?php if (empty($uploadedFiles)): ?>
                <p>Chưa có file nào được upload.</p>
            <?php else: ?>
                <?php foreach ($uploadedFiles as $file): ?>
                    <div class="file-item">
                        <span><?php echo htmlspecialchars($file); ?></span>
                        <a href="uploads/<?php echo htmlspecialchars($file); ?>" target="_blank">Xem/Tải về</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="info">
            <strong>Lỗ hổng:</strong> Unrestricted File Upload<br>
            <strong>Thử nghiệm:</strong><br>
            - Upload file PHP: <code>&lt;?php phpinfo(); ?&gt;</code><br>
            - Upload file shell: <code>&lt;?php system($_GET['cmd']); ?&gt;</code><br>
            - Upload file .htaccess để bypass<br>
            - Không có kiểm tra MIME type, extension, hoặc nội dung file
        </div>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">← Về trang chủ</a>
        </p>
    </div>
</body>
</html>

