<?php
// config.php - Konfigurasi database menggunakan MySQLi saja

// Variabel koneksi database
$servername = "localhost"; // Host database
$username = "root";        // Username database
$password = "";            // Password database
$dbname = "todolist";      // Nama database

// Membuat koneksi MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi MySQLi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset koneksi MySQLi ke UTF-8
$conn->set_charset("utf8");
?>