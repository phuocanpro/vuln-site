<?php
session_start();
require_once 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'] ?? ($_SESSION['user_id'] ?? 1);
    $new_password = $_POST['new_password'] ?? '';
    
    if (!empty($new_password)) {
        $query = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
        
        if ($conn->query($query)) {
            $message = "Mật khẩu đã được thay đổi thành công!";
            $messageType = 'success';
        } else {
            $message = "Lỗi: " . $conn->error;
            $messageType = 'error';
        }
    } else {
        $message = "Vui lòng nhập mật khẩu mới!";
        $messageType = 'error';
    }
}

$user_id = $_SESSION['user_id'] ?? 1;
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result ? $result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - Vulnerable App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
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
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
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
        <h1>🔑 Đổi mật khẩu</h1>
        
        <?php if ($user): ?>
            <p><strong>Người dùng hiện tại:</strong> <?php echo htmlspecialchars($user['username']); ?> (ID: <?php echo $user['id']; ?>)</p>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="user_id">User ID (có thể thay đổi để đổi mật khẩu user khác):</label>
                <input type="number" id="user_id" name="user_id" value="<?php echo $user_id; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <button type="submit">Đổi mật khẩu</button>
        </form>
        
        <div class="info">
            <strong>Lỗ hổng:</strong> CSRF (Cross-Site Request Forgery) + SQL Injection + IDOR<br>
            <strong>Thử nghiệm CSRF:</strong><br>
            - Tạo file HTML trên server khác:<br>
            <pre>&lt;form action="http://localhost/vuln-site/change_password.php" method="POST"&gt;
  &lt;input type="hidden" name="user_id" value="1"&gt;
  &lt;input type="hidden" name="new_password" value="hacked"&gt;
  &lt;input type="submit" value="Click để nhận quà"&gt;
&lt;/form&gt;</pre>
            - Nếu admin click vào link này, mật khẩu sẽ bị đổi<br>
            - Không có CSRF token để bảo vệ<br>
            <strong>Thử nghiệm IDOR:</strong><br>
            - Thay đổi user_id trong form để đổi mật khẩu của user khác<br>
            - Không có kiểm tra quyền truy cập
        </div>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">← Về trang chủ</a>
        </p>
    </div>
</body>
</html>

