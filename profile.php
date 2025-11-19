<?php
session_start();
require_once 'config.php';

$user_id = $_GET['user_id'] ?? ($_SESSION['user_id'] ?? 1);

$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result ? $result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Vulnerable App</title>
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
        .profile-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .profile-item {
            margin-bottom: 15px;
        }
        .profile-item strong {
            display: inline-block;
            width: 120px;
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
        <h1>👤 Thông tin người dùng</h1>
        
        <?php if ($user): ?>
            <div class="profile-info">
                <div class="profile-item">
                    <strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?>
                </div>
                <div class="profile-item">
                    <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
                </div>
                <div class="profile-item">
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                </div>
                <div class="profile-item">
                    <strong>Password:</strong> <?php echo htmlspecialchars($user['password']); ?>
                </div>
                <div class="profile-item">
                    <strong>Ngày tạo:</strong> <?php echo $user['created_at']; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Không tìm thấy người dùng.</p>
        <?php endif; ?>
        
        <div class="info">
            <strong>Lỗ hổng:</strong> IDOR (Insecure Direct Object Reference) + Information Disclosure<br>
            <strong>Thử nghiệm:</strong><br>
            - Truy cập: <code>profile.php?user_id=1</code> (admin)<br>
            - Truy cập: <code>profile.php?user_id=2</code> (user1)<br>
            - Truy cập: <code>profile.php?user_id=3</code> (test)<br>
            - Không có kiểm tra quyền truy cập, có thể xem thông tin của bất kỳ user nào<br>
            - Password được hiển thị plain text (Information Disclosure)
        </div>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">← Về trang chủ</a>
        </p>
    </div>
</body>
</html>

