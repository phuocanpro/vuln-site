<?php
session_start();
require_once 'security_headers.php';

$host = $_GET['host'] ?? '';
$output = '';

if (!empty($host)) {
    $command = "ping -n 4 " . $host;
    $output = shell_exec($command);
    
    if (empty($output)) {
        exec($command, $outputArray);
        $output = implode("\n", $outputArray);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping Tool - Vulnerable App</title>
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
        .output {
            background-color: #1e1e1e;
            color: #00ff00;
            padding: 15px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
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
        <h1>🌐 Ping Tool</h1>
        
        <form method="GET" action="">
            <div class="form-group">
                <label for="host">Nhập địa chỉ IP hoặc domain:</label>
                <input type="text" id="host" name="host" placeholder="127.0.0.1 hoặc google.com" value="<?php echo htmlspecialchars($host); ?>" required>
            </div>
            <button type="submit">Ping</button>
        </form>

        <?php if (!empty($output)): ?>
            <div class="output"><?php echo htmlspecialchars($output); ?></div>
        <?php endif; ?>
        
        <div class="info">
            <strong>Lỗ hổng:</strong> Command Injection<br>
            <strong>Thử nghiệm (Windows):</strong><br>
            - <code>ping.php?host=127.0.0.1 &amp;&amp; dir</code><br>
            - <code>ping.php?host=127.0.0.1 &amp;&amp; whoami</code><br>
            - <code>ping.php?host=127.0.0.1 | type config.php</code><br>
            - <code>ping.php?host=127.0.0.1 &amp;&amp; echo %USERNAME%</code><br>
            - <code>ping.php?host=127.0.0.1 &amp;&amp; ipconfig</code><br>
            <strong>Lưu ý:</strong> Có thể thực thi bất kỳ lệnh hệ thống nào!
        </div>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">← Về trang chủ</a>
        </p>
    </div>
</body>
</html>

