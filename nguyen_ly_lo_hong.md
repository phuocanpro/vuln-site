# Nguyên lý và Cách hoạt động của các lỗ hổng bảo mật

## 1. SQL Injection

### Cách hoạt động:
Khi người dùng nhập dữ liệu vào form, code PHP ghép trực tiếp vào câu SQL mà KHÔNG kiểm tra hay làm sạch dữ liệu.

### Ví dụ code lỗi:
```php
$username = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
```

### Tại sao lỗi?
- Nếu người dùng nhập: `admin' OR '1'='1`
- Câu SQL trở thành: `SELECT * FROM users WHERE username='admin' OR '1'='1' AND password='anything'`
- `'1'='1'` luôn đúng → Điều kiện OR luôn TRUE → Bỏ qua kiểm tra password

### Nguyên lý:
SQL Injection xảy ra vì:
1. **Không validate input**: Không kiểm tra ký tự đặc biệt như `'`, `"`, `;`, `--`
2. **String concatenation**: Ghép chuỗi trực tiếp vào SQL
3. **Không dùng Prepared Statements**: Prepared statements sẽ escape các ký tự đặc biệt

### Cách phòng chống:
- Dùng Prepared Statements: `$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");`
- Validate và sanitize input
- Escape special characters

---

## 2. Stored XSS (Cross-Site Scripting)

### Cách hoạt động:
Script độc hại được lưu vào database, sau đó được hiển thị trực tiếp trên trang web mà KHÔNG được encode.

### Ví dụ code lỗi:
```php
// Lưu vào DB - không sanitize
$content = $_POST['content'];
$query = "INSERT INTO comments (content) VALUES ('$content')";

// Hiển thị - không encode
echo $row['content']; // Nếu content chứa <script> thì sẽ chạy!
```

### Tại sao lỗi?
- Người dùng nhập: `<script>alert('XSS')</script>`
- Code lưu trực tiếp vào database
- Khi hiển thị, browser coi `<script>` là code JavaScript và thực thi
- Script có thể: đánh cắp cookie, redirect, thay đổi nội dung trang

### Nguyên lý:
XSS xảy ra vì:
1. **Không sanitize input**: Không loại bỏ hoặc encode HTML tags
2. **Output không encode**: Dùng `echo` thay vì `htmlspecialchars()`
3. **Browser tin tưởng**: Browser luôn thực thi JavaScript trong HTML

### Cách phòng chống:
- Dùng `htmlspecialchars()` khi output: `echo htmlspecialchars($content);`
- Validate input, loại bỏ HTML tags
- Content Security Policy (CSP)

---

## 3. Reflected XSS

### Cách hoạt động:
Tương tự Stored XSS nhưng script KHÔNG được lưu vào database, mà được phản chiếu trực tiếp từ URL parameter.

### Ví dụ code lỗi:
```php
$query = $_GET['q'];
echo "Kết quả tìm kiếm cho: " . $query; // Không encode!
```

### Tại sao lỗi?
- URL: `search.php?q=<script>alert('XSS')</script>`
- Code echo trực tiếp → Browser thực thi script
- Script chỉ chạy khi người dùng click link (không lưu vĩnh viễn)

### Nguyên lý:
- Input từ URL được output trực tiếp không encode
- Attacker gửi link độc cho nạn nhân
- Khi nạn nhân click, script chạy trong context của họ

### Cách phòng chống:
- Luôn encode output: `echo htmlspecialchars($query);`
- Validate input từ URL

---

## 4. Command Injection

### Cách hoạt động:
User input được ghép trực tiếp vào lệnh hệ thống mà KHÔNG validate.

### Ví dụ code lỗi:
```php
$host = $_GET['host'];
$command = "ping -n 4 " . $host; // Ghép trực tiếp!
shell_exec($command);
```

### Tại sao lỗi?
- User nhập: `127.0.0.1 && dir`
- Command trở thành: `ping -n 4 127.0.0.1 && dir`
- `&&` là toán tử "và" trong shell → Lệnh `dir` cũng được chạy!
- Có thể chạy BẤT KỲ lệnh nào: `whoami`, `ipconfig`, `type config.php`

### Nguyên lý:
- Shell cho phép nối nhiều lệnh bằng `&&`, `|`, `;`
- Code không validate input → Cho phép inject lệnh
- Web server chạy với quyền của user → Có thể truy cập file hệ thống

### Cách phòng chống:
- Validate input: Chỉ cho phép IP address hoặc domain hợp lệ
- Dùng whitelist thay vì blacklist
- Escape shell metacharacters: `escapeshellarg()`, `escapeshellcmd()`
- Tránh dùng `shell_exec()`, `exec()`, `system()`

---

## 5. Path Traversal (Directory Traversal)

### Cách hoạt động:
User input được dùng trực tiếp làm đường dẫn file mà KHÔNG kiểm tra ký tự `..` (lùi thư mục).

### Ví dụ code lỗi:
```php
$file = $_GET['file'];
$content = file_get_contents($file); // Không validate!
```

### Tại sao lỗi?
- User nhập: `../../../etc/passwd`
- `..` = lùi 1 thư mục lên
- `../../../` = lùi 3 thư mục → Ra khỏi web root
- Có thể đọc BẤT KỲ file nào mà web server có quyền đọc

### Nguyên lý:
- File system có cấu trúc phân cấp
- `..` là ký tự đặc biệt để điều hướng thư mục
- Code không validate → Cho phép truy cập file ngoài web root
- Web server có quyền đọc nhiều file hệ thống

### Cách phòng chống:
- Validate input: Loại bỏ `..`, `/`, `\`
- Dùng whitelist: Chỉ cho phép file trong thư mục được phép
- Dùng `basename()` để lấy tên file, loại bỏ path
- Kiểm tra file có trong whitelist trước khi đọc

---

## 6. IDOR (Insecure Direct Object Reference)

### Cách hoạt động:
Code cho phép user truy cập tài nguyên (user_id, file_id) mà KHÔNG kiểm tra quyền truy cập.

### Ví dụ code lỗi:
```php
$user_id = $_GET['user_id']; // Lấy trực tiếp từ URL
$query = "SELECT * FROM users WHERE id = $user_id";
// KHÔNG kiểm tra: User này có quyền xem user_id này không?
```

### Tại sao lỗi?
- User A đăng nhập với user_id = 2
- Thay đổi URL: `profile.php?user_id=1` (admin)
- Code không kiểm tra quyền → Cho phép xem profile admin
- Có thể xem/thay đổi dữ liệu của bất kỳ user nào

### Nguyên lý:
- Code tin tưởng user input
- Không có authorization check
- Dựa vào việc user "không biết" user_id khác (security through obscurity - không an toàn!)

### Cách phòng chống:
- Luôn kiểm tra quyền: `if ($_SESSION['user_id'] != $user_id) die('Unauthorized');`
- Dùng session để lấy user_id, không dùng từ URL
- Kiểm tra ownership trước khi cho phép truy cập

---

## 7. CSRF (Cross-Site Request Forgery)

### Cách hoạt động:
Attacker tạo form/script trên website khác, khiến nạn nhân (đã đăng nhập) thực hiện hành động mà họ không muốn.

### Ví dụ code lỗi:
```php
// Không có CSRF token
if ($_POST['new_password']) {
    $query = "UPDATE users SET password = '$new_password' WHERE id = 1";
    // Thực hiện ngay, không kiểm tra request có từ chính website không
}
```

### Tại sao lỗi?
- Admin đang đăng nhập vào website
- Attacker gửi email với link: `<form action="http://website.com/change_password.php" method="POST">`
- Admin click link → Form tự động submit
- Password admin bị đổi mà họ không biết!

### Nguyên lý:
- Browser tự động gửi cookie khi request đến cùng domain
- Code không phân biệt request hợp lệ hay từ website khác
- Không có CSRF token để verify request

### Cách phòng chống:
- Dùng CSRF token: Generate token khi hiển thị form, verify khi submit
- Check Referer header
- Dùng SameSite cookie attribute
- Yêu cầu re-authentication cho hành động quan trọng

---

## 8. Unrestricted File Upload

### Cách hoạt động:
Code cho phép upload file mà KHÔNG kiểm tra loại file, extension, hoặc nội dung.

### Ví dụ code lỗi:
```php
$fileName = $_FILES['file']['name'];
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $fileName);
// Không kiểm tra gì cả!
```

### Tại sao lỗi?
- User upload file `shell.php` với nội dung: `<?php system($_GET['cmd']); ?>`
- File được lưu vào `uploads/shell.php`
- Truy cập: `http://website.com/uploads/shell.php?cmd=dir`
- Server thực thi file PHP → Có thể chạy bất kỳ lệnh nào!

### Nguyên lý:
- Web server thực thi file PHP trong thư mục web
- Code không validate file type → Cho phép upload PHP
- File được lưu với tên gốc → Có thể truy cập và thực thi

### Cách phòng chống:
- Validate file extension: Chỉ cho phép .jpg, .png, .pdf (whitelist)
- Validate MIME type
- Đổi tên file: Dùng random name, không dùng tên gốc
- Lưu file ngoài web root hoặc chặn thực thi trong uploads folder
- Scan file content để phát hiện malicious code

---

## 9. Information Disclosure

### Cách hoạt động:
Error messages hoặc debug info tiết lộ thông tin nhạy cảm về hệ thống.

### Ví dụ code lỗi:
```php
if (!$result) {
    die("Error: " . $conn->error); // Lộ thông tin database!
}
// Hoặc
phpinfo(); // Lộ TOÀN BỘ cấu hình PHP!
```

### Tại sao lỗi?
- Error message: `Table 'vuln_db.users' doesn't exist`
- Lộ: Database name (`vuln_db`), table name (`users`), server version
- Attacker biết cấu trúc database → Dễ dàng SQL Injection
- `phpinfo()` lộ: PHP version, extensions, paths, environment variables

### Nguyên lý:
- Developer muốn debug nên hiển thị error chi tiết
- Không tắt error display trong production
- Thông tin này giúp attacker hiểu hệ thống

### Cách phòng chống:
- Tắt error display trong production: `display_errors = Off`
- Log errors thay vì hiển thị
- Generic error messages: "An error occurred" thay vì chi tiết
- Không dùng `phpinfo()` trong production
- Custom error pages

---

## 10. Weak Password

### Cách hoạt động:
Mật khẩu quá yếu, dễ đoán, và được lưu plain text (không hash).

### Ví dụ code lỗi:
```php
// Lưu plain text
$password = $_POST['password'];
$query = "INSERT INTO users (password) VALUES ('$password')";

// Không có giới hạn đăng nhập sai
// Không có rate limiting
```

### Tại sao lỗi?
- Password `123456` rất phổ biến → Dễ đoán
- Plain text → Nếu database bị leak, password bị lộ ngay
- Không rate limit → Có thể brute force (thử nhiều password liên tục)
- Không có password policy → User có thể đặt password yếu

### Nguyên lý:
- Password yếu dễ bị dictionary attack
- Plain text password → Không thể recover nếu bị leak
- Không rate limit → Attacker thử hàng ngàn password/phút

### Cách phòng chống:
- Hash password: `password_hash()` với bcrypt/argon2
- Password policy: Tối thiểu 8 ký tự, có chữ hoa, số, ký tự đặc biệt
- Rate limiting: Giới hạn số lần đăng nhập sai
- Account lockout: Khóa tài khoản sau N lần sai
- 2FA (Two-Factor Authentication)

---

## Tổng kết

Tất cả các lỗ hổng đều có điểm chung:
1. **Tin tưởng user input**: Không validate hoặc sanitize
2. **Không kiểm tra quyền**: Cho phép truy cập không được phép
3. **Hiển thị thông tin nhạy cảm**: Error messages, debug info
4. **Không dùng best practices**: Prepared statements, password hashing, CSRF tokens

Nguyên tắc vàng: **"Never trust user input"** - Luôn validate, sanitize, và kiểm tra quyền!



