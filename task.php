<?php
// task.php - Fungsi CRUD Task menggunakan MySQLi

require_once 'config.php';

/**
 * Membuat task baru.
 */
function createTask($conn, $user_id, $title, $description, $due_date, $priority) {
    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issss", $user_id, $title, $description, $due_date, $priority);
    return $stmt->execute();
}

/**
 * Mengambil semua task milik user (dengan filter opsional).
 */
function getTasksByUser($conn, $user_id, $status = null, $search = null, $order_by = 'created_at', $order_dir = 'DESC') {
    $allowed_order = ['created_at', 'due_date', 'priority', 'title', 'status'];
    if (!in_array($order_by, $allowed_order)) $order_by = 'created_at';
    if (!in_array(strtoupper($order_dir), ['ASC', 'DESC'])) $order_dir = 'DESC';

    $query = "SELECT * FROM tasks WHERE user_id = ?";
    $types = "i";
    $params = [$user_id];

    if ($status) {
        $query .= " AND status = ?";
        $types .= "s";
        $params[] = $status;
    }
    if ($search) {
        $query .= " AND (title LIKE ? OR description LIKE ?)";
        $types .= "ss";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
    }
    $query .= " ORDER BY $order_by $order_dir";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    return $tasks;
}

/**
 * Mengambil satu task berdasarkan id dan user_id.
 */
function getTaskById($conn, $id, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Update task.
 */
function updateTask($conn, $id, $user_id, $title, $description, $due_date, $priority, $status) {
    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, due_date=?, priority=?, status=?, updated_at=NOW() WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssssi", $title, $description, $due_date, $priority, $status, $id, $user_id);
    return $stmt->execute();
}

/**
 * Hapus task.
 */
function deleteTask($conn, $id, $user_id) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}
?>