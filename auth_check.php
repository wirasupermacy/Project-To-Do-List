<?php
// auth_check.php
require_once 'config.php';

/**
 * Mengecek apakah user sudah login.
 * Jika belum login, user akan diarahkan ke halaman login (index.php)
 * dan diberikan pesan error di session.
 * 
 * @return bool true jika sudah login, jika tidak maka proses akan dihentikan (exit)
 */
function checkAuth() {
    // Pastikan session sudah aktif
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Jika user belum login (tidak ada username di session)
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        // Simpan pesan error ke session untuk ditampilkan di halaman login
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Anda harus login terlebih dahulu untuk mengakses dashboard!'
        ];
        // Set form aktif ke login agar form login yang tampil
        $_SESSION['active_form'] = 'login';
        // Redirect ke halaman login
        header('Location: index.php');
        exit();
    }
    
    // Jika sudah login, lanjutkan proses
    return true;
}

/**
 * Mengambil data user yang sedang login dari session.
 * 
 * @return array|null Data user (id, username, email, waktu login, aktivitas terakhir)
 *                    atau null jika belum login.
 */
function getUserInfo() {
    // Pastikan session sudah aktif
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Jika belum login, kembalikan null
    if (!isset($_SESSION['username'])) {
        return null;
    }
    
    // Kembalikan data user dari session
    return [
        'id' => $_SESSION['user_id'] ?? 0,
        'username' => $_SESSION['username'] ?? 'User', // Username user
        'email' => $_SESSION['email'] ?? '',
        'login_time' => $_SESSION['last_activity'] ?? time(),
        'last_activity' => $_SESSION['last_activity'] ?? time()
    ];
}

/**
 * Mengecek apakah user sudah login.
 * 
 * @return bool true jika sudah login, false jika belum login.
 */
function isLoggedIn() {
    // Pastikan session sudah aktif
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Cek apakah username ada di session
    return isset($_SESSION['username']) && !empty($_SESSION['username']);
}

/**
 * Untuk API: Pastikan user sudah login.
 * Jika belum login, kirim response JSON error dan hentikan proses.
 */
function requireLogin() {
    if (!isLoggedIn()) {
        http_response_code(401); // Status 401: Unauthorized
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'authentication_required',
            'message' => 'Authentication required'
        ]);
        exit();
    }
}

/**
 * Jika user sudah login, redirect ke halaman lain (default: dashboard.php).
 * 
 * @param string $redirect_to Halaman tujuan redirect.
 */
function redirectIfLoggedIn($redirect_to = 'dashboard.php') {
    if (isLoggedIn()) {
        header('Location: ' . $redirect_to);
        exit();
    }
}

// ==================
// Timeout Session
// ==================

// Lama waktu tidak aktif sebelum logout otomatis (detik)
$timeout = 1200; // 20 menit

// Jika sudah login dan waktu tidak aktif melebihi batas timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); // Hapus semua data session
    session_destroy(); // Hancurkan session
    header('Location: index.php'); // Redirect ke login
    exit();
}

// Update waktu aktivitas terakhir setiap request
$_SESSION['last_activity'] = time();
?>