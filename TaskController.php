<?php
// TaskController.php - API untuk CRUD Tasks (MySQLi Only)

// Start session dan include dependencies
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
require_once 'auth_check.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Function untuk mengirim JSON response
function sendResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit();
}

// Check authentication
if (!isLoggedIn()) {
    sendResponse(false, 'Authentication required', ['error' => 'authentication_required']);
}

// Get user info
$user = getUserInfo();
$user_id = $user['id'];

// Get action dari URL parameter atau POST
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        handleCreateTask($conn, $user_id);
        break;
    case 'read':
        handleReadTasks($conn, $user_id);
        break;
    case 'update':
        handleUpdateTask($conn, $user_id);
        break;
    case 'delete':
        handleDeleteTask($conn, $user_id);
        break;
    case 'toggle':
        handleToggleTask($conn, $user_id);
        break;
    case 'stats':
        handleGetStats($conn, $user_id);
        break;
    default:
        sendResponse(false, 'Invalid action');
}

// CREATE - Tambah task baru
function handleCreateTask($conn, $user_id) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = $_POST['due_date'] ?? null;
    $due_time = $_POST['due_time'] ?? null;
    $priority = $_POST['priority'] ?? 'medium';

    // Validasi
    if (empty($title)) sendResponse(false, 'Judul tugas harus diisi');
    if (strlen($title) > 200) sendResponse(false, 'Judul tugas terlalu panjang (maksimal 200 karakter)');
    if (strlen($description) > 1000) sendResponse(false, 'Deskripsi terlalu panjang (maksimal 1000 karakter)');
    if (!in_array($priority, ['low', 'medium', 'high'])) $priority = 'medium';

    // Validasi dan format datetime
    $due_datetime = null;
    if (!empty($due_date)) {
        if (!DateTime::createFromFormat('Y-m-d', $due_date)) sendResponse(false, 'Format tanggal tidak valid (gunakan YYYY-MM-DD)');
        if (!empty($due_time)) {
            if (!DateTime::createFromFormat('H:i', $due_time)) sendResponse(false, 'Format jam tidak valid (gunakan HH:MM)');
            $due_datetime = $due_date . ' ' . $due_time . ':00';
        } else {
            $due_datetime = $due_date . ' 23:59:59';
        }
        $due_dt = new DateTime($due_datetime);
        $now = new DateTime();
        if ($due_dt->format('Y-m-d') < $now->format('Y-m-d')) sendResponse(false, 'Tanggal jatuh tempo tidak boleh di masa lalu');
        if ($due_dt->format('Y-m-d') === $now->format('Y-m-d') && !empty($due_time) && $due_dt < $now) sendResponse(false, 'Jam jatuh tempo tidak boleh di masa lalu untuk hari ini');
    }

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issss", $user_id, $title, $description, $due_datetime, $priority);
    if ($stmt->execute()) {
        sendResponse(true, 'Tugas berhasil ditambahkan!');
    } else {
        sendResponse(false, 'Gagal menambahkan tugas');
    }
}

// READ - Ambil daftar tasks
function handleReadTasks($conn, $user_id) {
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    $order_by = $_GET['order_by'] ?? 'created_at';
    $order_dir = $_GET['order_dir'] ?? 'DESC';

    $allowed_order = ['created_at', 'due_date', 'priority', 'title', 'status'];
    if (!in_array($order_by, $allowed_order)) $order_by = 'created_at';
    if (!in_array(strtoupper($order_dir), ['ASC', 'DESC'])) $order_dir = 'DESC';

    $query = "SELECT *, 
                CASE 
                    WHEN status = 'pending' AND due_date IS NOT NULL AND due_date < NOW() THEN 'overdue'
                    ELSE status 
                END as display_status
              FROM tasks WHERE user_id = ?";
    $types = "i";
    $params = [$user_id];

    if (!empty($search)) {
        $query .= " AND (title LIKE ? OR description LIKE ?)";
        $types .= "ss";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
    }

    if (!empty($status)) {
        if ($status === 'overdue') {
            $query .= " AND status = 'pending' AND due_date IS NOT NULL AND due_date < NOW()";
        } else {
            $query .= " AND status = ?";
            $types .= "s";
            $params[] = $status;
        }
    }

    if ($order_by === 'priority') {
        $query .= " ORDER BY FIELD(priority, 'high', 'medium', 'low') $order_dir";
    } else {
        $query .= " ORDER BY $order_by $order_dir";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        // Tambahkan due_time
        if (!empty($row['due_date'])) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $row['due_date']);
            $row['due_time'] = $dt ? $dt->format('H:i') : '';
        } else {
            $row['due_time'] = '';
        }
        $tasks[] = $row;
    }
    sendResponse(true, 'Tasks loaded successfully', ['tasks' => $tasks]);
}

// UPDATE - Update task
function handleUpdateTask($conn, $user_id) {
    $task_id = $_POST['task_id'] ?? 0;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = $_POST['due_date'] ?? null;
    $due_time = $_POST['due_time'] ?? null;
    $priority = $_POST['priority'] ?? 'medium';
    $status = $_POST['status'] ?? 'pending';

    if (empty($task_id) || empty($title)) sendResponse(false, 'Data tidak lengkap');

    // Cek apakah task milik user
    $check_stmt = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $task_id, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows === 0) sendResponse(false, 'Task tidak ditemukan atau bukan milik Anda');
    $check_stmt->close();

    if (strlen($title) > 200) sendResponse(false, 'Judul tugas terlalu panjang');
    if (!in_array($status, ['pending', 'completed'])) $status = 'pending';
    if (!in_array($priority, ['low', 'medium', 'high'])) $priority = 'medium';

    $due_datetime = null;
    if (!empty($due_date)) {
        if (!DateTime::createFromFormat('Y-m-d', $due_date)) sendResponse(false, 'Format tanggal tidak valid (gunakan YYYY-MM-DD)');
        if (!empty($due_time)) {
            if (!DateTime::createFromFormat('H:i', $due_time)) sendResponse(false, 'Format jam tidak valid (gunakan HH:MM)');
            $due_datetime = $due_date . ' ' . $due_time . ':00';
        } else {
            $due_datetime = $due_date . ' 23:59:59';
        }
        if ($status === 'pending') {
            $due_dt = new DateTime($due_datetime);
            $now = new DateTime();
            if ($due_dt->format('Y-m-d') < $now->format('Y-m-d')) sendResponse(false, 'Tanggal jatuh tempo tidak boleh di masa lalu untuk tugas yang belum selesai');
            if ($due_dt->format('Y-m-d') === $now->format('Y-m-d') && !empty($due_time) && $due_dt < $now) sendResponse(false, 'Jam jatuh tempo tidak boleh di masa lalu untuk hari ini');
        }
    }

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, due_date=?, priority=?, status=?, updated_at=NOW() WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssi", $title, $description, $due_datetime, $priority, $status, $task_id, $user_id);
    if ($stmt->execute()) {
        sendResponse(true, 'Tugas berhasil diupdate!');
    } else {
        sendResponse(false, 'Gagal mengupdate tugas');
    }
}

// DELETE - Hapus task
function handleDeleteTask($conn, $user_id) {
    $task_id = $_POST['task_id'] ?? 0;
    if (empty($task_id)) sendResponse(false, 'Task ID tidak valid');

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        sendResponse(true, 'Tugas berhasil dihapus!');
    } else {
        sendResponse(false, 'Task tidak ditemukan atau gagal dihapus');
    }
}

// TOGGLE - Toggle status task
function handleToggleTask($conn, $user_id) {
    $task_id = $_POST['task_id'] ?? 0;
    if (empty($task_id)) sendResponse(false, 'Task ID tidak valid');

    // Get current status
    $get_stmt = $conn->prepare("SELECT status FROM tasks WHERE id=? AND user_id=?");
    $get_stmt->bind_param("ii", $task_id, $user_id);
    $get_stmt->execute();
    $result = $get_stmt->get_result();
    if ($result->num_rows === 0) sendResponse(false, 'Task tidak ditemukan');
    $row = $result->fetch_assoc();
    $current_status = $row['status'];
    $new_status = ($current_status === 'completed') ? 'pending' : 'completed';

    // Update status
    $update_stmt = $conn->prepare("UPDATE tasks SET status=?, updated_at=NOW() WHERE id=? AND user_id=?");
    $update_stmt->bind_param("sii", $new_status, $task_id, $user_id);
    if ($update_stmt->execute()) {
        $message = ($new_status === 'completed') ? 'Tugas ditandai selesai!' : 'Tugas ditandai belum selesai!';
        sendResponse(true, $message);
    } else {
        sendResponse(false, 'Gagal mengupdate status tugas');
    }
}

// STATS - Get task statistics
function handleGetStats($conn, $user_id) {
    $stmt = $conn->prepare("SELECT 
        COUNT(*) as total_tasks,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
        SUM(CASE WHEN due_date IS NOT NULL AND due_date < NOW() AND status = 'pending' THEN 1 ELSE 0 END) as overdue_tasks
        FROM tasks WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = $result->fetch_assoc();
    sendResponse(true, 'Stats loaded successfully', ['stats' => $stats]);
}
?>