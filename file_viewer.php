<?php
session_start();

$file = $_GET['file'] ?? '';

$content = '';
$error = '';

if (!empty($file)) {
    $filePath = $file;
    
    if (file_exists($filePath) && is_file($filePath)) {
        $content = file_get_contents($filePath);
    } else {
        $error = "File không tồn tại hoặc không thể đọc.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Viewer - Vulnerable App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
        input[type="text"] {
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
        .file-content {
            background-color: #1e1e1e;
            color: #ffffff;
            padding: 15px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            margin-top: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
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
        <h1>📄 File Viewer</h1>
        
        <form method="GET" action="">
            <div class="form-group">
                <label for="file">Đường dẫn file:</label>
                <input type="text" id="file" name="file" placeholder="config.php hoặc ../../../etc/passwd" value="<?php echo htmlspecialchars($file); ?>" required>
            </div>
            <button type="submit">Xem file</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($content)): ?>
            <div class="file-content"><?php echo htmlspecialchars($content); ?></div>
        <?php endif; ?>
        
        <div class="info">
            <strong>Lỗ hổng:</strong> Path Traversal (Directory Traversal)<br>
            <strong>Thử nghiệm:</strong><br>
            - <code>file_viewer.php?file=config.php</code><br>
            - <code>file_viewer.php?file=../config.php</code><br>
            - <code>file_viewer.php?file=../../init_db.sql</code><br>
            - <code>file_viewer.php?file=../../../etc/passwd</code> (Linux)<br>
            - <code>file_viewer.php?file=..\..\..\Windows\System32\drivers\etc\hosts</code> (Windows)<br>
            - <code>file_viewer.php?file=C:\xampp\htdocs\vuln-site\config.php</code> (Windows absolute path)<br>
            <strong>Lưu ý:</strong> Có thể đọc bất kỳ file nào mà web server có quyền đọc!
        </div>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">← Về trang chủ</a>
        </p>
    </div>
</body>
</html>

