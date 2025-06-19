<?php
// logout.php - Handler untuk logout user

session_start();

// Ambil username dari session sebelum destroy
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

// (Opsional) Anda bisa log aktivitas logout ke database di sini jika mau, contoh MySQLi:
// require_once 'config.php';
// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
//     $ip_address = $_SERVER['REMOTE_ADDR'];
//     $log_query = "INSERT INTO user_activity_log (user_id, activity, ip_address, created_at) VALUES (?, 'logout', ?, NOW())";
//     $stmt = $conn->prepare($log_query);
//     $stmt->bind_param("is", $user_id, $ip_address);
//     $stmt->execute();
// }

// Destroy session
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Mulai session baru untuk flash message
session_start();
session_regenerate_id(true); // Pastikan session benar-benar baru

$_SESSION['alerts'][] = [
    'type' => 'success',
    'message' => "Sampai jumpa, {$user_name}! Anda telah berhasil logout."
];

// Redirect ke halaman login
header('Location: index.php');
exit();
?>