<?php
session_start();
require_once 'config.php';

$action = $_GET['action'] ?? '';

$errorMsg = '';

if ($action == 'db_error') {
    $badQuery = "SELECT * FROM nonexistent_table WHERE id = " . ($_GET['id'] ?? 1);
    $result = $conn->query($badQuery);
    if (!$result) {
        $errorMsg = "Database Error: " . $conn->error . "\n\n";
        $errorMsg .= "=== THÔNG TIN BỊ LỘ (Information Disclosure) ===\n";
        $dbResult = $conn->query("SELECT DATABASE()");
        if ($dbResult) {
            $errorMsg .= "Database Name: " . $dbResult->fetch_array()[0] . "\n";
        }
        $errorMsg .= "Server Version: " . $conn->server_info . "\n";
        $errorMsg .= "Host Info: " . $conn->host_info . "\n";
        $errorMsg .= "Error Code: " . $conn->errno . "\n";
        $errorMsg .= "SQL State: " . $conn->sqlstate . "\n\n";
        $errorMsg .= "⚠️ LƯU Ý: Bảng 'nonexistent_table' KHÔNG TỒN TẠI - đây là cố ý để demo lỗ hổng!";
    }
}

if ($action == 'file_error') {
    $file = $_GET['file'] ?? '';
    if (!empty($file)) {
        $content = @file_get_contents($file);
        if ($content === false) {
            $error = error_get_last();
            $errorMsg = "=== FILE ERROR ===\n";
            $errorMsg .= "File Requested: " . htmlspecialchars($file) . "\n";
            $errorMsg .= "Error Message: " . ($error['message'] ?? 'File not found') . "\n\n";
            $errorMsg .= "=== THÔNG TIN HỆ THỐNG BỊ LỘ (Information Disclosure) ===\n";
            $errorMsg .= "PHP Version: " . phpversion() . "\n";
            $errorMsg .= "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
            $errorMsg .= "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
            $errorMsg .= "Script Path: " . __FILE__ . "\n\n";
            $errorMsg .= "⚠️ LƯU Ý: File '" . htmlspecialchars($file) . "' KHÔNG TỒN TẠI - đây là cố ý để demo lỗ hổng!";
        }
    }
}

if ($action == 'phpinfo') {
    phpinfo();
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Demo - Vulnerable App</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1 {
            font-size: 2.2em;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .demo-link {
            display: block;
            padding: 20px;
            margin: 15px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .demo-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .error-display {
            background: #1e1e1e;
            color: #00ff00;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .info {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-top: 30px;
            font-size: 0.9em;
            box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);
        }
        .info strong {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .back-link a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-exclamation-circle"></i> Information Disclosure Demo</h1>
        
        <h2 style="color: #333; margin-bottom: 20px;">Các lỗ hổng Information Disclosure:</h2>
        
        <a href="?action=db_error&id=1" class="demo-link">
            <i class="fas fa-database"></i> 1. Database Error - Lộ thông tin kết nối DB
        </a>
        
        <a href="?action=file_error&file=nonexistent.php" class="demo-link">
            <i class="fas fa-file"></i> 2. File Error - Lộ thông tin hệ thống
        </a>
        
        <a href="?action=phpinfo" class="demo-link">
            <i class="fas fa-info"></i> 3. PHP Info - Lộ toàn bộ cấu hình PHP
        </a>

        <?php if (isset($errorMsg)): ?>
            <div class="error-display"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
        
        <div class="info">
            <strong><i class="fas fa-bug"></i> Lỗ hổng:</strong> Information Disclosure<br><br>
            <strong><i class="fas fa-info-circle"></i> Mô tả:</strong><br>
            - Error messages tiết lộ thông tin nhạy cảm về database, file system, PHP config<br>
            - phpinfo() hiển thị tất cả thông tin cấu hình PHP<br>
            - Stack traces có thể lộ đường dẫn file, code structure<br>
            - Database errors có thể lộ schema, table names<br><br>
            <strong><i class="fas fa-exclamation-triangle"></i> Lưu ý quan trọng:</strong><br>
            - Bảng "nonexistent_table" và file "nonexistent.php" <strong>KHÔNG TỒN TẠI</strong> - đây là cố ý!<br>
            - Mục đích: Tạo lỗi để demo Information Disclosure - lộ thông tin khi có lỗi<br>
            - Trong thực tế, lỗi này có thể xảy ra khi code có bug hoặc bị tấn công<br><br>
            <strong><i class="fas fa-flask"></i> Thử nghiệm:</strong><br>
            - Click vào các link trên để xem thông tin bị lộ<br>
            - Kiểm tra error messages trong các trang khác khi có lỗi
        </div>
        
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Về trang chủ</a>
        </div>
    </div>
</body>
</html>

