<?php
// login_process.php - Proses login dan register

// ===================== Mulai Session =====================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ===================== Include Konfigurasi Database =====================
require_once 'config.php';

// ===================== Proses Registrasi User Baru =====================
// Jika request POST dan terdapat field 'name', berarti proses register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // --- Validasi input register ---
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Semua field harus diisi!'
        ];
        $_SESSION['active_form'] = 'register';
        header('Location: index.php');
        exit();
    }
    if (strlen($password) < 6) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Password minimal 6 karakter!'
        ];
        $_SESSION['active_form'] = 'register';
        header('Location: index.php');
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Format email tidak valid!'
        ];
        $_SESSION['active_form'] = 'register';
        header('Location: index.php');
        exit();
    }

    // --- Cek email sudah terdaftar atau belum ---
    $check_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Email sudah terdaftar!'
        ];
        $_SESSION['active_form'] = 'register';
        header('Location: index.php');
        exit();
    }
    $check_stmt->close();

    // --- Hash password dan simpan user baru ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($insert_stmt->execute()) {
        // Registrasi sukses
        $_SESSION['alerts'][] = [
            'type' => 'success',
            'message' => 'Registrasi berhasil! Silakan login untuk melanjutkan.'
        ];
        $_SESSION['active_form'] = 'login';
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Gagal mendaftarkan akun. Silakan coba lagi.'
        ];
        $_SESSION['active_form'] = 'register';
        header('Location: index.php');
        exit();
    }
}

// ===================== Proses Login User =====================
// Jika request POST dan tidak ada field 'name', berarti proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['name'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // --- Validasi input login ---
    if (empty($email) || empty($password)) {
        $_SESSION['alerts'][] = [
            'type' => 'error',
            'message' => 'Email dan password harus diisi!'
        ];
        $_SESSION['active_form'] = 'login';
        header('Location: index.php');
        exit();
    }

    // --- Cek user berdasarkan email ---
    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        // --- Verifikasi password ---
        if (password_verify($password, $user['password'])) {
            // Set session login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Redirect ke dashboard
            header('Location: dashboard.php');
            exit();
        }
    }

    // --- Jika login gagal (email/password salah) ---
    $_SESSION['alerts'][] = [
        'type' => 'error',
        'message' => 'Email atau password salah!'
    ];
    $_SESSION['active_form'] = 'login';
    header('Location: index.php');
    exit();
}

// ===================== Redirect Default Jika Tidak Ada Aksi =====================
header('Location: index.php');
exit();
?>