<?php
session_start();
require_once 'config.php';

$query = $_GET['q'] ?? '';
$results = [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Vulnerable App</title>
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
        input[type="text"] {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            box-sizing: border-box;
            font-size: 1em;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        input[type="text"]:focus {
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
        .results {
            margin-top: 30px;
        }
        .results h2 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #333;
        }
        .result-item {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .result-item:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .result-item strong {
            color: #667eea;
            font-size: 1.1em;
            display: block;
            margin-bottom: 8px;
        }
        .result-item p {
            color: #333;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        .result-item small {
            color: #888;
            font-size: 0.85em;
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
        <h1><i class="fas fa-search"></i> Tìm kiếm</h1>
        
        <form method="GET" action="">
            <div class="form-group">
                <input type="text" name="q" placeholder="Nhập từ khóa tìm kiếm..." value="<?php echo htmlspecialchars($query); ?>" required>
            </div>
            <button type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
        </form>

        <?php if (!empty($query)): ?>
            <div class="results">
                <h2>Kết quả tìm kiếm cho: 
                    <?php echo $query; ?>
                </h2>
                
                <?php
                $searchQuery = "SELECT * FROM comments WHERE content LIKE '%$query%'";
                $result = $conn->query($searchQuery);
                
                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <div class="result-item">
                        <strong><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['username']); ?></strong>
                        <p><?php echo htmlspecialchars($row['content']); ?></p>
                        <small><i class="fas fa-clock"></i> <?php echo $row['created_at']; ?></small>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <p style="text-align: center; color: #888; padding: 20px;">Không tìm thấy kết quả nào.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="info">
            <strong><i class="fas fa-bug"></i> Lỗ hổng:</strong> Reflected XSS (Cross-Site Scripting)<br><br>
            <strong><i class="fas fa-flask"></i> Thử nghiệm:</strong><br>
            - URL: <code>search.php?q=&lt;script&gt;alert('XSS')&lt;/script&gt;</code><br>
            - Hoặc: <code>search.php?q=&lt;img src=x onerror=alert('XSS')&gt;</code><br>
            - Hoặc: <code>search.php?q=&lt;svg onload=alert('XSS')&gt;</code>
        </div>
        
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Về trang chủ</a>
        </div>
    </div>
</body>
</html>

