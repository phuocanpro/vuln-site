<?php
session_start();
require_once 'security_headers.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable Web Application</title>
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
            color: #333;
        }
        .container {
            max-width: 1000px;
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
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #667eea, #764ba2) 1;
        }
        .header h1 {
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 1.1em;
        }
        .warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .warning i {
            font-size: 1.5em;
        }
        .user-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info strong {
            font-size: 1.1em;
        }
        .logout {
            display: inline-block;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            border: 2px solid white;
        }
        .logout:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .section-title {
            font-size: 1.8em;
            font-weight: 600;
            margin: 30px 0 20px 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .menu {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .menu li a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            font-weight: 500;
        }
        .menu li a:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .menu li a i {
            font-size: 1.5em;
        }
        .account-info {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);
        }
        .account-info h3 {
            margin-bottom: 15px;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .account-info ul {
            list-style: none;
        }
        .account-info li {
            padding: 8px 0;
            font-size: 1.1em;
        }
        .account-info strong {
            display: inline-block;
            min-width: 100px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .header h1 {
                font-size: 2em;
            }
            .menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shield-alt"></i> Vulnerable Web Application</h1>
            <p>Ứng dụng kiểm thử bảo mật cho mục đích giáo dục</p>
        </div>
        
        <div class="warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>CẢNH BÁO:</strong> Đây là ứng dụng web được tạo ra với mục đích giáo dục và kiểm thử bảo mật. 
                Ứng dụng này chứa các lỗ hổng bảo mật cố ý. KHÔNG sử dụng trong môi trường production!
            </div>
        </div>

        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                <div>
                    <strong><i class="fas fa-user"></i> Đăng nhập với tư cách:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
                <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        <?php endif; ?>

        <h2 class="section-title"><i class="fas fa-list"></i> Menu</h2>
        <ul class="menu">
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> <span>Login (SQL Injection + Weak Password)</span></a></li>
            <li><a href="comment.php"><i class="fas fa-comments"></i> <span>Comments (Stored XSS)</span></a></li>
            <li><a href="search.php"><i class="fas fa-search"></i> <span>Search (Reflected XSS)</span></a></li>
            <li><a href="upload.php"><i class="fas fa-upload"></i> <span>File Upload (Unrestricted Upload)</span></a></li>
            <li><a href="profile.php"><i class="fas fa-user-circle"></i> <span>Profile (IDOR + Information Disclosure)</span></a></li>
            <li><a href="ping.php"><i class="fas fa-network-wired"></i> <span>Ping Tool (Command Injection)</span></a></li>
            <li><a href="file_viewer.php"><i class="fas fa-file-alt"></i> <span>File Viewer (Path Traversal)</span></a></li>
            <li><a href="change_password.php"><i class="fas fa-key"></i> <span>Change Password (CSRF + SQL Injection)</span></a></li>
            <li><a href="error_demo.php"><i class="fas fa-exclamation-circle"></i> <span>Error Demo (Information Disclosure)</span></a></li>
            <li><a href="huong_dan.php"><i class="fas fa-book"></i> <span>Hướng dẫn sử dụng</span></a></li>
        </ul>

        <div class="account-info">
            <h3><i class="fas fa-info-circle"></i> Thông tin tài khoản mặc định</h3>
            <ul>
                <li><strong>Username:</strong> admin</li>
                <li><strong>Password:</strong> 123456</li>
            </ul>
        </div>
    </div>
</body>
</html>

