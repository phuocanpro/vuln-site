<?php
session_start();
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'] ?? 'Guest';
    $content = $_POST['content'] ?? '';
    
    if (!empty($content)) {
        $query = "INSERT INTO comments (username, content) VALUES ('$username', '$content')";
        
        if ($conn->query($query)) {
            $message = "Comment đã được thêm thành công!";
        } else {
            $message = "Lỗi: " . $conn->error;
        }
    }
}

// Get all comments
$query = "SELECT * FROM comments ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments - Vulnerable App</title>
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
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-weight: 500;
        }
        textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            box-sizing: border-box;
            min-height: 120px;
            font-family: 'Inter', sans-serif;
            font-size: 1em;
            transition: all 0.3s;
            resize: vertical;
        }
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        button {
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .message {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .comments {
            margin-top: 40px;
        }
        .comments h2 {
            font-size: 1.8em;
            margin-bottom: 25px;
            color: #333;
        }
        .comment {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .comment:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .comment-header {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1em;
        }
        .comment-date {
            font-size: 0.85em;
            color: #888;
            font-weight: 400;
        }
        .comment-content {
            color: #333;
            line-height: 1.6;
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
        .info code {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
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
        <h1><i class="fas fa-comments"></i> Comments</h1>
        
        <?php if ($message): ?>
            <div class="message">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="content"><i class="fas fa-edit"></i> Viết comment:</label>
                <textarea id="content" name="content" required placeholder="Nhập comment của bạn..."></textarea>
            </div>
            
            <button type="submit"><i class="fas fa-paper-plane"></i> Gửi Comment</button>
        </form>
        
        <div class="comments">
            <h2><i class="fas fa-list"></i> Danh sách Comments</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="comment">
                        <div class="comment-header">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($row['username']); ?>
                            <span class="comment-date"> - <?php echo $row['created_at']; ?></span>
                        </div>
                        <div class="comment-content">
                            <?php echo $row['content']; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #888; padding: 20px;">Chưa có comment nào.</p>
            <?php endif; ?>
        </div>
        
        <div class="info">
            <strong><i class="fas fa-bug"></i> Lỗ hổng:</strong> Stored XSS (Cross-Site Scripting)<br><br>
            <strong><i class="fas fa-flask"></i> Thử nghiệm:</strong><br>
            - Nhập: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code><br>
            - Hoặc: <code>&lt;img src=x onerror=alert('XSS')&gt;</code>
        </div>
        
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Về trang chủ</a>
        </div>
    </div>
</body>
</html>

