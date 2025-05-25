<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - NigerDev</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background elements */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            margin: 0 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3); }
            50% { box-shadow: 0 15px 40px rgba(220, 38, 38, 0.5); }
            100% { box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3); }
        }

        .logo i {
            font-size: 32px;
            color: white;
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .tagline {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-input:focus + .input-icon {
            color: #dc2626;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #d1d5db;
            margin-right: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox:checked {
            background: #dc2626;
            border-color: #dc2626;
        }

        .checkbox-label {
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
        }

        .forgot-password {
            font-size: 14px;
            color: #dc2626;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #991b1b;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(220, 38, 38, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .button-icon {
            margin-right: 8px;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 6px;
        }

        .footer-text {
            text-align: center;
            margin-top: 32px;
            color: #6b7280;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                margin: 16px;
                border-radius: 20px;
            }

            .company-name {
                font-size: 24px;
            }
        }

        /* Loading animation */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<!-- Background animated shapes -->
<div class="bg-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
</div>

<div class="login-container">
    <div class="login-card">
        <!-- Logo and Branding -->
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-code"></i>
            </div>
            <h1 class="company-name">NigerDev</h1>
            <p class="tagline">Plateforme Employés</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Field -->
            <div class="form-group">
                <label class="form-label" for="email">Adresse email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="votre.email@nigerdev.com"
                    />
                </div>
                @if($errors->get('email'))
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ implode(', ', $errors->get('email')) }}
                    </div>
                @endif
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label class="form-label" for="password">Mot de passe</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input
                        id="password"
                        class="form-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                </div>
                @if($errors->get('password'))
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ implode(', ', $errors->get('password')) }}
                    </div>
                @endif
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="checkbox-wrapper">
                <div class="checkbox-group">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="checkbox"
                        name="remember"
                    />
                    <label for="remember_me" class="checkbox-label">
                        Se souvenir de moi
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit" class="login-button">
                <i class="fas fa-sign-in-alt button-icon"></i>
                Se connecter
            </button>
        </form>

        <!-- Footer -->
        <div class="footer-text">
            © 2024 NigerDev. Accès réservé aux employés.
        </div>
    </div>
</div>

<script>
    // Add loading state to button on form submit
    document.querySelector('form').addEventListener('submit', function() {
        const button = document.querySelector('.login-button');
        button.classList.add('loading');
        button.innerHTML = '<i class="fas fa-spinner fa-spin button-icon"></i>Connexion...';
        button.disabled = true;
    });

    // Add focus effects
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
</script>
</body>
</html>
