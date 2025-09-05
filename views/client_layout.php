<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }
        
        body {
            background-color: var(--light-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .client-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .client-sidebar {
            background: white;
            min-height: 100vh;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.05);
        }
        
        .client-sidebar .nav-link {
            color: var(--dark-color);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .client-sidebar .nav-link:hover,
        .client-sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(4px);
        }
        
        .client-sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
        }
        
        .client-main {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 1rem;
            padding: 2rem;
        }
        
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stats-card .stats-label {
            opacity: 0.9;
            font-size: 0.875rem;
        }
        
        .order-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }
        
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-new { background-color: #dbeafe; color: #1e40af; }
        .status-in_progress { background-color: #fef3c7; color: #92400e; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        
        .btn-client {
            background: var(--primary-color);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-client:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .chat-container {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #f9fafb;
        }
        
        .chat-message {
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            max-width: 80%;
        }
        
        .chat-message.client {
            background-color: var(--primary-color);
            color: white;
            margin-left: auto;
        }
        
        .chat-message.staff {
            background-color: white;
            border: 1px solid #e5e7eb;
        }
        
        .loyalty-card {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .loyalty-level {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .loyalty-discount {
            font-size: 1.5rem;
            opacity: 0.9;
        }
        
        .progress-bar {
            height: 0.5rem;
            border-radius: 0.25rem;
            background-color: rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: white;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['client_id'])): ?>
        <!-- Заголовок личного кабинета -->
        <header class="client-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Личный кабинет клиента
                        </h1>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="me-3">
                            <i class="fas fa-user me-1"></i>
                            <?= $_SESSION['client_name'] ?? 'Клиент' ?>
                        </span>
                        <a href="index.php?page=client_logout" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>
                            Выйти
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid">
            <div class="row">
                <!-- Боковая панель -->
                <div class="col-md-3 col-lg-2 px-0">
                    <div class="client-sidebar p-3">
                        <div class="text-center mb-4">
                            <h5 class="text-primary">
                                <i class="fas fa-tools me-2"></i>
                                Меню
                            </h5>
                        </div>
                        <nav class="nav flex-column">
                            <a class="nav-link <?= $page === 'client_portal' ? 'active' : '' ?>" 
                               href="index.php?page=client_portal">
                                <i class="fas fa-tachometer-alt"></i> Главная
                            </a>
                            <a class="nav-link <?= $page === 'client_orders' ? 'active' : '' ?>" 
                               href="index.php?page=client_portal&action=orders">
                                <i class="fas fa-clipboard-list"></i> Мои заказы
                            </a>
                            <a class="nav-link <?= $page === 'client_vehicles' ? 'active' : '' ?>" 
                               href="index.php?page=client_portal&action=vehicles">
                                <i class="fas fa-car"></i> Мои автомобили
                            </a>
                            <a class="nav-link <?= $page === 'client_service_book' ? 'active' : '' ?>" 
                               href="index.php?page=client_portal&action=service_book">
                                <i class="fas fa-book"></i> Сервисная книга
                            </a>
                            <a class="nav-link <?= $page === 'client_chat' ? 'active' : '' ?>" 
                               href="index.php?page=client_portal&action=chat">
                                <i class="fas fa-comments"></i> Чат с сервисом
                            </a>
                            <a class="nav-link <?= $page === 'client_loyalty' ? 'active' : '' ?>" 
                               href="index.php?page=client_portal&action=loyalty">
                                <i class="fas fa-star"></i> Программа лояльности
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Основной контент -->
                <div class="col-md-9 col-lg-10">
                    <div class="client-main">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php
                                switch ($_GET['success']) {
                                    case '1': echo 'Заказ успешно создан!'; break;
                                    case '2': echo 'Заказ успешно обновлен!'; break;
                                    case '3': echo 'Сообщение отправлено!'; break;
                                    default: echo 'Операция выполнена успешно!';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php
                                switch ($_GET['error']) {
                                    case 'access': echo 'Доступ запрещен!'; break;
                                    case 'not_found': echo 'Заказ не найден!'; break;
                                    default: echo 'Произошла ошибка!';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Здесь будет основной контент страницы -->
                        <?php include $content; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Контент для неавторизованных клиентов -->
        <?php include $content; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
