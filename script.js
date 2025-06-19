// Ambil elemen utama form login/register
const loginUsers = document.querySelector('.login-users');
// Ambil link untuk ke form login
const loginLink = document.querySelector('.login-link');
// Ambil link untuk ke form register
const registerLink = document.querySelector('.register-link');
// Ambil container alert (notifikasi)
const alertContainer = document.querySelector('.alert-container');

// Event klik pada link register, tampilkan form register (geser)
registerLink.addEventListener('click', () => loginUsers.classList.add('slide'));
// Event klik pada link login, tampilkan form login (geser balik)
loginLink.addEventListener('click', () => loginUsers.classList.remove('slide'));

// Jika ada alert (notifikasi)
if (alertContainer) {
    // Ambil semua elemen alert di dalam container
    const alerts = alertContainer.querySelectorAll('.alert');

    // Untuk setiap alert
    alerts.forEach((alert, i) => {
        // Delay kemunculan alert (opsional)
        setTimeout(() => {
            alert.style.opacity = '1';
        }, 100);

        // Auto fade dan hapus alert setelah beberapa detik
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 500); // Hapus setelah transisi selesai
        }, 3000 + i * 300); // Delay per alert agar tidak tumpang tindih
    });
}

// Auto-hide alerts setelah halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua alert
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Setelah 5 detik, animasi keluar dan hapus alert
        setTimeout(() => {
            if (alert.parentElement) {
                alert.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
});

// Fungsi untuk toggle (lihat/sembunyi) password
function togglePassword(inputId) {
    // Ambil input password berdasarkan id
    const input = document.getElementById(inputId);
    // Ambil tombol toggle (berada setelah input)
    const button = input.nextElementSibling;
    // Ambil ikon di dalam tombol
    const icon = button.querySelector('i');
    
    // Jika tipe input password, ubah jadi text (lihat password)
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bx bx-hide';
    } else {
        // Jika sudah text, ubah kembali ke password (sembunyikan)
        input.type = 'password';
        icon.className = 'bx bx-show';
    }
}

// Jika ada input password pada form register
if (document.getElementById('registerPassword')) {
    // Event input pada password register (cek kekuatan password)
    document.getElementById('registerPassword').addEventListener('input', function() {
        // Ambil nilai password
        const password = this.value;
        // Ambil elemen meter, bar, dan teks kekuatan password
        const meter = document.getElementById('strengthMeter');
        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        
        // Jika password kosong, sembunyikan meter dan teks
        if (password.length === 0) {
            meter.style.display = 'none';
            text.textContent = '';
            return;
        }
        
        // Tampilkan meter kekuatan password
        meter.style.display = 'block';
        
        let strength = 0; // Nilai kekuatan password
        let strengthClass = ''; // Kelas CSS untuk bar
        let strengthLabel = ''; // Label teks kekuatan
        
        // Cek kriteria password
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        // Tentukan level kekuatan password
        if (strength <= 2) {
            strengthClass = 'strength-weak';
            strengthLabel = 'Lemah';
            text.style.color = '#dc3545';
        } else if (strength <= 3) {
            strengthClass = 'strength-fair';
            strengthLabel = 'Cukup';
            text.style.color = '#ffc107';
        } else if (strength <= 4) {
            strengthClass = 'strength-good';
            strengthLabel = 'Baik';
            text.style.color = '#28a745';
        } else {
            strengthClass = 'strength-strong';
            strengthLabel = 'Kuat';
            text.style.color = '#007bff';
        }
        
        // Set kelas bar dan teks kekuatan
        bar.className = 'strength-bar ' + strengthClass;
        text.textContent = 'Kekuatan Password: ' + strengthLabel;
    });
}

// Validasi form login dan tampilkan loading saat submit
if (document.getElementById('loginForm')) {
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        // Ambil nilai email dan password
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        const btn = document.getElementById('loginBtn');
        
        // Jika ada field kosong, cegah submit dan tampilkan alert
        if (!email || !password) {
            e.preventDefault();
            alert('Semua field harus diisi!');
            return;
        }
        
        // Tampilkan loading pada tombol login
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span>Memproses...';
    });
}

// Validasi form register dan tampilkan loading saat submit
if (document.getElementById('registerForm')) {
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        // Ambil nilai nama, email, dan password
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const btn = document.getElementById('registerBtn');
        
        // Jika ada field kosong, cegah submit dan tampilkan alert
        if (!name || !email || !password) {
            e.preventDefault();
            alert('Semua field harus diisi!');
            return;
        }
        
        // Validasi nama minimal 2 karakter
        if (name.length < 2) {
            e.preventDefault();
            alert('Nama minimal 2 karakter!');
            return;
        }
        
        // Validasi password minimal 6 karakter
        if (password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return;
        }
        
        // Tampilkan loading pada tombol daftar
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span>Mendaftar...';
    });
}

// Reset loading state tombol jika halaman reload
window.addEventListener('load', function() {
    // Ambil tombol login dan register
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    
    // Reset tombol login jika ada
    if (loginBtn) {
        loginBtn.disabled = false;
        loginBtn.innerHTML = 'Login';
    }
    
    // Reset tombol daftar jika ada
    if (registerBtn) {
        registerBtn.disabled = false;
        registerBtn.innerHTML = 'Daftar';
    }
});

