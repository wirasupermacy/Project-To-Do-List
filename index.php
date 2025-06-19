<?php
// Start session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DEBUG: Tampilkan jika ada data POST yang masuk ke index.php
if ($_POST) {
    echo "<div style='background: yellow; padding: 10px; margin: 10px;'>";
    echo "<strong>POST data received in index.php:</strong><br>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    echo "</div>";
}

// Sertakan fungsi autentikasi
require_once 'auth_check.php';

// Ambil data session username, alert, dan form aktif
$username = $_SESSION['username'] ?? null;
$alerts = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? '';

// Hapus alert dan form aktif dari session setelah diambil
unset($_SESSION['alerts']);
unset($_SESSION['active_form']);

// Jika sudah login, redirect ke dashboard
if ($username !== null) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <title>Login & Registration - To Do List</title> <!-- Judul halaman -->
    <!-- Import Boxicons untuk ikon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Import file CSS utama -->
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <section>
        <h1>To Do List</h1> <!-- Judul aplikasi -->
    </section>
    
    <!-- Alert Messages (Notifikasi) -->
    <?php if (!empty($alerts)): ?>
    <div class="alert-container">
        <?php foreach ($alerts as $alert): ?> 
        <div class="alert <?= htmlspecialchars($alert['type']); ?>">
            <!-- Icon sesuai tipe alert -->
            <i class='bx <?= $alert['type'] === 'success' ? 'bxs-check-circle' : ($alert['type'] === 'warning' ? 'bxs-error' : 'bxs-x-circle'); ?>'></i> 
            <span><?= htmlspecialchars($alert['message']); ?></span>
            <!-- Tombol close alert -->
            <button onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; margin-left: auto; cursor: pointer; padding: 0 5px;">&times;</button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Container form login/register, class slide/show tergantung session -->
    <div class="login-users <?= $active_form === 'register' ? 'show slide' : ($active_form === 'login' ? 'show' : ''); ?>">
        <!-- Login Form -->
        <div class="form-box login">
            <h2>Login</h2>
            <form action="login_process.php" method="POST" id="loginForm">
                <!-- Input email login -->
                <div class="input-box">
                    <input type="email" name="email" id="loginEmail" placeholder="Email" required>
                    <i class='bx bx-envelope-open'></i> 
                </div>
                <!-- Input password login + toggle -->
                <div class="input-box">
                    <input type="password" name="password" id="loginPassword" placeholder="Password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')">
                        <i class='bx bx-show'></i>
                    </button>
                    <i class='bx bxs-lock'></i> 
                </div>
                <!-- Tombol submit login -->
                <button type="submit" name="login_btn" class="btn" id="loginBtn">Login</button>
                <!-- Link ke form register -->
                <p>Belum punya akun? <a href="#" class="register-link">Daftar di sini</a></p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box register">
            <h2>Daftar</h2>
            <form action="login_process.php" method="POST" id="registerForm">
                <!-- Input nama lengkap -->
                <div class="input-box">
                    <input type="text" name="name" id="registerName" placeholder="Nama Lengkap" required minlength="2">
                    <i class='bx bxs-user'></i> 
                </div>
                <!-- Input email register -->
                <div class="input-box">
                    <input type="email" name="email" id="registerEmail" placeholder="Email" required>
                    <i class='bx bx-envelope-open'></i> 
                </div>
                <!-- Input password register + toggle + strength meter -->
                <div class="input-box">
                    <input type="password" name="password" id="registerPassword" placeholder="Password" required minlength="6">
                    <button type="button" class="password-toggle" onclick="togglePassword('registerPassword')">
                        <i class='bx bx-show'></i>
                    </button>
                    <i class='bx bxs-lock'></i> 
                    <!-- Bar kekuatan password -->
                    <div class="strength-meter" id="strengthMeter">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>
                </div>
                <!-- Tombol submit register -->
                <button type="submit" name="register_btn" class="btn" id="registerBtn">Daftar</button>
                <!-- Link ke form login -->
                <p>Sudah punya akun? <a href="#" class="login-link">Login di sini</a></p>
            </form>
        </div>
    </div>
    <!-- Import script JS utama -->
    <script src="script.js"></script>
</body>
</html>