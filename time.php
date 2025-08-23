<?php
date_default_timezone_set('Asia/Bangkok'); // GMT+7

// Hàm mã hóa dữ liệu thành token
function encodeToken($data) {
    return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
}

// Hàm giải mã token thành dữ liệu
function decodeToken($token) {
    $data = base64_decode(str_pad(strtr($token, '-_', '+/'), strlen($token) % 4, '=', STR_PAD_RIGHT));
    return json_decode($data, true);
}

// Nếu có token → giải mã thành biến
if (isset($_GET['token'])) {
    $tokenData = decodeToken($_GET['token']);
    if ($tokenData && is_array($tokenData)) {
        foreach ($tokenData as $key => $value) {
            $_GET[$key] = $value;
            $$key = $value; // tạo biến động
        }
    } else {
        die("❌ Token không hợp lệ");
    }
}
// Nếu chưa có token nhưng có tham số time → tự tạo token và redirect
elseif (isset($_GET['time'])) {
    $tokenData = $_GET;
    $token = encodeToken($tokenData);

    // Redirect 302 sang dạng token
    $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?') . "?token={$token}";
    header("Location: {$redirectUrl}", true, 302);
    exit;
}
// Nếu không có token và không có time → báo lỗi
else {
    die("❌ Thiếu tham số time");
}

// Các biến từ GET (sau khi giải mã token vẫn có)
$rawTime     = isset($_GET['time']) ? intval($_GET['time']) : null;
$tiktokUrl   = isset($_GET['opentiktok']) ? $_GET['opentiktok'] : '';
$username    = isset($_GET['username']) ? $_GET['username'] : '';
$viewCount   = isset($_GET['view']) ? $_GET['view'] : '';
$peopleCount = isset($_GET['people']) ? $_GET['people'] : '';
$timeunbound = isset($_GET['timeunbound']) ? $_GET['timeunbound'] : '';
$matxem      = isset($_GET['matxem']) ? $_GET['matxem'] : '';
$chatid      = isset($_GET['chatid']) ? $_GET['chatid'] : '';

$nowGmt7 = round(microtime(true) * 1000);

// ==== Code xử lý cũ của bạn tiếp tục ở đây ====
?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🎯 CANH MỞ RƯƠNG - huycoihd</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #000000;
      --secondary-color: #000000;
      --accent-color: #000000;
      --background-primary: #ffffff;
      --background-secondary: #f8f9fa;
      --background-tertiary: #e9ecef;
      --surface-color: rgba(0, 0, 0, 0.03);
      --surface-hover: rgba(0, 0, 0, 0.06);
      --text-primary: #000000;
      --text-secondary: #333333;
      --text-muted: #666666;
      --success-color: #000000;
      --warning-color: #000000;
      --error-color: #000000;
      --border-color: rgba(0, 0, 0, 0.15);
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      background: var(--background-primary);
      color: var(--text-primary);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      overflow-x: hidden;
      position: relative;
      transition: all 0.3s ease;
    }
    
    .container {
      background: var(--surface-color);
      backdrop-filter: blur(20px);
      border: 1px solid var(--border-color);
      border-radius: 24px;
      padding: 40px;
      max-width: 500px;
      width: 100%;
      text-align: center;
      position: relative;
      box-shadow: var(--shadow-xl);
      overflow: hidden;
    }
    
    .container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    }
    
    .header {
      margin-bottom: 32px;
    }
    
    .app-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }
    
    .app-subtitle {
      color: var(--text-secondary);
      font-size: 0.875rem;
      font-weight: 400;
    }
    
    .user-info {
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 16px 24px;
      margin-bottom: 24px;
      font-size: 1.125rem;
      font-weight: 600;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    
    .stats {
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 32px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 24px;
    }
    
    .stat-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1rem;
      font-weight: 500;
      color: var(--text-primary);
    }
    
    .stat-icon {
      font-size: 1.25rem;
      color: var(--text-primary);
    }
    
    .countdown-display {
      font-family: 'JetBrains Mono', 'Courier New', monospace;
      font-size: 5.5rem;
      font-weight: 900;
      color: var(--text-primary);
      margin: 40px 0;
      letter-spacing: -0.08em;
      position: relative;
      text-shadow: 
        0 0 10px rgba(0, 0, 0, 0.3),
        0 0 20px rgba(0, 0, 0, 0.2),
        0 0 30px rgba(0, 0, 0, 0.1);
    }
    
    .status-message {
      font-size: 1.25rem;
      font-weight: 500;
      margin: 24px 0;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    
    .error-message {
      color: var(--error-color);
      font-size: 1.375rem;
      font-weight: 600;
    }
    
    .success-message {
      color: var(--success-color);
      font-size: 1.5rem;
      font-weight: 700;
      animation: pulse 2s infinite;
    }
    
    .progress-container {
      width: 100%;
      height: 8px;
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      margin: 32px 0;
      overflow: hidden;
      position: relative;
    }
    
    .progress-bar {
      height: 100%;
      background: var(--text-primary);
      border-radius: 8px;
      transition: width 0.2s ease-out;
      position: relative;
    }
    
    .claim-btn {
      background: var(--text-primary);
      color: var(--background-primary);
      border: 2px solid var(--text-primary);
      padding: 16px 32px;
      font-size: 1.125rem;
      font-weight: 600;
      border-radius: 16px;
      margin-top: 24px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: var(--shadow-lg);
      position: relative;
      overflow: hidden;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      min-width: 200px;
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }
    
    .claim-btn:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: var(--shadow-xl);
      background: var(--background-primary);
      color: var(--text-primary);
    }
    
    .claim-btn:active {
      transform: translateY(0) scale(0.98);
    }
    
    .claim-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }
    
    .claim-btn:hover::before {
      left: 100%;
    }
    
    .delta-controls {
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 16px;
      margin: 24px 0;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 12px;
    }
    
    .delta-btn {
      padding: 8px 16px;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      background: var(--surface-color);
      color: var(--text-primary);
      cursor: pointer;
      font-size: 0.875rem;
      font-weight: 500;
      transition: all 0.2s ease;
      min-width: 60px;
    }
    
    .delta-btn:hover {
      background: var(--surface-hover);
      border-color: var(--text-primary);
      transform: translateY(-1px);
    }
    
    .delta-text {
      font-size: 1rem;
      font-weight: 600;
      margin: 0 16px;
      color: var(--text-primary);
      min-width: 80px;
    }
    
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(8px);
      z-index: 1000;
      justify-content: center;
      align-items: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .modal.show {
      display: flex;
      opacity: 1;
    }
    
    .modal-content {
      background: var(--background-secondary);
      border: 1px solid var(--border-color);
      padding: 32px;
      border-radius: 20px;
      max-width: 420px;
      width: 90%;
      box-shadow: var(--shadow-xl);
      transform: scale(0.9);
      transition: transform 0.3s ease;
    }
    
    .modal.show .modal-content {
      transform: scale(1);
    }
    
    .modal-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 24px;
      color: var(--text-primary);
      text-align: center;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--text-secondary);
      font-size: 0.875rem;
    }
    
    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 12px;
      border: 1px solid var(--border-color);
      background: var(--surface-color);
      color: var(--text-primary);
      font-size: 1rem;
      transition: all 0.2s ease;
    }
    
    .form-group input:focus {
      outline: none;
      border-color: var(--text-primary);
      box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }
    
    .modal-actions {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }
    
    .modal-btn {
      flex: 1;
      padding: 12px 24px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.2s ease;
    }
    
    .modal-btn-submit {
      background: var(--text-primary);
      color: var(--background-primary);
      border: 2px solid var(--text-primary);
    }
    
    .modal-btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
      background: var(--background-primary);
      color: var(--text-primary);
    }
    
    .modal-btn-cancel {
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
    }
    
    .modal-btn-cancel:hover {
      background: var(--surface-hover);
      color: var(--text-primary);
    }
    
    .config-btn {
      background: var(--background-primary);
      color: var(--text-primary);
      border: 2px solid var(--text-primary);
      border-radius: 16px;
      width: 100%;
      padding: 16px 24px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: 24px;
      transition: all 0.3s ease;
      box-shadow: var(--shadow-md);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    
    .config-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
      background: var(--text-primary);
      color: var(--background-primary);
    }
    
    .setting-btn {
      margin-top: 16px !important;
    }
    
    .color-display {
      position: fixed;
      bottom: 24px;
      right: 24px;
      max-width: 320px;
      background: var(--background-secondary);
      border: 1px solid var(--border-color);
      backdrop-filter: blur(20px);
      padding: 16px;
      border-radius: 16px;
      box-shadow: var(--shadow-lg);
      z-index: 100;
    }
    
    .color-box {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 8px 0;
      font-size: 0.875rem;
      color: var(--text-primary);
      padding: 8px;
      background: var(--surface-color);
      border-radius: 8px;
    }
    
    .color-box button {
      padding: 6px 12px;
      font-size: 0.75rem;
      border: 1px solid var(--text-primary);
      background: var(--background-primary);
      color: var(--text-primary);
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    
    .color-box button:hover {
      background: var(--text-primary);
      color: var(--background-primary);
      transform: translateY(-1px);
    }
    
    .notes-container {
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 16px;
      padding: 20px;
      margin-top: 24px;
    }
    
    .note {
      font-size: 0.875rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 8px 0;
      font-weight: 500;
    }
    
    .note i {
      color: var(--text-primary);
      width: 16px;
    }
    
    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.05); opacity: 0.8; }
      100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes glow {
      0%, 100% { box-shadow: var(--glow-primary); }
      50% { box-shadow: 0 0 30px rgba(255, 0, 80, 0.5); }
    }
    
    .container {
      animation: fadeIn 0.6s ease-out;
    }
    
    @media (max-width: 768px) {
      .container {
        padding: 24px;
        margin: 16px;
      }
      
      .countdown-display {
        font-size: 4.5rem;
        font-weight: 900;
        text-shadow: 
          0 0 10px rgba(0, 0, 0, 0.3),
          0 0 20px rgba(0, 0, 0, 0.2),
          0 0 30px rgba(0, 0, 0, 0.1);
      }
      
      .app-title {
        font-size: 1.25rem;
      }
      
      .stats {
        flex-direction: column;
        gap: 12px;
      }
      
      .delta-controls {
        flex-wrap: nowrap;
        gap: 6px;
        overflow-x: auto;
        padding: 12px 8px;
      }
      
      .delta-btn {
        min-width: 45px;
        padding: 8px 10px;
        font-size: 0.7rem;
        flex-shrink: 0;
        white-space: nowrap;
      }
      
      .delta-text {
        min-width: 70px;
        font-size: 0.9rem;
        flex-shrink: 0;
      }
      
      .claim-btn {
        padding: 14px 24px;
        font-size: 1rem;
        min-width: 180px;
      }
      
      .color-display {
        max-width: calc(100vw - 32px);
        right: 16px;
        bottom: 16px;
        left: 16px;
      }
      
      .modal-content {
        width: 95%;
        padding: 24px;
      }
    }
    
    @media (max-width: 480px) {
      .countdown-display {
        font-size: 3.5rem;
        font-weight: 900;
        text-shadow: 
          0 0 15px rgba(0, 0, 0, 0.4),
          0 0 25px rgba(0, 0, 0, 0.3),
          0 0 35px rgba(0, 0, 0, 0.2),
          0 0 45px rgba(0, 0, 0, 0.1);
        margin: 24px 0;
      }
      
      .container {
        padding: 20px;
      }
      
      .delta-controls {
        padding: 10px 6px;
        gap: 4px;
      }
      
      .delta-btn {
        min-width: 40px;
        padding: 6px 8px;
        font-size: 0.65rem;
      }
      
      .delta-text {
        min-width: 65px;
        font-size: 0.85rem;
      }
      
      .notes-container {
        padding: 16px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="app-title">
        <i class="fas fa-treasure-chest"></i>
        CANH MỞ RƯƠNG
      </div>
      <div class="app-subtitle">huycoihd</div>
    </div>

    <?php if($username): ?>
    <div class="user-info" id="usernameDisplay">
      <i class="fas fa-user"></i>
      <span>@<?php echo htmlspecialchars($username); ?></span>
    </div>
    <?php endif; ?>
    
    <div class="stats">
      <div class="stat-item">
        <i class="fas fa-eye stat-icon"></i>
        <span id="viewCount"><?php echo htmlspecialchars($viewCount); ?></span>/<span id="peopleCount"><?php echo htmlspecialchars($peopleCount); ?></span>
      </div>
      <?php if($matxem): ?>
      <div class="stat-item">
        <i class="fas fa-users stat-icon"></i>
        <span><?php echo htmlspecialchars($matxem); ?></span>
      </div>
      <?php endif; ?>
    </div>
    
    <div class="delta-controls">
      <button class="delta-btn" id="btn-decrease-0.05">-0.05s</button>
      <button class="delta-btn" id="btn-decrease-0.01">-0.01s</button>
      <span class="delta-text" id="delta-text">±0.00s</span>
      <button class="delta-btn" id="btn-increase-0.01">+0.01s</button>
      <button class="delta-btn" id="btn-increase-0.05">+0.05s</button>
    </div>
    
    <div class="progress-container">
      <div class="progress-bar" id="progress"></div>
    </div>

    <div class="countdown-display" id="countdown">00.0</div>
    
    <?php if($timeunbound): ?>
    <div class="status-message">
      <i class="fas fa-clock"></i>
      <span><?php echo htmlspecialchars($timeunbound); ?></span>
    </div>
    <?php endif; ?>
    
    <div class="status-message" id="status"></div>
     
    <button class="claim-btn" id="claimBtn">
      <i class="fab fa-tiktok"></i>
      <span>Mở Live</span>
    </button>

    <div class="notes-container">
      <div class="note">
        <i class="fas fa-sync-alt"></i>
        <span>Bật tự động cập nhật thời gian</span>
      </div>
      <div class="note">
        <i class="fas fa-microchip"></i>
        <span>Sử dụng thiết bị có hiệu năng tốt</span>
      </div>
      <div class="note">
        <i class="fas fa-times-circle"></i>
        <span>Đóng các ứng dụng không cần thiết</span>
      </div>
      
      <button class="config-btn" id="btn-open">
        <i class="fas fa-palette"></i>
        <span>Cấu hình màu sắc theo thời gian</span>
      </button>
      
      <?php if($chatid): ?>
      <button class="config-btn setting-btn" id="btn-setting" onclick="openBotSetting()">
        <i class="fas fa-cog"></i>
        <span>Cài đặt Bot</span>
      </button>
      <?php endif; ?>
    </div>
  </div>
  <div class="modal" id="modal">
    <div class="modal-content">
      <div class="modal-title">
        <i class="fas fa-palette"></i>
        Cấu hình màu nền theo thời gian
      </div>
      <div class="form-group">
        <label for="start_seconds">
          <i class="fas fa-play"></i>
          Thời gian bắt đầu (giây):
        </label>
        <input type="number" id="start_seconds" step="0.1" min="0" placeholder="Ví dụ: 10.5">
      </div>
      <div class="form-group">
        <label for="end_seconds">
          <i class="fas fa-stop"></i>
          Thời gian kết thúc (giây):
        </label>
        <input type="number" id="end_seconds" step="0.1" min="0" placeholder="Ví dụ: 5.0">
      </div>
      <div class="form-group">
        <label for="hex_background_color">
          <i class="fas fa-fill-drip"></i>
          Màu nền:
        </label>
        <input type="color" id="hex_background_color" value="#ff0050">
      </div>
      <div class="modal-actions">
        <button class="modal-btn modal-btn-cancel" id="btn-cancel">
          <i class="fas fa-times"></i>
          Hủy
        </button>
        <button class="modal-btn modal-btn-submit" id="btn-submit">
          <i class="fas fa-save"></i>
          Lưu cấu hình
        </button>
      </div>
    </div>
  </div>

  <div id="color-display"></div>

<script>
  // Các biến PHP sang JS
  const rawTime = <?php echo ($rawTime !== null) ? intval($rawTime) : 'null'; ?>;
  const tiktokUrl = <?php echo json_encode($tiktokUrl); ?>;
  const username = <?php echo json_encode($username); ?>;
  const viewCount = <?php echo json_encode($viewCount); ?>;
  const peopleCount = <?php echo json_encode($peopleCount); ?>;
  const nowGmt7 = <?php echo $nowGmt7; ?>; // Server time in milliseconds
  const chatid = <?php echo json_encode($chatid); ?>;

  // Function to open bot setting
  function openBotSetting() {
    if (chatid) {
      const settingUrl = `https://api.hubmmo.pro/tiktok/config.php?chat_id=${chatid}`;
      window.open(settingUrl, '_blank');
    } else {
      alert('Không tìm thấy Chat ID để mở cài đặt bot!');
    }
  }

  // Delta time functions
  function getDeltaTime() {
    try {
      return JSON.parse(localStorage.getItem("delta_time") || "0") || 0;
    } catch {
      return 0;
    }
  }
  
  function setDeltaTime(input) {
    try {
      localStorage.setItem("delta_time", JSON.stringify(input));
    } catch {}
  }

  // Color times functions
  function getColorTimes() {
    try {
      return JSON.parse(localStorage.getItem("color_times") || "[]") || [];
    } catch {
      return [];
    }
  }
  
  function addColorTime(input) {
    try {
      const colorTimes = getColorTimes();
      colorTimes.push(input);
      localStorage.setItem("color_times", JSON.stringify(colorTimes));
    } catch {}
  }
  
  function deleteColorTime(id) {
    try {
      const colorTimes = getColorTimes();
      const newColorTimes = colorTimes.filter(
        (item) => `${item?.id || ""}` !== `${id || ""}`
      );
      localStorage.setItem("color_times", JSON.stringify(newColorTimes));
    } catch {}
  }

  let globalColorTimes = getColorTimes();
  let delta = getDeltaTime();
  
  function updateDeltaDisplay() {
    const showDelta = delta / 1000;
    document.getElementById("delta-text").style.color =
      showDelta === 0 ? "white" : showDelta > 0 ? "#28b92c" : "#ff4444";
    document.getElementById("delta-text").innerHTML = `<b>${
      showDelta > 0 ? "+" : ""
    }${showDelta.toFixed(2)}s</b>`;
  }
  
  updateDeltaDisplay();

  const countdownElement = document.getElementById("countdown");
  const statusElement = document.getElementById("status");
  const progressElement = document.getElementById("progress");
  const claimBtn = document.getElementById("claimBtn");

  if (isNaN(rawTime) || rawTime === null) {
    countdownElement.textContent = "00.0";
    statusElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i><span>Thiếu tham số thời gian</span>';
    statusElement.className = "status-message error-message";
    progressElement.style.width = "0%";
  } else {
    const targetTime = rawTime * 1000; // Target time in milliseconds
    const totalDuration = targetTime - nowGmt7;
    const clientStartTime = performance.now();

    function update() {
      const elapsedClientTime = performance.now() - clientStartTime;
      const now = nowGmt7 + elapsedClientTime; // Current time based on server time + client elapsed
      const diffMs = targetTime - now + delta; // Apply delta time adjustment
      const elapsed = now - nowGmt7;
      const progress = Math.min(100, (elapsed / totalDuration) * 100);

      if (diffMs <= 0) {
        countdownElement.textContent = "0.0";
        statusElement.innerHTML = '<i class="fas fa-trophy"></i><span>Thời gian đã đến! Hãy mở live ngay!</span>';
        statusElement.className = "status-message success-message";
        progressElement.style.width = "100%";
        
        // Add glow effect to claim button
        claimBtn.style.animation = "glow 1s infinite";
        return;
      }

      const sec = Math.max(0, diffMs / 1000).toFixed(1);
      countdownElement.textContent = sec.padStart(4, '0');
      progressElement.style.width = `${progress}%`;

      // Apply color if configured
      try {
        const match = globalColorTimes.find(
          (ct) => ct.end_seconds <= sec && sec <= ct.start_seconds
        );
        const color = match?.hex_background_color || "var(--background-color)";
        if (document.body.style.backgroundColor !== color) {
          document.body.style.backgroundColor = color;
        }
      } catch {}

      requestAnimationFrame(update);
    }

    claimBtn.addEventListener('click', () => {
      if (tiktokUrl) {
        // Add click effect
        claimBtn.style.transform = "scale(0.95)";
        setTimeout(() => {
          claimBtn.style.transform = "";
        }, 150);
        
        window.open(tiktokUrl, '_blank');
      } else {
        // Show error with icon
        statusElement.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Không tìm thấy liên kết TikTok LIVE!</span>';
        statusElement.className = "status-message error-message";
      }
    });

    update();
  }

  // Display color config
  function displayColors() {
    try {
      globalColorTimes = getColorTimes();
      const container = document.getElementById("color-display");
      container.innerHTML = "";

      if (globalColorTimes.length) {
        container.style.display = "block";
      } else {
        container.style.display = "none";
      }

      globalColorTimes.forEach((item) => {
        const div = document.createElement("div");
        div.classList.add("color-box");
        div.innerHTML = `
          <button onclick="deleteItem('${item.id}')">Xóa</button>
          <span><b>${item.start_seconds}s</b> → <b>${item.end_seconds}s</b> → </span>
          <div style="height: 30px; width: 30px; background-color: ${item.hex_background_color}; border-radius: 50%; border: 2px solid gray;"></div>
        `;
        container.appendChild(div);
      });
    } catch {}
  }

  function deleteItem(id) {
    if (window.confirm("Bạn có chắc chắn muốn xóa không?")) {
      deleteColorTime(id);
      displayColors();
    }
  }

  function submitData() {
    let start_seconds = document.getElementById("start_seconds").value.trim();
    let end_seconds = document.getElementById("end_seconds").value.trim();
    const hex_background_color = document.getElementById("hex_background_color").value.trim();

    if (!start_seconds) {
      document.getElementById("start_seconds").focus();
      document.getElementById("start_seconds").style.borderColor = "var(--error-color)";
      return;
    }
    if (!end_seconds) {
      document.getElementById("end_seconds").focus();
      document.getElementById("end_seconds").style.borderColor = "var(--error-color)";
      return;
    }
    if (!hex_background_color) {
      document.getElementById("hex_background_color").focus();
      return;
    }

    start_seconds = parseFloat(start_seconds);
    end_seconds = parseFloat(end_seconds);

    if (isNaN(start_seconds) || start_seconds < 0) {
      document.getElementById("start_seconds").focus();
      document.getElementById("start_seconds").style.borderColor = "var(--error-color)";
      return;
    }
    if (isNaN(end_seconds) || end_seconds < 0) {
      document.getElementById("end_seconds").focus();
      document.getElementById("end_seconds").style.borderColor = "var(--error-color)";
      return;
    }

    document.getElementById("modal").classList.remove("show");

    addColorTime({
      id: `${Date.now()}${Math.round(Math.random() * 1000)}`,
      start_seconds: Math.max(start_seconds, end_seconds),
      end_seconds: Math.min(start_seconds, end_seconds),
      hex_background_color,
    });

    document.getElementById("start_seconds").value = "";
    document.getElementById("end_seconds").value = "";
    document.getElementById("hex_background_color").value = "#ff0050";

    displayColors();
  }

  document.addEventListener("DOMContentLoaded", function () {
    displayColors();

    document.getElementById("btn-open").addEventListener("click", function () {
      document.getElementById("modal").classList.add("show");
    });

    document.getElementById("btn-submit").addEventListener("click", submitData);

    document.getElementById("btn-cancel").addEventListener("click", function () {
      document.getElementById("modal").classList.remove("show");
    });

    document.getElementById("btn-increase-0.01").addEventListener("click", () => {
      delta += 10;
      updateDeltaDisplay();
      setDeltaTime(delta);
    });

    document.getElementById("btn-increase-0.05").addEventListener("click", () => {
      delta += 50;
      updateDeltaDisplay();
      setDeltaTime(delta);
    });

    document.getElementById("btn-decrease-0.01").addEventListener("click", () => {
      delta -= 10;
      updateDeltaDisplay();
      setDeltaTime(delta);
    });

    document.getElementById("btn-decrease-0.05").addEventListener("click", () => {
      delta -= 50;
      updateDeltaDisplay();
      setDeltaTime(delta);
    });

    // Reset border colors when user starts typing
    document.getElementById("start_seconds").addEventListener("input", function() {
      this.style.borderColor = "var(--border-color)";
    });
    
    document.getElementById("end_seconds").addEventListener("input", function() {
      this.style.borderColor = "var(--border-color)";
    });

    // Add smooth transitions for better UX
    document.querySelectorAll('.delta-btn').forEach(btn => {
      btn.addEventListener('mousedown', function() {
        this.style.transform = 'translateY(-1px) scale(0.95)';
      });
      
      btn.addEventListener('mouseup', function() {
        this.style.transform = 'translateY(-1px)';
      });
      
      btn.addEventListener('mouseleave', function() {
        this.style.transform = '';
      });
    });
  });
</script>
</body>
</html>
