<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 15px 20px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-header">
            <h2><i class="fas fa-car"></i> <?= APP_NAME ?></h2>
            <p class="mb-0">Регистрация нового пользователя</p>
        </div>
        
        <div class="register-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><i class="fas fa-exclamation-triangle"></i> <?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?page=register">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i> Имя пользователя *
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= $formData['username'] ?? '' ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">
                            <i class="fas fa-id-card"></i> Полное имя *
                        </label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="<?= $formData['full_name'] ?? '' ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= $formData['email'] ?? '' ?>">
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Пароль *
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               minlength="6" required>
                        <small class="form-text text-muted">Минимум 6 символов</small>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-lock"></i> Подтвердите пароль *
                        </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-register">
                    <i class="fas fa-user-plus"></i> Зарегистрироваться
                </button>
            </form>
            
            <div class="login-link">
                <p>Уже есть аккаунт? <a href="index.php?page=login">Войти в систему</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
