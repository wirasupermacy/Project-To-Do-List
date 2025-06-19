<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Memanggil file auth_check.php untuk pengecekan autentikasi
require_once 'auth_check.php';
// Memastikan user sudah login, jika tidak akan diarahkan ke login
checkAuth();

// Mengambil data user yang sedang login
$user = getUserInfo();

// Debug: Untuk melihat data session dan user (bisa diaktifkan jika perlu)
// echo "<pre>SESSION DEBUG: " . print_r($_SESSION, true) . "</pre>";
// echo "<pre>USER DEBUG: " . print_r($user, true) . "</pre>";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <title>Todo List - Dashboard</title> <!-- Judul halaman -->
    <!-- Import Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Import file CSS utama dashboard -->
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Alert jika user tidak terautentikasi -->
    <div id="auth-protection-alert" class="auth-protection-alert" style="display:none;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-lock"></i>
            <div>
                <strong>Akses Ditolak!</strong><br>
                Anda harus login terlebih dahulu untuk mengakses dashboard.
                <a href="index.php" style="color: #742a2a; text-decoration: underline;">Login di sini</a>
            </div>
        </div>
    </div>

    <div class="container"> <!-- Container utama -->
        <!-- Header dashboard -->
        <div class="header">
            <h1><i class="fas fa-tasks"></i> Todo List</h1>
            <div class="header-actions">
                <!-- Menampilkan nama user yang login -->
                <h1><i>👤 </i>Selamat datang, <?php echo htmlspecialchars($user['username'] ?? 'User'); ?></h1>
                <!-- Tombol logout -->
                <button class="btn btn-danger" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </div>
        </div>

        <!-- Statistik tugas -->
        <div class="stats-grid">
            <!-- Total tugas -->
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-number" id="total-tasks">0</div>
                <div class="stat-label">Total Tugas</div>
            </div>
            <!-- Tugas selesai -->
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number" id="completed-tasks">0</div>
                <div class="stat-label">Selesai</div>
            </div>
            <!-- Tugas pending -->
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="pending-tasks">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <!-- Tugas terlambat -->
            <div class="stat-card">
                <div class="stat-icon overdue">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number" id="overdue-tasks">0</div>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>

        <!-- Alert notifikasi -->
        <div id="alert" class="alert">
            <button class="alert-close" onclick="closeAlert()">&times;</button>
            <span id="alert-message"></span>
        </div>

        <!-- Konten utama dashboard -->
        <div class="main-content">
            <!-- Form tambah tugas -->
            <div class="task-form-section">
                <h2 class="section-title">➕ Tambah Tugas Baru</h2>
                <div class="task-form-content">
                <form id="task-form">
                    <!-- Input judul tugas -->
                    <div class="form-group">
                        <label class="form-label" for="title">Judul Tugas</label>
                        <input type="text" id="title" name="title" class="form-input" required maxlength="200">
                    </div>
                    <!-- Input deskripsi tugas -->
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-textarea" rows="3" maxlength="1000"></textarea>
                    </div>
                    <!-- Input tanggal jatuh tempo -->
                    <div class="form-group">
                        <label class="form-label" for="due_date">Tanggal Jatuh Tempo</label>
                        <input type="date" id="due_date" name="due_date" class="form-input" required>
                    </div>
                    <!-- Input jam jatuh tempo -->
                    <div class="form-group">
                        <label class="form-label" for="due_time">Jam Jatuh Tempo</label>
                        <input type="time" id="due_time" name="due_time" class="form-input" required>
                    </div>
                    <!-- Pilihan prioritas tugas -->
                    <div class="form-group">
                        <label class="form-label" for="priority">Prioritas</label>
                        <select id="priority" name="priority" class="form-select">
                            <option value="low">Rendah</option>
                            <option value="medium" selected>Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                    <!-- Tombol submit tambah tugas -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Tugas
                    </button>
                </form>
             </div>
            </div>

            <!-- Daftar tugas -->
            <div class="task-list-section">
                <h2 class="section-title">📋 Daftar Tugas</h2>
                <div class="task-list-content">
                    <!-- Filter pencarian dan status -->
                    <div class="task-filters">
                        <div class="search-box">
                            <input type="text" id="search" class="search-input" placeholder="Cari tugas...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        <select id="status-filter" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="overdue">Terlambat</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <select id="sort-by" class="filter-select">
                            <option value="created_at-DESC">Terbaru</option>
                            <option value="created_at-ASC">Terlama</option>
                            <option value="due_date-ASC">Jatuh Tempo</option>
                            <option value="priority-DESC">Prioritas</option>
                            <option value="title-ASC">Judul A-Z</option>
                        </select>
                    </div>
                    <!-- Container daftar tugas (scrollable) -->
                    <div class="task-list-container">
                        <!-- Loading spinner saat memuat tugas -->
                        <div id="loading" class="loading">
                            <div class="spinner"></div>
                            <p>Memuat tugas...</p>
                        </div>
                        <!-- Daftar tugas akan dirender di sini -->
                        <div id="task-list"></div>
                        <!-- State kosong jika belum ada tugas -->
                        <div id="empty-state" class="empty-state" style="display: none;">
                            <i class="fas fa-inbox"></i>
                            <h3>Belum ada tugas</h3>
                            <p>Mulai dengan menambahkan tugas pertama Anda!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </div>

    <!-- Modal edit tugas -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-edit"></i> Edit Tugas
                </h3>
                <!-- Tombol close modal -->
                <button class="close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-task-form">
                    <!-- Hidden input untuk ID tugas -->
                    <input type="hidden" id="edit-task-id" name="task_id">
                    <!-- Edit judul tugas -->
                    <div class="form-group">
                        <label class="form-label" for="edit-title">Judul Tugas *</label>
                        <input type="text" id="edit-title" name="title" class="form-input" required maxlength="200">
                        <div class="validation-error" id="edit-title-error">Judul tugas harus diisi</div>
                    </div>
                    <!-- Edit deskripsi tugas -->
                    <div class="form-group">
                        <label class="form-label" for="edit-description">Deskripsi</label>
                        <textarea id="edit-description" name="description" class="form-textarea" rows="3" maxlength="1000"></textarea>
                        <div class="validation-error" id="edit-description-error">Deskripsi terlalu panjang</div>
                    </div>
                    <!-- Edit tanggal jatuh tempo -->
                    <div class="form-group">
                        <label class="form-label" for="edit-due-date">Tanggal Jatuh Tempo</label>
                        <input type="date" id="edit-due-date" name="due_date" class="form-input">
                    </div>
                    <!-- Edit jam jatuh tempo -->
                    <div class="form-group">
                        <label class="form-label" for="edit-due-time">Jam Jatuh Tempo</label>
                        <input type="time" id="edit-due-time" name="due_time" class="form-input">
                        <div class="validation-error" id="edit-due-time-error">Jam tidak valid</div>
                    </div>
                    <!-- Edit prioritas -->
                    <div class="form-group">
                        <label class="form-label" for="edit-priority">Prioritas</label>
                        <select id="edit-priority" name="priority" class="form-select">
                            <option value="low">Rendah</option>
                            <option value="medium">Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                    <!-- Edit status -->
                    <div class="form-group">
                        <label class="form-label" for="edit-status">Status</label>
                        <select id="edit-status" name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Tombol batal edit -->
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <!-- Tombol simpan perubahan -->
                <button type="button" class="btn btn-primary" onclick="updateTask()">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <script>
        // =======================
        // Variabel global
        // =======================
        let tasks = []; // Menyimpan daftar tugas
        let currentEditTask = null; // Menyimpan tugas yang sedang diedit

        // =======================
        // Cek autentikasi user
        // =======================
        function checkAuthenticationStatus() {
            // Mengecek parameter URL jika ada error autentikasi
            const urlParams = new URLSearchParams(window.location.search);
            const authError = urlParams.get('auth_error');
            
            if (authError === 'required') {
                showAuthProtectionAlert();
                // Redirect ke login setelah alert tampil
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 3000);
                return false;
            }
            return true;
        }

        // Menampilkan alert proteksi autentikasi
        function showAuthProtectionAlert() {
            const alert = document.getElementById('auth-protection-alert');
            alert.style.display = 'block';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        // =======================
        // Inisialisasi halaman
        // =======================
        document.addEventListener('DOMContentLoaded', function() {
            // Cek autentikasi dulu
            if (!checkAuthenticationStatus()) {
                return;
            }

            loadStats(); // Memuat statistik tugas
            loadTasks(); // Memuat daftar tugas
            
            // Event submit form tambah tugas
            document.getElementById('task-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                // Ambil data dari form
                const title = document.getElementById('title').value.trim();
                const description = document.getElementById('description').value.trim();
                const due_date = document.getElementById('due_date').value;
                const due_time = document.getElementById('due_time').value;
                const priority = document.getElementById('priority').value;

                // Siapkan data untuk dikirim ke backend
                const formData = new FormData();
                formData.append('action', 'create');
                formData.append('title', title);
                formData.append('description', description);
                formData.append('due_date', due_date);
                formData.append('due_time', due_time);
                formData.append('priority', priority);

                // Kirim data ke TaskController.php
                const response = await fetch('TaskController.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                // Tampilkan notifikasi sesuai hasil
                if (result.success) {
                    showAlert(result.message, 'success');
                    this.reset();
                    loadTasks();
                    loadStats();
                } else {
                    showAlert(result.message, 'error');
                }
            });

            // Event pencarian tugas
            document.getElementById('search').addEventListener('input', debounce(loadTasks, 300));
            // Event filter status tugas
            document.getElementById('status-filter').addEventListener('change', loadTasks);
            // Event urutkan tugas
            document.getElementById('sort-by').addEventListener('change', loadTasks);
            
            // Set tanggal minimum pada input tanggal (tidak bisa pilih tanggal lampau)
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('due_date').setAttribute('min', today);
            document.getElementById('edit-due-date').setAttribute('min', today);

            // Event klik di luar modal edit untuk menutup modal
            document.getElementById('edit-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

            // Event tekan tombol Escape untuk menutup modal edit
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.getElementById('edit-modal').classList.contains('show')) {
                    closeEditModal();
                }
            });
        });

        // =======================
        // Validasi form
        // =======================
        function validateForm(formPrefix = '') {
            let isValid = true;
            const prefix = formPrefix ? formPrefix + '-' : '';
            
            // Validasi judul tugas
            const title = document.getElementById(prefix + 'title');
            const titleError = document.getElementById(prefix + 'title-error');
            if (!title.value.trim()) {
                showFieldError(title, titleError, 'Judul tugas harus diisi');
                isValid = false;
            } else if (title.value.length > 200) {
                showFieldError(title, titleError, 'Judul tugas terlalu panjang (maksimal 200 karakter)');
                isValid = false;
            } else {
                hideFieldError(title, titleError);
            }

            // Validasi deskripsi tugas
            const description = document.getElementById(prefix + 'description');
            const descriptionError = document.getElementById(prefix + 'description-error');
            if (description.value.length > 1000) {
                showFieldError(description, descriptionError, 'Deskripsi terlalu panjang (maksimal 1000 karakter)');
                isValid = false;
            } else {
                hideFieldError(description, descriptionError);
            }

            // Validasi tanggal jatuh tempo
            const dueDate = document.getElementById(prefix + 'due-date') || document.getElementById(prefix + 'due_date');
            const dueDateError = document.getElementById(prefix + 'due-date-error') || document.getElementById(prefix + 'due_date-error');
            if (dueDate && dueDate.value) {
                const selectedDate = new Date(dueDate.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    showFieldError(dueDate, dueDateError, 'Tanggal jatuh tempo tidak boleh di masa lalu');
                    isValid = false;
                } else {
                    hideFieldError(dueDate, dueDateError);
                }
            }

            return isValid;
        }

        // Menampilkan pesan error pada field form
        function showFieldError(field, errorElement, message) {
            field.classList.add('error');
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
        }

        // Menyembunyikan pesan error pada field form
        function hideFieldError(field, errorElement) {
            field.classList.remove('error');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }

        // =======================
        // Fungsi API call (fetch)
        // =======================
        async function apiCall(url, data = null, method = 'GET') {
            const options = {
                method: method,
                headers: {
                    'Content-Type': method === 'POST' ? 'application/x-www-form-urlencoded' : 'application/json'
                }
            };
            
            if (data && method === 'POST') {
                options.body = new URLSearchParams(data);
            }
            
            try {
                const response = await fetch(url, options);
                const result = await response.json();
                
                // Jika error autentikasi, tampilkan alert dan redirect
                if (result.error === 'authentication_required') {
                    showAlert('Sesi Anda telah berakhir. Silakan login kembali.', 'error');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                    return { success: false, message: 'Authentication required' };
                }
                
                return result;
            } catch (error) {
                console.error('API Error:', error);
                showAlert('Terjadi kesalahan koneksi', 'error');
                return { success: false, message: 'Kesalahan koneksi' };
            }
        }

        // =======================
        // Memuat daftar tugas
        // =======================
        async function loadTasks() {
            showLoading(true); // Tampilkan loading
            
            // Ambil filter pencarian, status, dan urutan
            const search = document.getElementById('search').value;
            const status = document.getElementById('status-filter').value;
            const sortBy = document.getElementById('sort-by').value;
            const [orderBy, orderDir] = sortBy.split('-');
            
            // Siapkan parameter untuk API
            const params = new URLSearchParams({
                action: 'read',
                search: search,
                status: status,
                order_by: orderBy,
                order_dir: orderDir
            });
            
            // Panggil API untuk mengambil data tugas
            const result = await apiCall(`TaskController.php?${params}`);
            
            showLoading(false); // Sembunyikan loading
            
            if (result.success) {
                tasks = result.tasks;
                renderTasks();
            } else {
                showAlert(result.message, 'error');
            }
        }

        // =======================
        // Memuat statistik tugas
        // =======================
        async function loadStats() {
            const result = await apiCall('TaskController.php?action=stats');
            
            if (result.success) {
                const stats = result.stats;
                document.getElementById('total-tasks').textContent = stats.total_tasks || 0;
                document.getElementById('completed-tasks').textContent = stats.completed_tasks || 0;
                document.getElementById('pending-tasks').textContent = stats.pending_tasks || 0;
                document.getElementById('overdue-tasks').textContent = stats.overdue_tasks || 0;
            }
        }

        // =======================
        // Render daftar tugas ke HTML
        // =======================
        function renderTasks() {
            const container = document.getElementById('task-list');
            const emptyState = document.getElementById('empty-state');
            
            if (tasks.length === 0) {
                container.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }
            
            emptyState.style.display = 'none';
            
            // Render setiap tugas ke dalam HTML
            container.innerHTML = tasks.map(task => {
                const isOverdue = task.due_date && new Date(task.due_date) < new Date() && task.status === 'pending';
                const taskClass = task.status === 'completed' ? 'completed' : (isOverdue ? 'overdue' : '');
                
                return `
                    <div class="task-item ${taskClass}">
                        <div class="task-header">
                            <div>
                                <div class="task-title ${task.status === 'completed' ? 'completed' : ''}">${escapeHtml(task.title)}</div>
                                <div class="task-priority priority-${task.priority}">${getPriorityText(task.priority)}</div>
                            </div>
                        </div>
                        ${task.description ? `<div class="task-description">${escapeHtml(task.description)}</div>` : ''}
                        <div class="task-meta">
                            <div class="task-due-date">
                                ${task.due_date ? `<i class="fas fa-calendar"></i> ${formatDateOnly(task.due_date)}` : ''}
                                ${task.due_time ? `<span style="margin-left:8px"><i class="fas fa-clock"></i> ${task.due_time}</span>` : ''}
                                ${isOverdue ? '<span style="color: var(--danger-color); margin-left: 10px;"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>' : ''}
                            </div>
                            <div class="task-actions">
                                <button class="btn btn-small ${task.status === 'completed' ? 'btn-warning' : 'btn-success'}" 
                                        onclick="toggleTask(${task.id})" title="${task.status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai'}">
                                    <i class="fas ${task.status === 'completed' ? 'fa-undo' : 'fa-check'}"></i>
                                </button>
                                <button class="btn btn-small btn-info" onclick="openEditModal(${task.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-small btn-danger" onclick="deleteTask(${task.id})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // =======================
        // Utility functions
        // =======================

        // Menampilkan alert notifikasi
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            const messageEl = document.getElementById('alert-message');
            
            alert.className = `alert alert-${type}`;
            messageEl.textContent = message;
            alert.style.display = 'block';
            
            // Sembunyikan alert otomatis setelah 5 detik
            setTimeout(() => {
                closeAlert();
            }, 5000);
        }

        // Menutup alert notifikasi
        function closeAlert() {
            const alert = document.getElementById('alert');
            alert.style.display = 'none';
        }

        // Menampilkan atau menyembunyikan loading spinner
        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }

        // Escape karakter HTML agar aman ditampilkan
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Format tanggal (hanya tanggal, tanpa jam)
        function formatDateOnly(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Mendapatkan teks prioritas
        function getPriorityText(priority) {
            const priorities = {
                'low': 'Rendah',
                'medium': 'Sedang',
                'high': 'Tinggi'
            };
            return priorities[priority] || priority;
        }

        // Fungsi debounce untuk membatasi pemanggilan fungsi
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // =======================
        // Fungsi Logout
        // =======================
        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                showAlert('Logging out...', 'info');
                setTimeout(() => {
                    window.location.href = 'logout.php';
                }, 1000);
            }
        }

        // =======================
        // Toggle status tugas (selesai/belum)
        // =======================
        async function toggleTask(taskId) {
            const result = await apiCall('TaskController.php', {
                action: 'toggle',
                task_id: taskId
            }, 'POST');
            
            if (result.success) {
                showAlert(result.message, 'success');
                loadTasks();
                loadStats();
            } else {
                showAlert(result.message, 'error');
            }
        }

        // =======================
        // Hapus tugas
        // =======================
        async function deleteTask(taskId) {
            if (!confirm('Apakah Anda yakin ingin menghapus tugas ini?')) return;
            
            const result = await apiCall('TaskController.php', {
                action: 'delete',
                task_id: taskId
            }, 'POST');
            
            if (result.success) {
                showAlert(result.message, 'success');
                loadTasks();
                loadStats();
            } else {
                showAlert(result.message, 'error');
            }
        }

        // =======================
        // Modal Edit Tugas
        // =======================
        function openEditModal(taskId) {
            // Cari data tugas yang akan diedit
            const task = tasks.find(t => t.id == taskId);
            if (!task) {
                showAlert('Tugas tidak ditemukan', 'error');
                return;
            }
            
            currentEditTask = task; 
            
            // Parse tanggal dan jam jatuh tempo
            let dueDate = '';
            let dueTime = '';
            if (task.due_date) {
                const dateObj = new Date(task.due_date);
                if (!isNaN(dateObj.getTime())) {
                    dueDate = dateObj.toISOString().split('T')[0];
                    dueTime = dateObj.toTimeString().split(' ')[0].substring(0, 5);
                }
            }

            // Isi form edit dengan data tugas
            document.getElementById('edit-task-id').value = task.id;
            document.getElementById('edit-title').value = task.title;
            document.getElementById('edit-description').value = task.description || '';
            document.getElementById('edit-due-date').value = dueDate;
            document.getElementById('edit-due-time').value = dueTime;
            document.getElementById('edit-priority').value = task.priority;
            document.getElementById('edit-status').value = task.status;
            
            // Bersihkan error validasi sebelumnya
            clearValidationErrors('edit');
            
            // Tampilkan modal edit
            const modal = document.getElementById('edit-modal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Fokus ke input judul
            setTimeout(() => {
                document.getElementById('edit-title').focus();
            }, 100);
        }

        // Menutup modal edit tugas
        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
            currentEditTask = null;
            document.getElementById('edit-task-form').reset();
            clearValidationErrors('edit');
        }

        // Bersihkan error validasi pada form edit
        function clearValidationErrors(prefix = '') {
            const prefixStr = prefix ? prefix + '-' : '';
            const errorElements = document.querySelectorAll(`[id*="${prefixStr}"][id$="-error"]`);
            const inputElements = document.querySelectorAll(`[id*="${prefixStr}"].form-input, [id*="${prefixStr}"].form-textarea, [id*="${prefixStr}"].form-select`);
            
            errorElements.forEach(el => el.style.display = 'none');
            inputElements.forEach(el => el.classList.remove('error'));
        }

        // =======================
        // Update tugas (simpan edit)
        // =======================
        async function updateTask() {
            if (!validateForm('edit')) {
                showAlert('Mohon periksa kembali form Anda', 'error');
                return;
            }
            
            if (!currentEditTask) {
                showAlert('Tidak ada tugas yang dipilih untuk diedit', 'error');
                return;
            }
            
            // Siapkan data tugas yang akan diupdate
            const taskData = {
                action: 'update',
                task_id: currentEditTask.id,
                title: document.getElementById('edit-title').value.trim(),
                description: document.getElementById('edit-description').value.trim(),
                due_date: document.getElementById('edit-due-date').value,
                due_time: document.getElementById('edit-due-time').value,
                priority: document.getElementById('edit-priority').value,
                status: document.getElementById('edit-status').value
            };
            
            // Kirim data ke backend
            const result = await apiCall('TaskController.php', taskData, 'POST');
            
            if (result.success) {
                showAlert(result.message, 'success');
                closeEditModal();
                loadTasks();
                loadStats();
            } else {
                showAlert(result.message, 'error');
            }
        }
    </script>
</body>
</html>