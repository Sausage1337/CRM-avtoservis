<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --apple-blue: #007AFF;
            --apple-gray: #F2F2F7;
            --apple-gray-2: #E5E5EA;
            --apple-gray-3: #C7C7CC;
            --apple-black: #1C1C1E;
            --apple-white: #FFFFFF;
            --apple-red: #FF3B30;
            --apple-green: #34C759;
            --apple-border-radius: 12px;
            --apple-border-radius-lg: 20px;
            --apple-shadow: 0 2px 16px rgba(0, 0, 0, 0.12);
            --apple-shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
        }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--apple-gray);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: var(--apple-border-radius-lg);
            box-shadow: var(--apple-shadow-lg);
            border: 1px solid var(--apple-gray-2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .login-header {
            text-align: center;
            padding: 40px 40px 20px;
            background: white;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--apple-blue), #5856D6);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
        }

        .login-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--apple-black);
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 15px;
            color: #8E8E93;
            margin-bottom: 0;
        }

        .login-body {
            padding: 20px 40px 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 15px;
            font-weight: 500;
            color: var(--apple-black);
            margin-bottom: 8px;
            display: block;
        }

        .form-control-apple {
            width: 100%;
            padding: 16px;
            border: 1px solid var(--apple-gray-3);
            border-radius: var(--apple-border-radius);
            font-size: 15px;
            background: white;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control-apple:focus {
            border-color: var(--apple-blue);
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
        }

        .btn-apple-primary {
            width: 100%;
            background: var(--apple-blue);
            border: none;
            border-radius: var(--apple-border-radius);
            padding: 16px;
            font-weight: 500;
            font-size: 15px;
            color: white;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            margin-bottom: 20px;
        }

        .btn-apple-primary:hover {
            background: #0056b3;
            transform: scale(1.02);
            color: white;
        }

        .btn-apple-primary:active {
            transform: scale(0.98);
        }

        .alert-apple {
            border: none;
            border-radius: var(--apple-border-radius);
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            background: rgba(255, 59, 48, 0.1);
            color: var(--apple-red);
            border-left: 4px solid var(--apple-red);
        }

        .text-link {
            color: var(--apple-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .text-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            text-align: center;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--apple-gray-3);
        }

        .divider span {
            padding: 0 16px;
            color: #8E8E93;
            font-size: 14px;
        }

        .demo-info {
            background: rgba(0, 122, 255, 0.05);
            border: 1px solid rgba(0, 122, 255, 0.2);
            border-radius: var(--apple-border-radius);
            padding: 12px 16px;
            text-align: center;
            font-size: 13px;
            color: var(--apple-blue);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                margin: 0 16px;
            }
            
            .login-header,
            .login-body {
                padding-left: 24px;
                padding-right: 24px;
            }
            
            .logo-icon {
                width: 64px;
                height: 64px;
                font-size: 28px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <h1 class="login-title"><?= APP_NAME ?></h1>
            <p class="login-subtitle">Добро пожаловать в систему</p>
        </div>
        
        <div class="login-body">
            <?php if (isset($error)): ?>
                <div class="alert-apple">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?page=login">
                <div class="form-group">
                    <label for="username" class="form-label">Имя пользователя</label>
                    <input type="text" 
                           class="form-control-apple" 
                           id="username" 
                           name="username" 
                           value="<?= $_POST['username'] ?? '' ?>" 
                           placeholder="Введите имя пользователя"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" 
                           class="form-control-apple" 
                           id="password" 
                           name="password" 
                           placeholder="Введите пароль"
                           required>
                </div>
                
                <button type="submit" class="btn-apple-primary">
                    Войти в систему
                </button>
            </form>
            
            <div class="text-center">
                <p class="mb-0">
                    Нет аккаунта? 
                    <a href="index.php?page=register" class="text-link">Зарегистрироваться</a>
                </p>
            </div>
            
            <div class="divider">
                <span>Демо доступ</span>
            </div>
            
            <div class="demo-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>admin</strong> / <strong>admin123</strong>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Apple-style form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control-apple');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
            
            // Button press effect
            const button = document.querySelector('.btn-apple-primary');
            if (button) {
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                button.addEventListener('mouseup', function() {
                    this.style.transform = 'scale(1.02)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            }
        });
    </script>
</body>
</html>
