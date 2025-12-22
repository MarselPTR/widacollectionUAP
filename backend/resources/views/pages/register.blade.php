<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Wida Collection</title>
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-page {
            background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
            min-height: 100vh;
        }
        .form-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(78,205,196,0.13);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            margin: 5vh auto;
        }
        .input {
            background: #f7fff7;
            border: 1.5px solid #4ecdc4;
            border-radius: 0.75rem;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            width: 100%;
            transition: border 0.2s, background 0.2s;
        }
        .input:focus {
            border-color: #ff6b6b;
            background: #ffffff;
        }
        .btn-main {
            background: linear-gradient(90deg, #4ecdc4 0%, #ff6b6b 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 0.85rem 2.2rem;
            border-radius: 2rem;
            box-shadow: 0 4px 24px 0 rgba(78,205,196,0.18);
            border: none;
            outline: none;
            letter-spacing: 1px;
            margin-top: 1.2rem;
            transition: background 0.3s, box-shadow 0.3s, transform 0.2s, outline 0.2s;
        }
        .link-register {
            color: #ff6b6b;
        }
    </style>
</head>
<body class="gradient-page font-poppins">
    <div class="form-card">
        <h2 class="text-2xl font-bold text-center mb-6 text-dark wc-reveal" style="--reveal-delay: 80ms;">Daftar Akun <span class="text-primary">Wida Collection</span></h2>
        <form id="registerForm">
            <input type="text" name="name" class="input" placeholder="Nama Lengkap" required>
            <input type="tel" name="phone" class="input" placeholder="Nomor WhatsApp" required>
            <input type="email" name="email" class="input" placeholder="Email" required>
            <input type="password" name="password" class="input" placeholder="Password" required>
            <input type="password" name="confirm" class="input" placeholder="Konfirmasi Password" required>
            <button type="submit" class="btn-main w-full wc-reveal" style="--reveal-delay: 180ms;">Daftar</button>
        </form>
        <p id="registerStatus" class="text-center text-sm text-red-500 mt-3 hidden"></p>
        <div class="text-center mt-4">
            <span class="wc-reveal" style="--reveal-delay: 240ms;">Sudah punya akun? <a href="login.html" class="link-register">Login</a></span>
        </div>
    </div>
    <script src="js/reveal.js" defer></script>
    <script src="js/profile-data.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('registerForm');
        const statusEl = document.getElementById('registerStatus');
        if (!form) return;
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!window.AuthStore || !window.ProfileStore) {
                alert('Sistem auth belum siap. Muat ulang halaman.');
                return;
            }
            const name = form.name.value.trim();
            const phone = form.phone.value.trim();
            const email = form.email.value.trim().toLowerCase();
            const password = form.password.value;
            const confirm = form.confirm.value;
            if (password.length < 6) {
                statusEl.textContent = 'Password minimal 6 karakter.';
                statusEl.classList.remove('hidden');
                return;
            }
            if (password !== confirm) {
                statusEl.textContent = 'Konfirmasi password tidak cocok.';
                statusEl.classList.remove('hidden');
                return;
            }
            try {
                await AuthStore.registerAccount({ name, email, password });
                // phone is stored in profile table.
                await ProfileStore.saveProfileData({ name, phone });
                statusEl.classList.add('hidden');
                window.location.href = 'profile.html';
            } catch (error) {
                statusEl.textContent = error.message || 'Gagal mendaftar. Coba lagi.';
                statusEl.classList.remove('hidden');
            }
        });
    });
    </script>
</body>
</html>