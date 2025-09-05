<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация клиентов - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .auth-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        
        .auth-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        
        .btn-auth {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            width: 100%;
        }
        
        .btn-auth:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        }
        
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .auth-links a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }
        
        .auth-links a:hover {
            color: #2563eb;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .alert {
            border-radius: 0.5rem;
            border: none;
            font-size: 0.875rem;
        }
        
        .success-message {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="auth-container">
                    <!-- Здесь будет основной контент страницы -->
                    <?php include $content; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
