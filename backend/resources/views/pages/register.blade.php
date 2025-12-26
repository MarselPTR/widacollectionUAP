<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Wida Collection</title>
    <!-- Preload halaman login untuk transisi instan -->
    <link rel="prefetch" href="login">
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="app.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow-y: auto;
        }

        /* ========== FAST PAGE TRANSITION ========== */
        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            pointer-events: none;
            display: flex;
            overflow: hidden;
        }

        .transition-panel {
            flex: 1;
            background: linear-gradient(180deg, #1a1a2e 0%, #0f3460 50%, #16213e 100%);
            transform: translateY(100%);
            will-change: transform;
            margin-left: -1px;
        }

        .transition-panel:first-child {
            margin-left: 0;
        }

        /* Staggered delays - much faster */
        .transition-panel:nth-child(1) {
            transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 0ms;
        }

        .transition-panel:nth-child(2) {
            transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 30ms;
        }

        .transition-panel:nth-child(3) {
            transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 60ms;
        }

        .transition-panel:nth-child(4) {
            transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 90ms;
        }

        .transition-panel:nth-child(5) {
            transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 120ms;
        }

        .page-transition.entering .transition-panel {
            transform: translateY(0);
        }

        .page-transition.exiting .transition-panel {
            transform: translateY(-100%);
        }

        /* Center Logo During Transition */
        .transition-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            z-index: 10000;
            opacity: 0;
            pointer-events: none;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
        }

        .transition-logo.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .transition-logo .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 15px 50px rgba(255, 107, 107, 0.5);
            animation: logoPulse 0.6s ease-in-out infinite alternate;
        }

        @keyframes logoPulse {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.08);
            }
        }

        /* ========== AUTH CONTAINER ========== */
        .auth-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            opacity: 0;
            animation: pageEnter 0.4s ease forwards 0.1s;
        }

        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Left Panel - Background Image Slideshow */
        .auth-left {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
            overflow: hidden;
        }

        /* Background Slideshow */
        .bg-slideshow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
        }

        .bg-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.2s ease-in-out, transform 6s ease-out;
            transform: scale(1.08);
        }

        .bg-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .bg-slide:nth-child(1) {
            background-image: url('https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=1600&q=80');
        }

        .bg-slide:nth-child(2) {
            background-image: url('https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&w=1600&q=80');
        }

        .bg-slide:nth-child(3) {
            background-image: url('https://images.unsplash.com/photo-1560243563-062bfc001d68?auto=format&fit=crop&w=1600&q=80');
        }

        .bg-slide:nth-child(4) {
            background-image: url('https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1600&q=80');
        }

        /* Gradient Overlay - Red theme for register */
        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.88) 0%, rgba(255, 107, 107, 0.65) 100%);
            z-index: 1;
        }

        .bg-overlay::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(26, 26, 46, 0.95) 100%);
        }

        /* Slide Indicators */
        .slide-indicators {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .slide-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slide-indicator.active {
            width: 24px;
            border-radius: 4px;
            background: #ff6b6b;
        }

        .auth-left-content {
            position: relative;
            z-index: 10;
            color: white;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 32px rgba(255, 107, 107, 0.3);
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        .brand-name span {
            color: #ff6b6b;
        }

        .auth-tagline {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .auth-description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 380px;
            line-height: 1.6;
        }

        /* Features List */
        .auth-features {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .auth-feature i {
            color: #ff6b6b;
            font-size: 1rem;
        }

        /* Right Panel - Form */
        .auth-right {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
        }

        .auth-form-container {
            width: 100%;
            max-width: 420px;
            padding: 1rem 0;
        }

        /* Tab Toggle */
        .auth-tabs {
            display: flex;
            background: #f3f4f6;
            border-radius: 50px;
            padding: 4px;
            margin-bottom: 2rem;
        }

        .auth-tab {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            color: #6b7280;
            border: none;
            background: transparent;
        }

        .auth-tab.active {
            background: white;
            color: #1f2937;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .auth-tab:hover:not(.active) {
            color: #374151;
            background: rgba(255, 255, 255, 0.5);
        }

        /* Form Header */
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .auth-title .emoji {
            font-size: 1.5rem;
        }

        .auth-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Form */
        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.125rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-label-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-link {
            font-size: 0.75rem;
            color: #ff6b6b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .form-link:hover {
            color: #4ecdc4;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9375rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff6b6b;
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        /* Password Strength */
        .password-strength {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.25rem;
        }

        .strength-bar {
            flex: 1;
            height: 3px;
            background: #e5e7eb;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-bar.weak {
            background: #ef4444;
        }

        .strength-bar.medium {
            background: #f59e0b;
        }

        .strength-bar.strong {
            background: #10b981;
        }

        /* Checkbox */
        .form-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
        }

        .form-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: #ff6b6b;
            cursor: pointer;
        }

        .form-checkbox label {
            font-size: 0.8125rem;
            color: #6b7280;
            cursor: pointer;
            line-height: 1.4;
        }

        .form-checkbox label a {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: 500;
        }

        .form-checkbox label a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
            color: white;
            font-size: 0.9375rem;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 16px rgba(255, 107, 107, 0.3);
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(255, 107, 107, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Error Message */
        .form-error {
            padding: 0.75rem 1rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #dc2626;
            font-size: 0.875rem;
            text-align: center;
            display: none;
        }

        .form-error.show {
            display: block;
        }

        /* Success Message */
        .form-success {
            padding: 0.75rem 1rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            color: #16a34a;
            font-size: 0.875rem;
            text-align: center;
            display: none;
        }

        .form-success.show {
            display: block;
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.25rem 0;
        }

        .auth-divider-line {
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .auth-divider-text {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Social Login */
        .btn-social {
            width: 100%;
            padding: 0.875rem;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }

        .btn-social:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* Footer Link */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .auth-footer a {
            color: #ff6b6b;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: #4ecdc4;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-left {
                min-height: 280px;
                padding: 2rem;
            }

            .auth-tagline {
                font-size: 1.75rem;
            }

            .auth-features {
                display: none;
            }

            .auth-right {
                padding: 2rem 1.5rem;
            }

            .slide-indicators {
                bottom: 1rem;
                right: 1rem;
            }
        }

        @media (max-width: 480px) {
            .auth-left {
                min-height: 220px;
                padding: 1.5rem;
            }

            .auth-tagline {
                font-size: 1.5rem;
            }

            .brand-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Page Transition -->
    <div class="page-transition" id="pageTransition">
        <div class="transition-panel"></div>
        <div class="transition-panel"></div>
        <div class="transition-panel"></div>
        <div class="transition-panel"></div>
        <div class="transition-panel"></div>
    </div>
    <div class="transition-logo" id="transitionLogo">
        <div class="logo-icon">
            <i class="fas fa-shirt"></i>
        </div>
    </div>

    <div class="auth-container">
        <!-- Left Panel -->
        <div class="auth-left">
            <!-- Background Slideshow -->
            <div class="bg-slideshow">
                <div class="bg-slide active"></div>
                <div class="bg-slide"></div>
                <div class="bg-slide"></div>
                <div class="bg-slide"></div>
            </div>
            <div class="bg-overlay"></div>

            <!-- Slide Indicators -->
            <div class="slide-indicators">
                <div class="slide-indicator active" data-slide="0"></div>
                <div class="slide-indicator" data-slide="1"></div>
                <div class="slide-indicator" data-slide="2"></div>
                <div class="slide-indicator" data-slide="3"></div>
            </div>

            <div class="auth-left-content">
                <div class="brand-logo">
                    <div class="brand-icon">
                        <i class="fas fa-shirt"></i>
                    </div>
                    <span class="brand-name">Wida<span>Collection</span></span>
                </div>
                <h1 class="auth-tagline">Mulai Perjalanan<br>Style Anda Bersama Kami.</h1>
                <p class="auth-description">Bergabung dengan ribuan member yang sudah menemukan fashion impian mereka.
                </p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Akses ke koleksi thrift eksklusif</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Notifikasi live drop terbaru</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Diskon spesial member hingga 30%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="auth-right">
            <div class="auth-form-container">
                <!-- Tab Toggle -->
                <div class="auth-tabs">
                    <a href="javascript:void(0)" class="auth-tab" data-page="login">Masuk</a>
                    <a href="javascript:void(0)" class="auth-tab active" data-page="register">Daftar</a>
                </div>

                <!-- Form Header -->
                <div class="auth-header">
                    <h2 class="auth-title">Buat Akun Baru <span class="emoji">✨</span></h2>
                    <p class="auth-subtitle">Isi data diri Anda untuk memulai</p>
                </div>

                <!-- Register Form -->
                <form id="registerForm" class="auth-form">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="form-label">
                                <span class="form-label-text">Nama Depan</span>
                            </div>
                            <input type="text" name="firstName" class="form-input" placeholder="John" required>
                        </div>

                        <div class="form-group">
                            <div class="form-label">
                                <span class="form-label-text">Nama Belakang</span>
                            </div>
                            <input type="text" name="lastName" class="form-input" placeholder="Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label">
                            <span class="form-label-text">Email</span>
                        </div>
                        <input type="email" name="email" class="form-input" placeholder="nama@email.com" required>
                    </div>

                    <div class="form-group">
                        <div class="form-label">
                            <span class="form-label-text">Password</span>
                        </div>
                        <input type="password" name="password" id="passwordInput" class="form-input"
                            placeholder="Minimal 6 karakter" required minlength="6">
                        <div class="password-strength">
                            <div class="strength-bar" id="bar1"></div>
                            <div class="strength-bar" id="bar2"></div>
                            <div class="strength-bar" id="bar3"></div>
                            <div class="strength-bar" id="bar4"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label">
                            <span class="form-label-text">Konfirmasi Password</span>
                        </div>
                        <input type="password" name="passwordConfirm" class="form-input" placeholder="Ulangi password"
                            required>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                        <label for="agreeTerms">Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a
                                href="#">Kebijakan Privasi</a></label>
                    </div>

                    <p id="registerStatus" class="form-error">Terjadi kesalahan saat mendaftar.</p>
                    <p id="registerSuccess" class="form-success">Pendaftaran berhasil! Mengalihkan...</p>

                    <button type="submit" class="btn-submit">Daftar Sekarang</button>
                </form>

                <!-- Divider -->
                <div class="auth-divider">
                    <span class="auth-divider-line"></span>
                    <span class="auth-divider-text">atau daftar dengan</span>
                    <span class="auth-divider-line"></span>
                </div>

                <!-- Social Login -->
                <button type="button" class="btn-social" onclick="alert('Fitur Google Signup akan segera hadir!')">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    Google
                </button>

                <!-- Footer -->
                <div class="auth-footer">
                    Sudah punya akun? <a href="javascript:void(0)" class="auth-nav-link" data-page="login">Masuk
                        disini</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/profile-data.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ========== Background Slideshow ==========
            const slides = document.querySelectorAll('.bg-slide');
            const indicators = document.querySelectorAll('.slide-indicator');
            let currentSlide = 0;
            let slideInterval;

            function goToSlide(index) {
                slides[currentSlide].classList.remove('active');
                indicators[currentSlide].classList.remove('active');

                currentSlide = index;
                if (currentSlide >= slides.length) currentSlide = 0;
                if (currentSlide < 0) currentSlide = slides.length - 1;

                slides[currentSlide].classList.add('active');
                indicators[currentSlide].classList.add('active');
            }

            function nextSlide() {
                goToSlide(currentSlide + 1);
            }

            function startSlideshow() {
                slideInterval = setInterval(nextSlide, 5000);
            }

            function resetSlideshow() {
                clearInterval(slideInterval);
                startSlideshow();
            }

            indicators.forEach(indicator => {
                indicator.addEventListener('click', () => {
                    const slideIndex = parseInt(indicator.dataset.slide);
                    goToSlide(slideIndex);
                    resetSlideshow();
                });
            });

            startSlideshow();

            // ========== FAST Page Transition ==========
            const pageTransition = document.getElementById('pageTransition');
            const transitionLogo = document.getElementById('transitionLogo');
            let isNavigating = false;

            function navigateWithTransition(url) {
                if (isNavigating) return;
                isNavigating = true;

                // Start preloading the target page immediately
                const preloadLink = document.createElement('link');
                preloadLink.rel = 'prefetch';
                preloadLink.href = url;
                document.head.appendChild(preloadLink);

                // Show logo immediately
                transitionLogo.classList.add('show');

                // Start panels entering
                pageTransition.classList.add('entering');

                // Navigate DURING the animation (not after)
                setTimeout(() => {
                    window.location.href = url;
                }, 200); // Navigate at 200ms
            }

            // Handle tab clicks with instant feedback
            document.querySelectorAll('.auth-tab, .auth-nav-link').forEach(link => {
                // Use mousedown for faster response
                link.addEventListener('mousedown', (e) => {
                    if (e.button !== 0) return;
                    e.preventDefault();
                    const page = link.dataset.page;
                    if (page && page !== 'register') {
                        navigateWithTransition(page);
                    }
                });

                link.addEventListener('click', (e) => {
                    e.preventDefault();
                });
            });

            // ========== Password Strength ==========
            const passwordInput = document.getElementById('passwordInput');
            const bars = [
                document.getElementById('bar1'),
                document.getElementById('bar2'),
                document.getElementById('bar3'),
                document.getElementById('bar4')
            ];

            passwordInput.addEventListener('input', () => {
                const val = passwordInput.value;
                let strength = 0;

                if (val.length >= 6) strength++;
                if (val.length >= 8) strength++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength++;
                if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) strength++;

                bars.forEach((bar, i) => {
                    bar.classList.remove('weak', 'medium', 'strong');
                    if (i < strength) {
                        if (strength <= 1) bar.classList.add('weak');
                        else if (strength <= 2) bar.classList.add('medium');
                        else bar.classList.add('strong');
                    }
                });
            });

            // ========== Register Form ==========
            const form = document.getElementById('registerForm');
            const statusEl = document.getElementById('registerStatus');
            const successEl = document.getElementById('registerSuccess');

            (async () => {
                if (!window.AuthStore) return;
                const me = await AuthStore.me();
                if (me) {
                    navigateWithTransition(me.is_admin ? 'admin' : 'body');
                }
            })();

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!window.AuthStore) {
                    alert('Sistem auth belum siap. Muat ulang halaman.');
                    return;
                }

                const firstName = form.firstName.value.trim();
                const lastName = form.lastName.value.trim();
                const email = form.email.value.trim().toLowerCase();
                const password = form.password.value;
                const passwordConfirm = form.passwordConfirm.value;
                const agreeTerms = form.agreeTerms.checked;

                statusEl.classList.remove('show');
                successEl.classList.remove('show');

                if (!firstName || !lastName) {
                    statusEl.textContent = 'Nama depan dan belakang wajib diisi.';
                    statusEl.classList.add('show');
                    return;
                }

                if (password.length < 6) {
                    statusEl.textContent = 'Password minimal 6 karakter.';
                    statusEl.classList.add('show');
                    return;
                }

                if (password !== passwordConfirm) {
                    statusEl.textContent = 'Konfirmasi password tidak cocok.';
                    statusEl.classList.add('show');
                    return;
                }

                if (!agreeTerms) {
                    statusEl.textContent = 'Anda harus menyetujui syarat dan ketentuan.';
                    statusEl.classList.add('show');
                    return;
                }

                try {
                    await AuthStore.registerAccount({
                        name: `${firstName} ${lastName}`,
                        email: email,
                        password: password
                    });

                    successEl.classList.add('show');

                    // Use elegant transition after showing success message
                    setTimeout(() => {
                        navigateWithTransition('body');
                    }, 1200);
                } catch (error) {
                    statusEl.textContent = error?.message || 'Terjadi kesalahan saat mendaftar.';
                    statusEl.classList.add('show');
                }
            });
        });
    </script>
</body>

</html>